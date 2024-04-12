<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
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
            "luckyNum" => null,
            "luckyCredits" => ""
        ];
    }



    /**
     * Manipulate old session value, and set data["luckyNum"]
     *
     * @return void
     */
    private function updateLuckyNum()
    {
        session_name("oljn22");
        session_start();

        if (!isset($_SESSION["lucky_num"])) {
            // set random int 0-4
            $_SESSION["lucky_num"] = random_int(0, 4);
        } else {
            // continue loop 0-4
            $_SESSION["lucky_num"] = ($_SESSION["lucky_num"] + 1) % 5;
        }

        $this->data["luckyNum"] = $_SESSION["lucky_num"];
    }



    /**
     * get credits for current lucky image from relevant folder,
     * and update data["luckyCredits"]
     *
     * @return void
     */
    private function updateLuckyCredits()
    {
        $num = $this->data["luckyNum"];
        $filePath = "img/lucky/{$num}/credits.txt";
        $fileContent = "";

        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
        }

        $this->data["luckyCredits"] = $fileContent;
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
        $this->data["pageTitle"] = "Om";

        return $this->render("about.html.twig", $this->data);
    }



    #[Route('/report', name: "report")]
    public function report()
    {
        $this->data["pageTitle"] = "Redovisning";

        return $this->render("report.html.twig", $this->data);
    }



    #[Route('/lucky', name: "lucky")]
    public function lucky()
    {
        $this->data["pageTitle"] = "Lyckobild";

        // update luckyNum and luckyCredits to render an img with credits
        $this->updateLuckyNum();
        $this->updateLuckyCredits();

        return $this->render("lucky.html.twig", $this->data);
    }



    #[Route('/api', name: "api")]
    public function api()
    {
        $this->data["pageTitle"] = "API";

        return $this->render("api.html.twig", $this->data);
    }
}
