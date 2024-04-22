<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controller class for api routes
 */
class ApiController extends AbstractController
{
    private const QUOTES = [
        "&quot;The trouble with having an open mind, of course,"
        . "is that people will insist on coming along and trying to put things in it.&quot;"
        . " - Terry Pratchett",

        "&quot;I intend to live forever. So far, so good.&quot; - Steven Wright",

        "&quot;I am free of all prejudice. I hate everyone equally.&quot; - W.C. Fields",

        "&quot;I'm not afraid of death; I just don't want to be there when it happens.&quot;"
        . " - Woody Allen",

        "&quot;I'm an idealist. I don't know where I'm going, but I'm on my way.&quot;"
        . " - Carl Sandburg"
    ];

    private array $data;

    private JSONResponse $response;



    // /**
    //  * constructor
    //  */
    // public function __construct()
    // {
    //     $this->data = [
    //         "quote" => "",
    //         "date" => null,
    //         "timestamp" => null
    //     ];
    // }




    /**
     * get random quote from array of quotes,
     * and update data["quote"]
     *
     * @return void
     */
    private function updateQuote(): void
    {
        $index = random_int(0, 4);
        $quote = self::QUOTES[$index];

        $this->data["quote"] = $quote;
    }




    /**
     * get current timestamp and date,
     * and update data["date"] and data["timestamp"]
     *
     * @return void
     */
    private function updateTime(): void
    {
        $timestamp = time();
        $date = date("l jS \of F Y", $timestamp);

        $this->data["date"] = $date;
        $this->data["timestamp"] = $timestamp;
    }




    /**
     * set new JSONResponse with current data,
     * and update $this->response
     *
     * @return void
     */
    private function setResponse(): void
    {
        $response = new JsonResponse($this->data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        $this->response = $response;
    }



    /**
     * route shows a random quote
     */
    #[Route("api/quote", name: "quote")]
    public function quote(): JsonResponse
    {
        $this->updateQuote();
        $this->updateTime();
        $this->setResponse();

        return $this->response;
    }
}
