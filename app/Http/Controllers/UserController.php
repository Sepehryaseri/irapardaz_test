<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiResponder;

    public function __construct(protected UserService $userService)
    {
    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();
        $response = $this->userService->login($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed(message: $response['message'], statusCode: $response['status']);
        }
        return $this->success(data: $response['data']);
    }
}
