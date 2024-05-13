<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LibraryController extends AbstractController
{
    /**
     * @var array<string,mixed> $data - template data
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



    /**
     * Set flash and redirect to all books
     */
    private function handleMissingBook(int $id): Response
    {
        $this->addFlash(
            "warning",
            "Ingen bok med id $id hittades!"
        );
        return $this->redirectToRoute('all_books');
    }



    #[Route('/library', name: 'library_start', methods: ["GET"])]
    public function index(): Response
    {
        $this->data["pageTitle"] = "Bibliotek";

        return $this->render('library/index.html.twig', $this->data);
    }



    #[Route('/library/create', name: 'create_book_view', methods: ["GET"])]
    public function createBookView(): Response
    {
        $this->data["pageTitle"] = "Lägg till bok";

        return $this->render('library/create_book.html.twig', $this->data);
    }



    #[Route('/library/create', name: 'create_book', methods: ["POST"])]
    public function createBook(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $request->request->all();

        $book = new Book();
        $book->setTitle($form["title"]);
        $book->setAuthor($form["author"]);
        $book->setIsbn($form["isbn"]);
        $book->setImage($form["image"]);

        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash(
            "notice",
            "En ny bok har lagts till!"
        );

        return $this->redirectToRoute("all_books");
    }



    #[Route('/library/books', name: 'all_books', methods: ["GET"])]
    public function allBooks(BookRepository $bookRepository): Response
    {
        $this->data["pageTitle"] = "Böcker";

        $books = $bookRepository->findAll();

        $this->data["books"] = $books;

        return $this->render('library/all_books.html.twig', $this->data);
    }



    #[Route('/library/books/{id}', name: 'single_book', methods: ["GET"])]
    public function singleBook(
        int $id,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->handleMissingBook($id);
        }

        $this->data["pageTitle"] = "Bok med id $id";
        $this->data["book"] = $book;

        return $this->render('library/single_book.html.twig', $this->data);
    }



    #[Route('/library/edit/{id}', name: 'edit_book_view', methods: ["GET"])]
    public function editBookView(
        int $id,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->handleMissingBook($id);
        }

        $this->data["pageTitle"] = "Redigera bok med id $id";
        $this->data["book"] = $book;

        return $this->render('library/edit_book.html.twig', $this->data);
    }



    #[Route('/library/edit', name: 'edit_book', methods: ["POST"])]
    public function editBook(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $request->request->all();

        $id = $form["id"] ?? null;
        if (!$id) {
            $this->addFlash(
                "warning",
                "Formuläret för 'redigera bok' var inte giltigt!"
            );
            return $this->redirectToRoute('all_books');
        }
        $book = $entityManager->getRepository(Book::class)->find($id);
        if (!$book) {
            return $this->handleMissingBook($id);
        }

        $book->setTitle($form["title"]);
        $book->setAuthor($form["author"]);
        $book->setIsbn($form["isbn"]);
        $book->setImage($form["image"]);

        $entityManager->flush();

        $this->addFlash(
            "notice",
            "Boken har uppdaterats!"
        );

        return $this->redirectToRoute('single_book', ['id' => $id]);
    }




    #[Route('/library/delete/{id}', name: 'delete_book_view', methods: ["GET"])]
    public function deleteBookView(
        int $id,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository->find($id);
        if (!$book) {
            return $this->handleMissingBook($id);
        }

        $this->data["pageTitle"] = "Radera bok";
        $this->data["book"] = $book;

        return $this->render('library/delete_book.html.twig', $this->data);
    }



    #[Route('/library/delete', name: 'delete_book', methods: ["POST"])]
    public function deleteBook(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $id = $request->request->get("id") ?? null;
        if (!$id) {
            $this->addFlash(
                "warning",
                "Formuläret för 'radera bok' var inte giltigt!"
            );
            return $this->redirectToRoute('all_books');
        }

        $book = $entityManager->getRepository(Book::class)->find((int) $id);
        if (!$book) {
            return $this->handleMissingBook((int) $id);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash(
            "notice",
            "{$book->getTitle()} har raderats!"
        );

        return $this->redirectToRoute('all_books');
    }



    #[Route('/library/reset', name: 'library_reset_view', methods: ["GET"])]
    public function resetLibraryView(): Response
    {
        $this->data["pageTitle"] = "Återställ bibliotek";

        return $this->render('library/library_reset.html.twig', $this->data);
    }



    #[Route('/library/reset', name: 'library_reset', methods: ["POST"])]
    public function resetLibrary(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $reset = $request->request->get("reset") ?? null;
        if ($reset !== "true") {
            $this->addFlash(
                "warning",
                "Formuläret för 'återställ bibliotek' var inte giltigt!"
            );
            return $this->redirectToRoute('all_books');
        }

        // Remove all existing books from the database
        $books = $entityManager->getRepository(Book::class)->findAll();
        foreach ($books as $book) {
            $entityManager->remove($book);
        }
        $entityManager->flush();

        // Add default books to the library
        $jsonData = file_get_contents(__DIR__ . '/../../data/default-library.json');
        if (!$jsonData) {
            $this->addFlash(
                "warning",
                "Återställning misslyckades eftersom 'default-library.json' saknas!"
            );
            return $this->redirectToRoute('all_books');
        }

        $defaultBooks = json_decode($jsonData, true);
        foreach ($defaultBooks as $b) {
            $book = new Book();
            $book->setTitle($b["title"]);
            $book->setAuthor($b["author"]);
            $book->setIsbn($b["isbn"]);
            $book->setImage($b["image"]);
            $entityManager->persist($book);
        }
        $entityManager->flush();

        $this->addFlash(
            "notice",
            "Biblioteket har återställts!"
        );
        return $this->redirectToRoute('all_books');
    }
}
