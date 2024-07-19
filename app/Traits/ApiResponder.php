<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponder
{
    public function success(array $data = [], string $message = '', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->response($statusCode, $data, $message);
    }

    public function failed(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, array $data = []): JsonResponse
    {
        return $this->response($statusCode, [], $message);
    }

    public function response(int $statusCode, array $data = [], string $message= ''): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $statusCode);
    }
}
