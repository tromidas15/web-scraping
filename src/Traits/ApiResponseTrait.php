<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponseTrait
{
    public function successResponse(array $data): JsonResponse
    {
        return new JsonResponse(['data' => $data]);
    }

    public function serverErrorResponse(): JsonResponse
    {
        return new JsonResponse(['error' => 'Server error'], 500);
    }
}