## Pseudokod

```
INIT Game

WHILE Player doesnt stop
    Player draws 1 card from deck
    SHOW card
    SHOW SUM of hand
    SHOW deck count
    IF SUM > 21 THEN
        Bank wins
    END IF
END WHILE

WHILE Bank doesnt stop
    Bank draws 1 card from deck
    SHOW card
    SHOW SUM of hand
    SHOW deck count
    IF SUM > 21 THEN
        Player wins
    END IF
END WHILE

IF Bank SUM >= Player SUM THEN
    Bank wins
ELSE
    Player wins
END IF
```