<?php

namespace App\Card;

// use InvalidArgumentException as InvalidArgEx;

/**
 * Class for card game 21
 */
class CardGame21
{
    /**
     * @var int MAX_SUM - The highest allowed sum for a hand
     * @var int BANK_STRATEGY_NUM - The sum where the bank stops to draw
     */
    private const MAX_SUM = 21;
    private const BANK_STRATEGY_NUM = 17;



    /**
     * @var CardDeck $deck - a deck of cards
     * @var CardHand $player - a cardhand for player
     * @var CardHand $bank - a cardhand for bank
     * @var bool $gameOver - default is false
     * @var ?string $winner - default is null
     */
    private CardDeck $deck;
    private CardHand $player;
    private CardHand $bank;
    private bool $gameOver;
    private ?string $winner;



    /**
     * Constructor
     * Add deck, player hand and bank hand
     * Set $gameOver to false
     */
    public function __construct(CardDeck $deck, CardHand $player, CardHand $bank)
    {
        $this->deck = $deck;
        $this->player = $player;
        $this->bank = $bank;
        $this->gameOver = false;
        $this->winner = null;
    }



    /**
     * Get current state of the game
     * @return array<string,mixed> - a representation of the current state
     */
    public function getState(): array
    {
        return [
            "cardBack" => $this->deck->getCardBack(),
            "deckCount" => $this->deck->getCount(),
            "playerHand" => $this->player->getAsString(),
            "playerSum" => $this->player->rankSum(),
            "bankHand" => $this->bank->getAsString(),
            "bankSum" => $this->bank->rankSum(),
            "gameOver" => $this->gameOver,
            "winner" => $this->winner
        ];
    }



    /**
     * Player draws a card
     * If player exceeds MAX_SUM, bank wins
     */
    public function draw(): void
    {
        $this->player->draw($this->deck);
        $this->checkSum();
    }




    /**
     * Bank plays until full or done
     */
    public function playBank(): void
    {
        while ($this->bank->rankSum() < self::BANK_STRATEGY_NUM) {
            $this->bank->draw($this->deck);

            // If bank exceeds MAX_SUM, player wins
            if (!$this->checkSum()) {
                return;
            }
        }

        $this->endGame();
    }



    /**
     * Check if sum of each hand for player and bank are valid
     * If not - set gameOver and winner
     *
     * @return bool - true if both are valid, else false
    */
    private function checkSum(): bool
    {
        if ($this->player->rankSum() > self::MAX_SUM) {
            $this->gameOver = true;
            $this->winner = "bank";
            return false;
        }

        if ($this->bank->rankSum() > self::MAX_SUM) {
            $this->gameOver = true;
            $this->winner = "spelare";
            return false;
        }

        return true;
    }



    /**
     * End game
     */
    private function endGame(): void
    {
        $this->gameOver = true;
        $this->determineWinner();
    }



    /**
     * Determine winner from best hand
     */
    private function determineWinner(): void
    {
        $this->winner = "bank";
        if ($this->player->rankSum() > $this->bank->rankSum()) {
            $this->winner = "spelare";
        }
    }
}
