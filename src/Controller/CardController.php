<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Controller class with routes for card assignment
 */
class CardController extends AbstractController
{
    /**
     * @var array $data - template data
     */
    private $data;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "pageTitle" => "",
        ];
    }



    #[Route("/card", name: "card_start", methods: ["GET"])]
    public function start(): Response
    {
        $this->data["pageTitle"] = "Kortlek";

        return $this->render("card/start.html.twig", $this->data);
    }
}
