<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController
{
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            "<html>
                <body>
                    Lucky number: {$number}
                </body>
            </html>"
        );
    }


    #[Route("/lucky/hi")]
    public function hi(): Response
    {
        $msg = "Hi there!";

        return new Response(
            "<html><body>"
            . $msg
            . "</body></html>"
        );
    }
}
