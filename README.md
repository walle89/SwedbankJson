# SwedbankJson

Inofficiell wrapper för det API som används för Swedbanks och Sparbankernas mobilappar. Inlogging görs med hjälp av internetbankens personliga kod (person- eller organisationsnummer och lösenord).

**Detta kan wrappen göra**

* Översikt av tillgängliga konton så som lönekonto, sparkonton investeringsbesparningar, lån, bankkort och kreditkort.
* Lista ett kontos samtliga transaktioner med historik så långt bak i tiden som finns tillgängligt i internetbanken.
* Företagsinloggingar kan välja att lista konton utifrån en vald profil.
* Aktivera, avaktivera och visa snabbsaldo.
* Kommunicerar med Swedbanks servrar över SSL utan mellanhänder. Ingenting sparas eller loggas.
* Autentiseringsnyckel som krävs för inlogging genereras automatiskt per session (standard) eller manuellt sätta en statisk nykel.

[Fler funktioner finns planerade](https://github.com/walle89/SwedbankJson/labels/todo).

## Kodexempel

### Grund
Grundkoden för exemplen nedan:
```php
require_once 'vendor/autoload.php';

// Inställningar
define('BANKID',    'swedbank');     // Byt mot motsvarande IOS/Android mobil app. Alternativ: swedbank, sparbanken, swedbank_ung, sparbanken_ung, swedbank_foretag, sparbanken_foretag
define('USERNAME',  198903060000);   // Person- eller organisationsnummer
define('PASSWORD',  'fakePW');       // Personlig kod

$auth = new SwedbankJson\Auth\PersonalCode(BANKID, USERNAME, PASSWORD);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```
Men vill man använda en annan inloggigstyp än personlig kod behöver man modifera ovanstånde kod till ett av förjande:

#### Säkerhetsdosa (Loginkod)
Det finns två typer av varianter för inlogging med säkerhetsdosa. Ett av dessa är loginkod, som ger ett 8-siffrig kod när man har loggat in på dosan och väler 1 när Appli visas.

Utgår man från inlogginsflöde i mobilappen ser den ut som följande:

Välj säkerhetsdosa -> Fyll i engångskod från säkerhetsdosan -> Inloggad

```php
$auth = new SwedbankJson\Auth\SecurityToken(BANKID, USERNAME, $challengeResponse);
```
**$challengeResponse** ska vara ett 8-siffrigt nummer som man får från bankdosan som behövs för att logga in

#### Säkerhetsdosa (Responskod)
Den andra typen av inlogginsmetod för säkerhetsdosa är responskod. Här ska 

Utgår man från inlogginsflöde i mobilappen ser den ut som följande:

Välj säkerhetsdosa -> Mata in engångskod i dosan -> Fyll i svaret från säkerhetsdosan -> Inloggad

```php
// Work in progress
//$auth = new SwedbankJson\Auth\SecurityToken(BANKID, USERNAME);
//$auth->getchallenge();
//$_SESSION['auth'] = $auth;
// *Ny sidhämtning*
//$auth=$_SESSION['auth'];
//$auth->login($challengeResponse);

```

### Kontotransaktioner
Lista kontotransaktioner från första kontot som är sannolikt lönekontot med personlig kod. Ändra bara inställningarna nedan.
```php
$accountInfo = $bankConn->accountDetails(); // Hämtar från första kontot, sannolikt lönekontot

$bankConn->terminate(); // Utlogging

echo '<strong>Kontoutdrag</strong>';
print_r($accountInfo);
```

### Välja konto
För att lista och välja ett specifikt konto som man hämtar sina transaktioner kan man modifiera ovanstående kod till följande:
```php
$accounts = $bankConn->accountList(); // Lista på tillgängliga konton

$accountInfo = $bankConn->accountDetails($accounts->transactionAccounts[1]->id); // För konto #2 (gissningsvis något sparkonto)

$bankConn->terminate(); // Utlogging

echo '<strong>Konton</strong>';
print_r($accounts);

echo '<strong>Kontoutdrag</strong>';
print_r($accountInfo);
```

### Profilväljare (företag)
I Swedbanks API finns det stöd för att ha flera företagsprofiler kopplat till sin inlogging. Glöm inte att ändra BANKID till något av Swedbanks företagsappar.
```PHP
$profiles = $bankConn->profileList(); // Profiler

$accounts = $bankConn->accountList($profiles->corporateProfiles[0]->id); // Tillgängliga konton utifrån vald profil

$accountInfo = $bankConn->accountDetails($accounts->transactionAccounts[0]->id);

$bankConn->terminate(); // Utlogging

echo '<strong>Profiler</strong>';
print_r($profiles);

echo '<strong>Konton</strong>';
print_r($profiles);

echo '<strong>Kontoutdrag</strong>';
print_r($accountInfo);
```

## Systemkrav

* PHP 5.4+
* Curl

## Installation med Composer

Rekommendationen är att installera SwedbankJson med [Composer](http://getcomposer.org).

Kör följande i ett terminalfönster:
```bash
# Installera Composer
curl -sS https://getcomposer.org/installer | php
```

Lägg in SwebankJson i composer.json antingen med följande kommando:
```bash
# Uppdatera eller skapa composer.json samt kör installation
php composer.phar require walle89/swedbank-json ~0.4
```

***Eller*** manuellt med:
```javascript
{
    "require": {
        "walle89/swedbank-json": "~0.4"
    }
}
```

Efter lyckad installation, ladda in autoload.php i vendor mappen.

```php
require 'vendor/autoload.php';
```

## Dokumentation

Finns i form av PHPDoc kommentarer i filerna. Utförligare dokumentation med API-anrop finns på [todo-listan](https://github.com/walle89/SwedbankJson/wiki/Todo).

## Uppdateringar

Kör följande kommando:
```bash
php composer.phar update
```

Det är främst [appdata.php](https://github.com/walle89/SwedbankJson/blob/master/src/appdata.php) som kan komma att ändras i samband med Swedbank uppdaterar sina appar och därmed appID:n och User Agents.

## Feedback, frågor, buggar, etc.

Skapa en [Github Issue](https://github.com/walle89/SwedbankJson/issues).

## Andra projekt med Swedbanks API
* [SwedbankSharp](https://github.com/DarkTwisterr/SwedbankSharp) av [DarkTwisterr](https://github.com/DarkTwisterr) - C# med .NET implementation.
* [Swedbank-Cli](https://github.com/spaam/swedbank-cli) av [Spaam](https://github.com/spaam) - Swedbank i terminalen. Skriven i Python.
* [SwedbankJson](https://github.com/viktorgardart/SwedbankJson) av [Viktor Gardart](https://github.com/viktorgardart) - Objective-C implementation (för iOS).

## Licens (MIT)
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
