<?php

namespace App\Exception;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiException
{
    #[NoReturn] public function exception(string $message, int $code): void
    {
        $response = new JsonResponse(["detail" => $message], $code,
            ['Access-Control-Allow-Origin' => '*']
        );
        $response->send();
        exit();
    }
}