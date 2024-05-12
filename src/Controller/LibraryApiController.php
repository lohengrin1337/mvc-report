<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controller class for api routes of library
 */
class LibraryApiController extends AbstractController
{
    use JsonResponseTrait;

    #[Route("api/library/books", name: "api_all_books", methods: ["GET"])]
    public function apiAllBooks(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();
        $bookArray = [];
        foreach ($books as $book) {
            $bookArray[] = [
                "title" => $book->getTitle(),
                "author" => $book->getAuthor(),
                "isbn" => $book->getIsbn(),
                "image" => $book->getImage(),
            ];
        }

        $this->setResponse($bookArray);
        return $this->response;
    }



    #[Route("api/library/book/{isbn}", name: "api_single_book", methods: ["GET"])]
    public function apiSingleBook(
        int $isbn,
        BookRepository $bookRepository
    ): JsonResponse
    {
        $book = $bookRepository->getByIsbn($isbn);
        if (!$book) {
            $this->setResponse([]);
            return $this->response;
        }

        $book = [
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "isbn" => $book->getIsbn(),
            "image" => $book->getImage(),
        ];

        $this->setResponse($book);
        return $this->response;
    }
}
