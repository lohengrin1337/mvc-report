<?php

namespace App\Controller\Project;

use App\Form\ConfirmType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


/**
 * Controller for landingpage and about pages of project
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
        $this->data["pageTitle"] = "Projekt";
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



    #[Route("/proj/api", name: "proj_api", methods: ["GET"])]
    public function api(): Response
    {
        $this->data["pageTitle"] = "API";

        $resetForm = $this->createForm(ConfirmType::class);

        return $this->render("proj/api/api.html.twig", $this->data);
    }
}
