<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * controller with routes for regular pages
 */
class MainController extends AbstractController
{
    /**
     * @var array $data
     */
    public $data;



    /**
     * constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "pageTitle" => "",
        ];
    }



    #[Route('/', name: "home")]
    public function home()
    {
        $this->data["pageTitle"] = "Hem";

        return $this->render("home.html.twig", $this->data);
    }



    #[Route('/about', name: "about")]
    public function about()
    {
        $this->data["pageTitle"] = "Om MVC";

        return $this->render("about.html.twig", $this->data);
    }



    #[Route('/report', name: "report")]
    public function report()
    {
        $this->data["pageTitle"] = "Redovisning";

        return $this->render("report.html.twig", $this->data);
    }
}
