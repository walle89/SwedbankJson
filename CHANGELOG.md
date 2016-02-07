# Change Log
Alla märkbara förändringar i detta projekt kommer att dokumenteras i den här filen.

## [Unreleased][unreleased]
### Changed
- Uppdatering av appversion för samtliga Swedbank-appar.

## [0.6.1] - 2016-02-07
### Added
- Dokumentation för snabbsaldo

### Changed
- Rätt sökvägar i dokumentation

### Fixed
- Cleanup rensade inte sparad session
- Rätt UserException namespace för Appdata

## [0.6.0] - 2016-01-31
### Added
- Ny inloggingstyp: Mobilt BankID
- Ny inloggingstyp: Ingen inlogging

### Changed
- Guzzle 6 och därmed PHP 5.5 krav
- Uppdatering av appversion för samtliga Swedbank-appar.
- Förbättrad felhantering
- Förbättrad dokumentation

### Removed
- Automatisk utlogging - Måste anropa terminate() manuelt

## [0.5.2] - 2015-04-21
### Changed
- Uppdatering av Guzzle beroende.

### Fixed
- Getchallenge() sparar inte utmaningen inom en session. Issue #22

## [0.5.1] - 2015-03-09
### Changed
-  Uppdateringar av Guzzle

## [0.5.0] - 2015-03-09
### Added
- Stöd för inlogging för bankdosa med engångslösenord (se README.md)

### Changed
-  Med anledning av stödjet av bankdosa har skapadet av SwedbankJson objektet gjots om. Se README.md.
-  Förenklad och snyggare att sätta AppID som är bakåtkompatibel med den tidigare metoden.
-  Uppdatering av appversion för samtliga Swedbank-appar.
-  Uppdaterad dokumentation med guide för Windowsanvändare.

## [0.4.0] - 2015-02-13
### Added
- Snabbsaldo - Visa, aktivera och avaktivera snabbsaldo
- Reminders - Hämta antalet avvisade betalningar, osignerade betalningar, osignerade överförningar och inkommna e-fakturor
- Baseinfo - Lista på konton grupperade på typ

### Changed
- AccountDetails kräver inte längre AccoutID. Om AccoutID inte är anget så används första kontot.
- ProfilID inte längre nödvändig att ange i flertal anrop. Om ProfilID inte är anget så används första profilen.
- Uppdatering av appversion för samtliga Swedbank-appar.
- Uppdaterad dokumentation
- Uppdateringar av bereonden av UDID och Guzzle

## [0.3.4] - 2014-06-18
### Added
- Stöd för Sparbanken Företag

### Changed
- Uppdaterad appversion för Swedbank och Sparbanken
- Uppdaterad dokumenation
- Testad med Guzzle 4.1 och fungerar

### Deprecated
- BankID "swedbank_företag" har bytt namn till "swedbank_foretag"

## [0.3.3] - 2014-05-28
### Added
- MIT Lisens-fil tillagd

### Changed
- Småfix av dokumenation
- Testad med Guzzle 4.1 och fungerar

### Fixed
- Fix för att radera cookie-fil efter kör förfrågan.

## [0.3.2] - 2014-05-15
### Fixed
- Fix för #8. Linux skiljer mellan versaler och gemener på filnamn, vilket OSX inte gör.

## [0.3.1] - 2014-05-15
### Changed
- Byte av namespaceing
- Composer justeringar

## [0.3.0] - 2014-05-15
### Changed
- Använder Guzzle 4 biblioteket, därmed krävs från och med nu PHP 5.4 eller senare.
- Renare och bättre struktur
- Uppdaterad Readme.md för tydligare instruktioner.

### Fixed
- Fixar issue #6.

## [0.2.0] - 2014-05-14
### Changed
- Konverterad till Composer.

## 0.1.0 - 2014-05-14
### Removed
- Utesluter temp-mapp och justering av tests-mappen

## 0.0.6 - 2014-05-04
### Added
- Väljer profil utifrån user-agent.
- Möjlighet att styra antal transaktioner som ska listas "per sida".

### Changed
- Uppdaterad menyanrop
- Anpassing till nya "Easy login"-funktionen samt felmeddealnde för byte av personlig kod.

### Fixed
- Buggfixar issue #4

## 0.0.5 - 2014-04-21
### Added
- Stöd för sparbanker, företag, swedbank ung samt sparbank ung

### Changed
- Förbättrad dokumentation

## 0.0.4 - 2014-02-09
### Changed
- Förbättrad felhantering

## 0.0.3 - 2014-02-01
### Added
- Investeringssparand

### Changed
- Bättre output av felmeddelanden

## 0.0.2 - 2013-03-24
### Changed
- Kompatibel med 3.2.0 API:et.

## 0.0.1 - 2013-03-15
### Added
- Första släppet

[unreleased]: https://github.com/walle89/SwedbankJson/compare/v0.6.1...HEAD
[0.6.1]: https://github.com/walle89/SwedbankJson/compare/v0.6.0...v0.6.1
[0.6.0]: https://github.com/walle89/SwedbankJson/compare/v0.5.2...v0.6.0
[0.5.2]: https://github.com/walle89/SwedbankJson/compare/v0.5.1...v0.5.2
[0.5.1]: https://github.com/walle89/SwedbankJson/compare/v0.5.0...v0.5.1
[0.5.0]: https://github.com/walle89/SwedbankJson/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/walle89/SwedbankJson/compare/v0.3.4...v0.4.0
[0.3.4]: https://github.com/walle89/SwedbankJson/compare/v0.3.3...v0.3.4
[0.3.3]: https://github.com/walle89/SwedbankJson/compare/v0.3.2...v0.3.3
[0.3.2]: https://github.com/walle89/SwedbankJson/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/walle89/SwedbankJson/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/walle89/SwedbankJson/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/walle89/SwedbankJson/compare/v0.1.0...v0.2.0