### Krav 1-3

Jag har byggt spelet *Poker Squares* som mitt projekt under [`/proj`]({{ path('proj_start') }}). Ambitionen har varit att försöka göra spelet så komplett som möjligt, och att göra det efter goda objektorienterade principer. Jag har byggt stöd för att spela flera spelare (med samma kortlek), och det finns tre dator-spelare med olika intelligens. Det går att skapa spelare, spara en avslutad runda (tid, poäng, spelplan etc kopplat till en spelare), och det går att läsa och ändra innehållet i databasen (CRUD).

Jag har olika stil för kurs- respektive projekt-sidan genom två olika *entrypoints* i ramverket - *report* och *projekt*, vilka leder till två uppsättningar javascript- och css-filer. 

Mitt repo går att klona, och jag har gjort så att en SQLite-databas används vid kloning. På studentservern och lokalt används MariaDB.

Jag har försökt skriva enhetstester för att täcka in majoriteten av koden. Jag har utelämnat de kontrollers som jobbar mot databasen eftersom jag inte fick det att fungera, men jag har skrivit andra tester mot en *in memory SQLite-databas*, som jag fick att fungera.

Länk till [`/proj/about`]({{ path('proj_about') }})

### Krav 4

På sidan [`/proj/api`]({{ path('proj_api') }}) presenterar jag sex olika JSON api:er - fyra *GET-routes* och två *POST-routes*. 

- **Visa alla rundor [GET]** - visar alla rundor sparade i databasen, senast först.
- **Visa topplista [GET]** - visar de tio rundor med högst poäng.
- **Visa alla spelare [GET]** - visar alla spelare i bokstavsordning.
- **Visa valda spelare [GET]** - hämtar de spelare som för tillfället är valda från sessionen.
- **Visa pågående spel [POST]** - hämtar data för aktuell spelomgång från sessionen.
- **Återställ databasen [POST]** - återställer databasen, dvs tar bort alla spelare och rundor, samt återskapar de tre dator-spelarna.

### Krav 5

Jag har använt mig av *Doctrine/ORM* för att spara php-object (Entiteter) i en databas (MariaDB). Entiteterna `Player`, `Round`, `Board` och `Score` mappas mot tabeller i databasen, och genom *Repository-klasser* och *EntityManager* är det enkelt att hämta och modifiera innehållet i databasen.

Jag blev förvånad över hur smidigt det fungerade att jobba med ORM för att lagra php-object i en databas. Jag minns från databas-kursen att det var svårt med relations-tänket till en början, och att det krävdes en hel del tid för att komma in i det. När man väl hade vant sig vid att översätta data och dess relationer till relationsmodellen, kändes det ganska bekvämt och kraftfullt. Jag föreställde mig därför att det skulle bli klurigt att få till relationerna på rätt sätt med ORM, men det visade sig vara ganska enkelt, och krävde bara några rader för varje Entitet, och det mesta genererades automatiskt med hjälp av *Symfony MakerBundle*. Även databasfrågorna var smidiga att formulera i *repository-klasserna*, med hjälp av *Doctrines* inbyggda lösningar. Jag behövde aldrig skriva någon ren SQL, även om jag hade nytta av mina SQL-kunskaper.

Spontant ser jag nog fler fördelar än nackdelar med ORM jämfört med att själv modellera och implementera en databas. Det är enklare, mer flexibelt med att kunna skifta databashanterare, och det kräver inte lika mycket kunskap om databaser. Nackdelar skulle kunna vara att man tappar lite kontroll över den faktiska strukturen i databasen, vilket skulle kunna leda till sämre prestanda, säkerhet och flexibilitet i längden, särskilt i en mer komplex applikation.

Länk till dokumentation för databasen [`/proj/about/database`]({{ path('proj_database') }})

### Krav 6

Jag ville bygga en applikation jag var nöjd med, och lära mig så mycket som möjligt på vägen. Jag gav mig själv lite extra tid på sommaren, och har därför kunnat göra en hel del utöver grundkraven.

- Jag använde **MariaDB** som databashanterare både lokalt och på studentservern, vilket krävde lite extra administration för att det skulle fungera. För den som klonar repot blir det enklare att komma igång med *SQLite*, varför jag har valde att sätta `DATABASE_URL="sqlite..."` i `.env`, och `DATABASE_URL="mysql...` i `.env.local` och `.env.student`.

- Jag skrev **enhetstester mot databas** i `PlayerRepositoryTest`, `RoundRepositoryTest`, `InitCpuPlayerServiceDbTest`, `ResetDatabaseServiceTest` och `SelectedPlayersServiceTest`. Jag använde mig av Symfonys `KernelTestCase`, Doctrines `SchemaTool`, och en *in memory SQLite-databas* - `DATABASE_URL="sqlite:///:memory:"` i `.env.test`.

- Mina entiteter är sammankopplade vilket ger olika **relationer mellan tabellerna i databasen**. En *round* är alltid kopplad till en *player*, *score* och *board*. En *player* kan vara kopplad till flera olika *round*. När en *player* raderas plockas även alla relaterade *round*, *score* och *board* bort genom *cascade*.

- Jag valde att bygga **dator-intelligens** för tre olika dator-spelare - *CPU LÄTT, CPU MEDEL, CPU SVÅR*. Den lätta lägger ut korten slumpvis, den medelsvåra försöker bygga händer som ger *flush*, och den svåra försöker bygga händer som kan ge *flush* eller *one-pair*, *two-pairs*, *three-of-a-kind*, *four-of-a-kind*, och *full-house*.

- Jag har byggt mina formulär med **Symfony Form**. I `App\Form` finns fyra olika *Types*, som definierar innehållet i formulären, samt kopplar till en entitet som i fallet med `PlayerType`. Jag har även använt *Symfony Validator* för att validera input.

- Genom två **JS-moduler** har jag byggt stöd för att kunna klicka på en tom ruta i spelplanen, samt gjort en animation (tidsfördröjning) av datorspelarens drag.

- Jag implementerade **CRUD för all data**, genom att göra det möjligt att skapa, visa, ändra och radera en spelare eller runda. Att kunna ändra i en spelad runda vore dock inte lämpligt, så det utelämnade jag. Det går att klicka sig omkring på ett användarvänligt sätt i tabellerna för spelare och rundor.

- Jag lade extra tid på min **implementering av regler med interface och trait** i `App\PokerSquares\Rule`. Här försökte jag skriva så *DRY* kod som möjligt genom att återanvända logik med *trait*. Alla regler implementerar `PokerRuleInterface`, vilket är ger möjlighet för *dependency injection* och *loose coupling*.

### Om projektet

Det gick ganska snabbt att komma igång med projektet, eftersom det innebar att bygga vidare på me-sidan, och fortsätta utveckla ett kortspel. De tankemässiga utmaningarna låg främst i att försöka strukturera koden på ett bra sätt enligt de riktlinjer vi lärt oss i kursen, att lösa regler och poängräkning för spelet, datorintelligens, samt att lära sig ramverk-specifika metoder för formulär och testning. Till skillnad från många tidigare kurser gick förhållandevis lite tid åt att lösa buggar, mycket på grund av att symfony erbjuder en väldigt tydlig och bra felhantering. Jag lade ganska mycket mer tid på projektet än planerat, men jag ville hellre sy ihop det hela på ett tillfredställande sätt, än att plocka bort feautures för att spara tid. Jag tycker projektet var rimligt, och jag upplevde inte att grundkraven var för svåra att lösa.

### Om kursen

Generellt tycker jag att kursen var spännande och lärorik. Jag uppskattde utvecklingsmiljön, och det var kul att lära sig fler objektorienterade principer, inte minst genom att jobba med spellogik. ORM var också trevligt att bekanta sig med.

Undervisningen har varit bra. Det enda kursmomentet jag inte riktigt gillade var kmom06, eftersom det var svårt att få ut något vettigt av de automatiska testerna. Jag tror det hade varit bättre för det kursmomentet om alla fick en färdig kodbas, som hade tydliga brister, och som man sedan med hjälp av metrics-verktygen skulle försöka förbättra.

Jag ger kursen en 8:a i betyg, och skulle rekommendera kursen.

