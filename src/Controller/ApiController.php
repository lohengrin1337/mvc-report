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
    use JsonResponseTrait;

    /**
     * @var string[]
     */
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
     * route shows a random quote
     */
    #[Route("api/quote", name: "quote")]
    public function quote(): JsonResponse
    {
        $this->updateQuote();
        $this->updateTime();
        $this->setResponse($this->data);

        return $this->response;
    }
}
