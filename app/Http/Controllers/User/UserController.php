<?php

namespace App\Http\Controllers\User;

use App\Core\Filter\User\UserFilter;
use App\Core\Helpers\Responses\Response;
use App\Core\Repository\User\UserRepository;
use App\Core\Service\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private UserService             $userService
    )
    {
    }

    public function list(): JsonResponse
    {
        return Response::success('All users', $this->userRepository->findAll()->toArray() ?? []);
    }

    public function filter(UserFilter $filter): JsonResponse
    {
        return response()->json($filter->apply()->paginate());
    }

    public function create(UserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request);

        return Response::success('User created', (array)$user->toArray());
    }

    public function update(UserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->update($request, $id);

        return Response::success('User updated', (array)$user->toArray());
    }

    public function delete(int $id): JsonResponse
    {
        return Response::success('User deleted', (array)$this->userService->delete($id)->toArray());
    }

    public function get(int $id): JsonResponse
    {
        return Response::success('User', (array)$this->userRepository->getById($id)->toArray());
    }

    public function ban(int $id): JsonResponse
    {
        $user = $this->userService->ban($id);

        return Response::success('User banned', (array)$user->toArray());
    }

    public function unban(int $id): JsonResponse
    {
        $user = $this->userService->unban($id);

        return Response::success('User unbanned', (array)$user);
    }
}
