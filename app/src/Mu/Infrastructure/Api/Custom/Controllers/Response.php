<?php

namespace Mu\Infrastructure\Api\Custom\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

trait Response
{
    protected function responseCreated(array $data): JsonResponse
    {
        return $this->response((object)$data, 201);
    }

    protected function response($data, int $code = 200): JsonResponse
    {
        return new JsonResponse(['message' => 'Ok.', 'data' => $data], $code);
    }

    protected function responseCollection(array $data = []): JsonResponse
    {
        return $this->response($data);
    }

    protected function responseSingle(array $data = []): JsonResponse
    {
        return $this->response((object)$data);
    }

    protected function responseEmpty(): JsonResponse
    {
        return new JsonResponse(null, 204);
    }
}
