<?php

namespace App\Exception;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiException
{
    #[NoReturn] public function exception(string $message, int $code): void
    {
        $exception = new JsonResponse($message, $code);
        $exception->send();
        exit;
    }
}