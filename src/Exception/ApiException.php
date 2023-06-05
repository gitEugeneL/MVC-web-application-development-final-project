<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiException
{
    public function exception(string $message, int $code): void
    {
        $exception = new JsonResponse($message, $code);
        $exception->send();
    }
}