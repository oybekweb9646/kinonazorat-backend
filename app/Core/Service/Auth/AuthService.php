<?php

namespace App\Core\Service\Auth;

use App\Core\Dto\Auth\JwtTokenDto;
use App\Core\Enums\Auth\AuthTypeEnum;
use App\Core\Helpers\DB\Transaction;
use App\Core\Repository\User\UserRepository;
use App\Core\Service\Auth\Contracts\AuthContract;
use App\Core\Service\Auth\Contracts\OneIdContract;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OneIdCodeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService implements AuthContract
{
    const string SCHEMA = 'Bearer';

    public function __construct(
        protected Transaction    $transaction,
        protected OneIdContract  $oneIdService,
        protected UserRepository $userRepository
    )
    {
    }

    /**
     * @param OneIdCodeRequest $oneIdCodeRequest
     * @return JwtTokenDto
     */
    public function loginByOneId(OneIdCodeRequest $oneIdCodeRequest): JwtTokenDto
    {
        $code = $oneIdCodeRequest->validated()['code'];

        $fetAccessTokenDto = $this->oneIdService->fetchAccessToken($code);

        $userDetailInfo = $this->oneIdService->fetchUserDetailInfo($fetAccessTokenDto->getAccessToken());

        if ($userDetailInfo->isEmptyStir()) {
            if ($userDetailInfo->isEmptyPinFl()) {
                abort(404, __('client.Pin Fl is not defined'));
            }
            $user = $this->userRepository->findByPinFl((int)$userDetailInfo->getPinFl());
        } else {
            $user = $this->userRepository->findByStir((int)$userDetailInfo->getStir());
        }

        if (empty($user)) {
            abort(403, __('client.Forbidden. The reason is that user is not found.'));
        }

        if (!$user->isActive()) {
            throw new UnauthorizedHttpException(__('client.Access login is denied. Dashboard PM'));
        }

        $user->setAuthType(AuthTypeEnum::_ONE_ID->value);
        $user->setEgovToken($fetAccessTokenDto->getAccessToken());
        $this->userRepository->save($user);

        try {
            DB::beginTransaction();

            if ($userDetailInfo->isEmptyStir()) {
                $token = Auth::guard()
                    ->attempt(['username' => $user->getUsername(), 'pin_fl' => $user->getPinFl()]);
            } else {
                $token = Auth::guard()
                    ->attempt(['username' => $user->getUsername(), 'stir' => $user->getStir()]);
            }


            if (empty($token) || is_bool($token)) {
                throw new UnauthorizedHttpException('', __('client.Unauthorized'));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \DomainException($e->getMessage());
        }

        return new JwtTokenDto((string)$token);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return JwtTokenDto
     */
    public function loginByUser(LoginRequest $loginRequest): JwtTokenDto
    {

        $postParams = $loginRequest->validated();

        /**
         * @var User|null $user
         */
        $user = $this->userRepository->findByUsername($postParams['username']);

        if (empty($user)) {
            abort(403, __('client.Forbidden. The user which is named not found'));
        }

        if (!$user->isCheckPassword($postParams['password'])) {
            abort(403, __('client.Forbidden. The reason is that password is not correct'));
        }

        if (!$user->isActive()) {
            throw new UnauthorizedHttpException(__('client.Access login is denied. Uzb'));
        }

        if (
            $user->isNotEmptyPassword()
            && !$user->isCheckPassword($postParams['password'])
        ) {
            throw new UnauthorizedHttpException(__('client.User password is empty or error'));
        }
        $user->setAuthType(AuthTypeEnum::_USERNAME->value);
        $this->userRepository->save($user);

        $token = Auth::guard()->attempt(['username' => $postParams['username'], 'password' => $postParams['password']]);

        return new JwtTokenDto((string)$token);
    }

    /**
     * @param Request $request
     * @return JwtTokenDto
     */
    public function refreshToken(Request $request): JwtTokenDto
    {
        $jwt = auth('api');

        if (!($jwt instanceof JWTGuard)) {
            throw new BadRequestHttpException(__("client.jwt doesn't implement from JWTGuard"));
        }

        try {
            $newToken = $jwt->refresh();
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('', $e->getMessage());
        }

        return new JwtTokenDto($newToken);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        if (!Auth::check()) {
            abort(401, __('client.Unauthorized'));
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        try {
            if ($user->isAuthTypeOneId()) {
                $this->oneIdService->logout($user->getEgovToken());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        $jwt = auth('api');

        if (!($jwt instanceof JWTGuard)) {
            abort(400, __("client.jwt doesn't implement from JWTGuard"));
        }

        $jwt->logout();

        $this->userRepository->save($user);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function parseAuthHeader(Request $request): string
    {
        $header = $request->header('Authorization');
        preg_match('/^' . self::SCHEMA . '\s+(.*?)$/', $header, $matches);
        return $matches[1];
    }
}
