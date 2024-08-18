<?php

namespace App\Controller\Report;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controller with routes for regular pages
 */
class MainController extends AbstractController
{
    /**
     * @var array<string,mixed> $data
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
    private function updateLuckyNum(): void
    {
        session_name("oljn22");
        session_start();

        $luckyNum = $_SESSION["lucky_num"] ?? null;
        if (!$luckyNum) {
            $luckyNum = random_int(0, 4);
        }

        // loop numbers 0-4
        $luckyNum = ($luckyNum + 1) % 5;

        $_SESSION["lucky_num"] = $luckyNum;
        $this->data["luckyNum"] = $luckyNum;
    }



    /**
     * get credits for current lucky image from relevant folder,
     * and update data["luckyCredits"]
     *
     * @return void
     */
    private function updateLuckyCredits(): void
    {
        /** @var int $num */
        $num = $this->data["luckyNum"];
        $filePath = "img/lucky/{$num}/credits.txt";
        $fileContent = "";

        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
        }

        $this->data["luckyCredits"] = $fileContent;
    }




    #[Route('/', name: "home")]
    public function home(): Response
    {
        $this->data["pageTitle"] = "Hem";

        return $this->render("main/home.html.twig", $this->data);
    }



    #[Route('/about', name: "about")]
    public function about(): Response
    {
        $this->data["pageTitle"] = "Om";

        return $this->render("main/about.html.twig", $this->data);
    }



    #[Route('/report', name: "report")]
    public function report(): Response
    {
        $this->data["pageTitle"] = "Redovisning";

        return $this->render("main/report.html.twig", $this->data);
    }



    #[Route('/lucky', name: "lucky")]
    public function lucky(): Response
    {
        $this->data["pageTitle"] = "Lyckobild";

        // update luckyNum and luckyCredits to render an img with credits
        $this->updateLuckyNum();
        $this->updateLuckyCredits();

        return $this->render("main/lucky.html.twig", $this->data);
    }



    #[Route('/api', name: "api")]
    public function api(): Response
    {
        $this->data["pageTitle"] = "API";

        return $this->render("main/api.html.twig", $this->data);
    }



    #[Route('/session', name: "session", methods: ["GET"])]
    public function session(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Session";

        // get all session data
        $this->data["session"] = $session->all();

        return $this->render("main/session.html.twig", $this->data);
    }



    #[Route('/session/delete', name: "session_delete", methods: ["POST"])]
    public function sessionDelete(
        Request $request,
        SessionInterface $session
    ): Response {
        // Verify session delete form is submitted
        $sessionDelete = $request->request->get("session_delete") === "true" ?: false;
        if (!$sessionDelete) {
            $this->addFlash(
                'warning',
                'Ett okänt fel inträffade när sessionen skulle raderas!'
            );

            return $this->redirectToRoute("session");
        }

        // destroy session and set data["session"] to null
        $session->invalidate();
        $this->data["session"] = null;

        $this->addFlash(
            'notice',
            'Sessionen har raderats!'
        );

        return $this->redirectToRoute("session");
    }



    #[Route('/metrics', name: "metrics")]
    public function metrics(): Response
    {
        $this->data["pageTitle"] = "Metrics analys";

        return $this->render("main/metrics.html.twig", $this->data);
    }
}
