<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
        // #[Route("api/lucky/number")]
    // public function jsonNumber(): Response
    // {
    //     $number = random_int(0, 100);

    //     $data = [
    //         "lucky-number" => $number,
    //         "lucky-message" => "Hi there!"
    //     ];

    //     $response = new Response();
    //     $response->setContent(json_encode($data));
    //     $response->headers->set("Content-Type", "application/json");

    //     return $response;
    // }


    // #[Route("api/lucky/number")]
    // public function jsonNumber(): JsonResponse
    // {
    //     $number = random_int(0, 100);

    //     $data = [
    //         "lucky-number" => $number,
    //         "lucky-message" => "Hi there!"
    //     ];

    //     return new JsonResponse($data);
    // }

    
    #[Route("api/lucky/number")]
    public function jsonNumber(): JsonResponse
    {
        $number = random_int(0, 100);

        $data = [
            "lucky-number" => $number,
            "lucky-message" => "Hi there!"
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
