<?php

namespace App\Controller\Project;

use App\Form\ConfirmType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for landingpages and text pages of poker squares project
 */
class MainController extends AbstractController
{
    /**
     * @var array<mixed> $data - template data
     */
    private array $data;

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



    #[Route("/proj", name: "proj_start", methods: ["GET"])]
    public function index(): Response
    {
        $this->data["pageTitle"] = "Poker Squares";
        return $this->render("proj/index.html.twig", $this->data);
    }



    #[Route("/proj/about", name: "proj_about", methods: ["GET"])]
    public function about(): Response
    {
        $this->data["pageTitle"] = "Om Projektet";
        return $this->render("proj/about/index.html.twig", $this->data);
    }



    #[Route("/proj/about/database", name: "proj_database", methods: ["GET"])]
    public function database(): Response
    {
        $this->data["pageTitle"] = "Databasen";
        return $this->render("proj/about/database.html.twig", $this->data);
    }



    #[Route("/proj/game", name: "proj_game_start", methods: ["GET"])]
    public function gameStart(): Response
    {
        $this->data["pageTitle"] = "Poker Squares";
        return $this->render("proj/game/index.html.twig", $this->data);
    }



    #[Route("/proj/game/rules", name: "proj_rules", methods: ["GET"])]
    public function gameRules(): Response
    {
        $this->data["pageTitle"] = "Regler";
        return $this->render("proj/game/rules.html.twig", $this->data);
    }



    #[Route("/proj/api", name: "proj_api", methods: ["GET"])]
    public function api(): Response
    {
        $this->data["pageTitle"] = "API";

        // create post form (button) to show current game state
        $this->data["gameForm"] = $this->createForm(
            ConfirmType::class,
            null,
            [
                'action' => $this->generateUrl('proj_api_game'),
                'method' => 'POST',
                "label" => "Visa pågående spel",
                "auth" => "p@ssw0rd",
            ]
        );

        // create post form (button) to reset database
        $this->data["resetForm"] = $this->createForm(
            ConfirmType::class,
            null,
            [
                'action' => $this->generateUrl('proj_api_reset'),
                'method' => 'POST',
                "label" => "Återställ databasen",
                "btn_attr" => ["class" => "button btn-delete margin-b"],
                "auth" => "p@ssw0rd",
            ]
        );

        return $this->render("proj/api/api.html.twig", $this->data);
    }
}
