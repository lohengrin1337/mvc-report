Jag gillar att skriva testkod, eftersom det ger ett tillfredställande resultat, där man får hjälp med överblick och koll på koden.
Kodtäckningsverktyget underlättade verkligen processen med att se exakt vilka delar av koden som testas, och vilka luckor som finns. phpUnit fungerade bra, och det var lätt att överföra kunskaper från tidigare testning i python.

Jag kom upp i 99% kodtäckning för klasserna till kortspelet. Det krävdes en del mockning för att få till det. Det stora jobbet var att lösa testningen för `CardGame21` - klassen.

Jag har försökt få koden *löst kopplad* genom *dependency injection* när klasser har en kompositions-relation. Det gjorde det möjligt att skicka med *stubbar* som argument i testningen i många fall. Jag lyckades inte testa `CardDeck` med mockade kort, eftersom 52 olika kort-instanser skapas och läggs till i konstruktorn. Jag vet inte om jag borde gjort annorlunda där, men jag kunde ändå testa funktionaliteten tillräckligt bra tycker jag. Jag valde att ha en publik metod `CardGame21::getState()` för att ge controllern enkel tillgång till spelets status. Jag märkte att det också underlättade betydligt för testningen, eftersom privata properties inte är tillgängliga i testen.

Jag upptäcke något litet slarvfel i koden genom testerna, och kunde ta bort några onödiga if-satser, men i övrigt gjorde jag inga större ändringar.

Ju mindre och enklare klasserna är, desto lättare är det att testa. Små enkla klasser är också lättare att förstå, vilket är positivt när man jobbar i team.
En sak jag inte blir klok på är hur jag skulle ha testat `CardGame21` - klassen ifall den inte avslöjade sina private properties genom `getState()`. Är det dålig kod om man har metoder som ändrar värden på klassens properties?

Veckas TIL är arbetet med att skapa test-stubbar för att få enhetstesterna att ha färre beroenden.