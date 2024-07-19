<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\Exceptionable;
use Exception;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    use Exceptionable;

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function login(array $data): array
    {
        try {
            $user = $this->userRepository->findBY('email', $data['email']);
            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw new Exception();
            }

            return [
                'status' => Response::HTTP_OK,
                'data' => [
                    'token' => $user->createToken($data['email'])->plainTextToken
                ],
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }
}
