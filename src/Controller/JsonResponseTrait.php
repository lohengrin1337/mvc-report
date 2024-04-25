<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

trait JsonResponseTrait
{
    /**
     * @var JsonResponse $response
     */
    private JsonResponse $response;



    /**
     * Set new JsonResponse with data
     * Update $this->response
     *
     * @param array<string,mixed> $data - data to put in the JsonResponse
     */
    private function setResponse(array $data): void
    {
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        $this->response = $response;
    }
}