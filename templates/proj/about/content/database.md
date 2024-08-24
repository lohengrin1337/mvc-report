## Tabeller

### Player

Spelarens namn, typ (human|cpu) och svårighet (null|1-3) sparas i player-tabellen.

### Board

Data som representerar den färdiga spelplanen sparas i board-tabellen.

### Score

Poäng för de tio pokerhänderna, samt totalpoäng sparas i score-tabellen

### Round

Tider för start, slut och varaktighet, samt koppling till de tre andra tabellerna sparas i round-tabellen.

## Relationer

Relationen mellan *round* och *score*, samt *round* och *board* är en enkel *en-till-en-relation*.

Relationen mellan *round* och *player* är *många-till-en*, där en runda hör till en spelare, medan en spelare kan ha många rundor.

*Doctrine/ORM* har stöd för att lösa relationer mellan olika *Entities*, och här valde jag även att låta en spelares *round*, *score* och *board* raderas automatiskt när en spelare tas bort genom *cascade*.

## Val av databas

Jag har använt *MariaDB* i utvecklingsmiljön och på studentservern, men vid kloning av repot används istället *SQLite* för att underlätta för den som klonar.

## Enhetstester

Jag har använt mig av en *in memory SQLite databas* i enhetstester för några *repository-* och *serviceklasser*. I `.env.test` lade jag in `DATABASE_URL="sqlite:///:memory:"`, och i testerna använder jag Symfonys `KernelTestCase` och Doctrines `SchemaTool` för att spegla schema och lägga in data att testa mot.

Jag behövde inte göra något särskilt för att detta även skulle fungera på Scrutinizer.
