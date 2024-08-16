<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardRepository::class)]
class Board
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var array<string|null> $data */
    #[ORM\Column(type: Types::ARRAY)]
    private array $data = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return array<string|null> */
    public function getData(): array
    {
        return $this->data;
    }

    /** @param array<string|null> $data */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
