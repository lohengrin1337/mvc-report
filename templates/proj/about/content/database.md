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

## ORM

Jag blev förvånad över hur smidigt det fungerade att jobba med ORM för att lagra php-object i en databas. Jag minns från databas-kursen att det var svårt med relations-tänket till en början, och att det krävdes en hel del tid för att komma in i det. När man väl hade vant sig vid att översätta data och dess relationer till relationsmodellen, kändes det ganska bekvämt och kraftfullt. Jag föreställde mig därför att det skulle bli klurigt att få till relationerna på rätt sätt med ORM, men det visade sig vara ganska enkelt, och krävde bara några rader för varje Entitet. Även databasfrågorna var smidiga att formulera i *repository-klasserna*, med hjälp av *Doctrines* inbyggda lösningar. Jag behövde aldrig skriva någon ren SQL, även om jag hade nytta av mina SQL-kunskaper.

Spontant ser jag nog fler fördelar än nackdelar med ORM jämfört med att själv modellera och implementera en databas. Det är enklare, mer flexibelt med att kunna skifta databashanterare, och det kräver inte lika mycket kunskap om databaser. Nackdelar skulle kunna vara att man tappar lite kontroll över den faktiska strukturen i databasen, vilket skulle kunna leda till sämre prestanda, säkerhet och flexibilitet i längden, särskilt i en mer komplex applikation.