<?php

namespace App\Core\Service\Jwt;

use App\Core\Service\Jwt\Contracts\JwtContract;
use DateTimeImmutable;
use Illuminate\Support\Facades\Log;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class JwtService implements JwtContract
{
    public function __construct(
        protected Configuration $configBuilder,
        protected array         $tokenExtraParams = []
    )
    {

    }

    /**
     * @param string $token
     * @return bool
     */
    public function validate(string $token): bool
    {
        try {
            $token = $this->configBuilder->parser()->parse($token);

            $constraints = [
                new SignedWith($this->configBuilder->signer(), $this->configBuilder->verificationKey()),
                new LooseValidAt(SystemClock::fromUTC())
            ];

            return $this->configBuilder->validator()->validate($token, ...$constraints);

        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $e) {
            Log::error($e);
            throw new \DomainException($e);
        }
    }

    /**
     * @return UnencryptedToken
     * @throws \DateMalformedStringException
     */
    public function generateToken(): UnencryptedToken
    {
        $now = new DateTimeImmutable();
        $signAlgo = $this->configBuilder->signer();
        $key = $this->configBuilder->signingKey();

        return $this->configBuilder->builder()
            ->issuedBy($this->tokenExtraParams['issuer'])
            ->permittedFor($this->tokenExtraParams['audience'])
            ->identifiedBy($this->tokenExtraParams['id'])
            ->issuedAt($now)
            ->expiresAt($now->modify('+' . $this->tokenExtraParams['access_token_expire_time'] . ' minute'))
            ->getToken($signAlgo, $key);
    }

    /**
     * @param string $token
     * @return UnencryptedToken
     * @throws \DateMalformedStringException
     */
    public function generateTokenViaUzbToken(string $token): UnencryptedToken
    {
        /**
         * @var Token\Plain $parseToken
         */
        $parseToken = $this->configBuilder->parser()->parse($token);
        $user_id = $parseToken->claims()->get('uid');

        $now = new DateTimeImmutable();
        $signAlgo = $this->configBuilder->signer();
        $key = $this->configBuilder->signingKey();

        return $this->configBuilder->builder()
            ->issuedBy($this->tokenExtraParams['issuer'])
            ->permittedFor($this->tokenExtraParams['audience'])
            ->identifiedBy($this->tokenExtraParams['id'])
            ->issuedAt($now)
            ->expiresAt($now->modify('+' . $this->tokenExtraParams['access_token_expire_time'] . ' minute'))
            ->withClaim('uid', $user_id)
            ->getToken($signAlgo, $key);
    }
}
