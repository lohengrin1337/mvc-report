Arv mellan två klasser handlar om att en klass är en specialicering av en annan klass. I php stavas det `class ChildClass extends ParentClass {}`. Attribut och metoder som är `public` eller `protected` i *superklassen* finns tillgängliga i *subklassen*, men attribut och metoder som definieras i subklassen finns inte i superklassen. Man kan säga att `ChildClass` *är en* `ParentClass`.

Komposition är när en klass *har en* annan klass, till exempel kanske ett objekt hålls av ett attribut i en annan klass. Det kan vara fråga *aggregat* komposition, som innebär att ett object både kan *ägas* av ett objekt, men också existera på egen hand.

Ett interface är en konstruktion som likar en klassdefinition `interface CardInterface {}`, men *bodyn* innehåller bara en specifikation på ett antal (publika) metoder. När en klass implemeterat ett interface `class Card implements CardInterface {}` betyder det att klassen måste implemetera de metoder som interfacet har specificerat. Hur implementationen går till är upp till klassen att lösa.

Ett trait `trait SomethingTrait {}` fungerar som en pusselbit för klasser, och kan användas i en klassdefinition `class Card {use SomethingTrait;}` för att återanvända en bit kod i flera olika klasser på ett sätt som påminner om arv. En skillnad är att flera olika trait kan användas i samma klassdefinition.

Jag löste *card-uppgiften* genom att bygga ett antal klasser för kort, hand och kortlek, ett kort-interface, en controller med routes för `/card`, en annan controller för `/api`, samt ett antal template-filer för att rendera de olika sidorna. Det tog lite tid att genomföra, men jag är nöjd med resultatet. Se [card]({{ path('card_start') }}) för detaljer kring klasserna.

Jag gillar att arbeta med *Symfony* och *PHP*. Det känns mer lättarbetat än *Express* och *Javascript* till exempel.

Veckans TIL är hur smart det är med Interface. Det verkar vara något jag kommer ha stor nytta av framöver.