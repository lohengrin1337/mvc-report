## Introduktion

Denna artikeln är en dokumentation över processen att försöka förbättra min egen källkod för denna webbappen. Ugångspunkten är att titta på ett antal olika mätvärden och principer, som har något att säga om hur kvaliteten på koden ser ut.

*Code style* är riktlinjer eller konventioner för hur ett programmeringspråk bör skrivas. Det kan exempelvis röra sig om hur *whitespace* och indentationer används, hur variabler namnges, eller hur kommentarer skrivs. Syftet är att göra koden mer läsbar, och lättare att underhålla. Jag använder mig av verktyget *PHP CS Fixer* för att enkelt hålla koden i trim enligt kodstandarden.

*Coverage* är ett värde på hur stor del av koden som testas (kodtäckning). Enhetstester är bra för att upptäcka brister i koden i ett tidigt skede, och även efterhand när ändringar görs. Hög kodtäckning ger en trygghet för utvecklaren, men även för kunden. Kod som är lätt att testa är förknippad med hög kvalitet och brukar vara lättare att underhålla. I utgångsläget har jag en kodtäckning på 20.6% för all källkod, vilket är lågt om ambitionen är att testa så mycket som möjligt. För att sätta det i perspektiv har jag bara skrivit tester för min `Card`-klass ännu, vilken har 99.4% täckning.

*Complexity* är ett mått på hur komplex koden är. Det handlar om hur många logiska vägar det finns, vilket till exempel ökar med fler if-satser och loopar. Komplex kod är svårare att förstå, underhålla och testa. Mina *controller-klasser* har en förhållandevis hög komlexitet (över 10), medan de flesta *modell-klasserna* ligger lägre (under 10).

*Cohesion* är ett mått på hur väl sammankopplad en modul är för att lösa **en** väldefinierad uppgift. När en modul har hög *cohesion* är den troligen mer robust, pålitlig, återanvändbar och lättbegriplig jämfört med en modul med låg *cohesion*.

*Coupling* är ett mått på hur tätt sammankopplade olika kodmoduler är. Hög *coupling* betyder att modulerna är beroende av varrandra i hög grad, vilket kan innebära att det blir svårt att utveckla koden i en modul utan att det påverkar andra. Det kan också bli svårare att testa kod med hög *coupling*.

*CRAP* är ett mått på förhållandet mellan komplexitet och kodtäckning. Syftet är att belysa kod som kan vara svår att underhålla, förstå och testa. Komplex kod kan få ett okej CRAP-värde (under 30) om den har hög kodtäckning. Till exempel är min `CardGame21`-klass relativt komplex samtidigt som den har hög kodtäckning, vilket resulterar i låga CRAP-värden.


## Phpmetrics
Den tydligaste signalen jag får från phpmetrics-rapporten är att mina controllers har relativt hög komplexitet (kring 10 eller högre), samtidigt som de saknar kodtäckning. Även flera andra klasser saknar kodtäckning.

`ProductController` anses vara ett *god object* på grund av låg *cohesion* och många metoder. 

`LibraryController` har hög komplexitet (15), och metoden `LibraryController::resetLibrary` är den enskilt största inom klassen.

<a href="img/metrics/phpmetrics-coverage.png">
    <img
        class="img"
        src="img/metrics/phpmetrics-coverage.png"
        alt="Controllers and coverage"
    >
</a>


## Scrutinizer

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/lohengrin1337/mvc-report/build-status/main)

Även Scrutinizer-rapporten visar på låg kodtäckning generellt, och att controller-klasserna är komplexa.

Några metoder har fått varningar på grund av logiska luckor. Till exempel lämnar uttrycket `if (!$this->value)` i `DiceGraphic::getAsString` utrymme för fel om värdet på en tärning skulle vara `0`.

`LibraryController::resetLibrary()` och `CardApiController::apiDeckDeal()` har relativt hög komplexitet, och innehåller logik som troligen skulle passa bättre i en modelklass.


## Förbättringar

### Issues
Jag börjar med att korrigera *issues* för att koden ska följa *best practice* och för att undvika potentiella buggar.

- `DiceGraphic::getAsString()` (Bug, Best practice)
- `DiceHand::getSum()` (Bug, Best practice)
- `CardDeckTest::testCreateCardDeckInvalid()` (Unused code)
- `CardDeckTest::cardStub` (Documentaion, Bug)
- `CardTest::testCreateCardInvalidSuit()` (Unused code)
- `CardTest::testCreateCardInvalidRanks()` (Unused code)


### Kodtäckning
Klasserna i namespace `App\Dice` saknar tester, och det känns som en rimlig åtgärd att säkra upp den koden.
Jag skulle kunna försöka ge mig på att testa controllerklasserna, samt en nyskapad `App\Service\ResetLibraryService`, men jag väljer att inte ge mig på det.


### Komplexitet
Jag gör en *refactoring* av koden i `LibraryController::resetLibrary()` genom att flytta logik till en ny klass `ResetLibraryService`. På så sätt kan jag få ner komplexiteten i controller-klassen, och följer i högre grad *single responsisibility principle*, vilket ger högre *cohesion*.


### Resultat
Alla issues i scrutinizer är åtgärdade, med undantag för en som jag anser är irrelevant.

Kodtäckningen har ökat från 21% till 25%. Jag är nöjd med det eftersom jag testat 99% av den kod jag vill testa.


Komplexiteten för `LibraryController` har sjunkit från 24 till 22 enligt scrutinizer, och från 15 till 13 enligt phpmetrics.
Den nya klassen `ResetLibraryService` får ett högt CRAP-score eftersom den inte har någon kodtäckning. Detta gör att klassen får betyg B av scrutinizer, vilket sänker helhetsbetyget från 10 till 9.98. Jag anser att koden är bättre ändå, och jag kan/vill inte testa den nya klassen.


## Diskussion
PHP metrics och scrutinizer kan vara bra verktyg för att förbättra sin kodkvalitet. Genom badges kan man kan man även ge en signal till användaren vad den kan förvänta sig. Om man regelbundet jobbar med att städa upp i sin kod blir det lättare att förebygga buggar, och se till att koden går att underhålla på sikt. Det främsta nackdelen är att det tar tid för stunden, och kan vara lite frustrerande. Ibland kan resultaten även vara lite missvisande.