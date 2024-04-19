Här kan du testa min kortleksmodell genom att klicka på länkarna till höger.

Modellen består av några olika klasser - `Card`, `CardGraphic`, `CardHand` och `CardDeck`, samt ett interface - `CardInterface`.

En kortlek `CardDeck` kan hålla kort som implementerar `CardInterface`. Kortleken kan dra, blanda, sortera, räkna antal, och visa en representation av korten. Vid representationen `getAsString()` anropas samma metod hos varje kort, men den är unik för respektive kortklass, vilket ger oilka grafisk representation.

`Card`: [♥5]

`Cardgrahic`: <span style="font-size: 50px;">{{ '&#x1f0b5;' }}</span>


Korthanden `CardHand` kan dra kort från leken, och spara dem. Den kan även räkna antal kort, och visa en representation av korten.