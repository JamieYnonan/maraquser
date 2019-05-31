<?php

namespace Mu\Infrastructure\Api\Custom\Exception;

use Mu\Infrastructure\Exception\InfrastructureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

final class ExceptionHandler
{
    public static function register(): void
    {
        $response = new JsonResponse();
        $response->setStatusCode(500);
        set_exception_handler(function (Throwable $e) use ($response) {
            if ($e instanceof InfrastructureException) {
                $data = ['message' => $e->getMessage()];
            } else {
                $data = ['message' => 'An unexpected error occurred.'];
            }

            $response->setData($data);

            return $response->send();

        });
    }

    private function __construct()
    {
    }
}
