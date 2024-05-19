<?php

namespace App\Service;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

class ResetLibraryService
{
    /**
     * @var string PATH_TO_DATA
     */
    public const PATH_TO_DATA = __DIR__ . "/../../data/";

    /**
     * @var string DEFAULT_JSON_FILE
     */
    public const DEFAULT_JSON_FILE = "default-library.json";

    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructor
     * Add entitymanager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    /**
     * Reset library (book table) to only contain titles from json-file
     *
     * @param string $fileName - the name of a json file
     * @return bool
     */
    public function resetLibrary($fileName = self::DEFAULT_JSON_FILE): bool
    {
        // Verify the file
        $filePath = self::PATH_TO_DATA . $fileName;
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return false;
        }

        // Remove all existing books from the book table
        $books = $this->entityManager->getRepository(Book::class)->findAll();
        foreach ($books as $book) {
            $this->entityManager->remove($book);
        }
        $this->entityManager->flush();

        // Get json and verify not false
        $jsonData = file_get_contents($filePath);
        if (!$jsonData) {
            return false;
        }

        // Add default books to the library
        $defaultBooks = json_decode($jsonData, true);
        foreach ($defaultBooks as $b) {
            $book = new Book();
            $book->setTitle($b["title"]);
            $book->setAuthor($b["author"]);
            $book->setIsbn($b["isbn"]);
            $book->setImage($b["image"]);
            $this->entityManager->persist($book);
        }
        $this->entityManager->flush();

        return true;
    }
}
