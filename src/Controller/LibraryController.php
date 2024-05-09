<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function createBook(EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        // $book->setIsbn();
        // $book->setTitle();
        // $book->setAuthor();
        // $book->setImage();

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute("all_books");
    }


    // TESTROUTE FOR CREATE BOOK
    #[Route('/library/create/test', name: 'create_book_test', methods: ["GET"])]
    public function createBookTest(EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $book->setIsbn("789-789-789-789");
        $book->setTitle("En tredje boktitel");
        $book->setAuthor("Förnamn Efternamn");
        $book->setImage("En-bild-url");

        $entityManager->persist($book);
        $entityManager->flush();

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
    ): Response
    {
        $this->data["pageTitle"] = "Bok med id $id";

        $book = $bookRepository->find($id);

        $this->data["book"] = $book;

        return $this->render('library/single_book.html.twig', $this->data);
    }



    #[Route('/library/edit/{id}', name: 'edit_book_view', methods: ["GET"])]
    public function editBookView(): Response
    {
        $this->data["pageTitle"] = "Redigera bok med id $id";

        return $this->render('library/edit_book.html.twig', $this->data);
    }



    #[Route('/library/edit', name: 'edit_book', methods: ["POST"])]
    public function editBook(): Response
    {

        return $this->redirectToRoute('single_book', ['id' => $id]);
    }



    
    #[Route('/library/delete/{id}', name: 'delete_book_view', methods: ["GET"])]
    public function deleteBookView(): Response
    {
        $this->data["pageTitle"] = "Radera bok med id $id";

        return $this->render('library/delete_book.html.twig', $this->data);
    }



    #[Route('/library/delete', name: 'delete_book', methods: ["POST"])]
    public function deleteBook(): Response
    {

        return $this->redirectToRoute('all_books');
    }
}
