#0.5.0 / 2015-03-09

### Nyheter
* Stöd för inlogging för bankdosa med engångslösenord (se README.md)

### Ändringar
* Med anledning av stödjet av bankdosa har skapadet av SwedbankJson objektet gjots om. Se README.md.
* Förenklad och snyggare att sätta AppID som är bakåtkompatibel med den tidigare metoden.

### Uppdateringar
* Uppdatering av appversion för samtliga Swedbank-appar.
* Uppdaterad dokumentation med guide för Windowsanvändare.

#0.4.0 / 2015-02-13

### Nyheter
* Snabbsaldo - Visa, aktivera och avaktivera snabbsaldo
* Reminders - Hämta antalet avvisade betalningar, osignerade betalningar, osignerade överförningar och inkommna e-fakturor
* Baseinfo - Lista på konton grupperade på typ
 
### Ändringar
* AccountDetails kräver inte längre AccoutID. Om AccoutID inte är anget så används första kontot.
* ProfilID inte längre nödvändig att ange i flertal anrop. Om ProfilID inte är anget så används första profilen.
 
### Uppdateringar
* Uppdatering av appversion för samtliga Swedbank-appar.
* Uppdaterad dokumentation

### Övrigt
 * Uppdateringar av bereonden av UDID och Guzzle
 
#0.3.4 / 2014-06-18

#### Nyheter
* Stöd för Sparbanken Företag

#### Ändringar
* BankID "swedbank_företag" har bytt namn till "swedbank_foretag"
* Uppdaterad appversion för Swedbank och Sparbanken
* Uppdaterad dokumenation

#### Övrigt
* Testad med Guzzle 4.1 och fungerar

#0.3.3 / 2014-05-28

#### Ändringar
* Fix för att radera cookie-fil efter kör förfrågan.
* Småfix av dokumenation
* MIT Lisens-fil tillagd

#### Övrigt
* Testad med Guzzle 4.1 och fungerar

#0.3.2 / 2014-05-15
* Fix för #8. Linux skiljer mellan versaler och gemener på filnamn, vilket OSX inte gör.

#0.3.1 / 2014-05-15
* Byte av namespaceing
* Composer justeringar
* Rättstavning

#0.3.0 / 2014-05-15
* Fixad så att den åter fungerar med Swedbanks API. Fixar issue #6.
* Använder Guzzle 4 biblioteket, därmed krävs från och med nu PHP 5.4 eller senare.
* Renare och bättre struktur
* Uppdaterad Readme.md för tydligare instruktioner.

#0.2.0 / 2014-05-14
* Konverterad till Composer.

#0.1.0 / 2014-05-14
* Utesluter temp-mapp och justering av tests-mappen

#0.0.6 / 2014-05-04
* Uppdaterad menyanrop
* Väljer profil utifrån user-agent.
* Anpassing till nya "Easy login"-funktionen samt felmeddealnde för byte av personlig kod.
* Möjlighet att styra antal transaktioner som ska listas "per sida".
* Buggfixar issue #4

#0.0.5 / 2014-04-21
* Stöd för sparbanker, företag, swedbank ung samt sparbank ung
* Förbättrad dokumentation

#0.0.4 / 2014-02-09
* Förbättrad felhantering

#0.0.3 / 2014-02-01
* Investeringssparand
* Bättre output av felmeddelanden

#0.0.2 / 2013-03-24
* Kompatibel med 3.2.0 API:et. Nya fast auth-nyckel måste genereras!

#0.0.1 / 2013-03-15
* Första släppet