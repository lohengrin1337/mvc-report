<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]   // score of the 10 hands
    private array $hands = [];

    #[ORM\Column]                       // total score
    private int $total;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hands = [
            "row1" => 0,
            "row2" => 0,
            "row3" => 0,
            "row4" => 0,
            "row5" => 0,
            "col1" => 0,
            "col2" => 0,
            "col3" => 0,
            "col4" => 0,
            "col5" => 0,
        ];

        $this->total = 0;
    }



    /**
     * Get score for each hand
     *
     * @return array
     */
    public function getHands(): array
    {
        return $this->hands;
    }


    /**
     * Get total score
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }



    /**
     * Set score for a single hand
     *
     * @param string $handName
     * @param int $points
     * @return void
     */
    public function setHandScore(string $handName, int $points): void
    {
        if (!array_key_exists($handName, $this->hands)) {
            throw new InvalidArgumentException("Invalid hand name!");
        }

        $this->hands[$handName] = $points;
        $this->updateTotal();
    }



    /**
     * Update total score
     *
     * @return void
     */
    private function updateTotal(): void
    {
        // $total = (int) array_sum($this->hands);
        // $this->setTotal($total);

        $this->total = (int) array_sum($this->hands);
    }



    public function getId(): ?int
    {
        return $this->id;
    }
}
