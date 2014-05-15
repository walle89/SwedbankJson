# SwedbankJson

Wrapper för Swedbanks stängda API som används för swedbanks- och sparbakernas mobilappar. Inlogging görs med hjälp av internetbankens personliga kod (person- eller orginisationsnummer och lösenord).

Exempel för privatperson

```php
require_once 'vendor/autoload.php';

use SwedbankJson\SwedbankJson;
use SwedbankJson\AppData;

// Inställningar
define('USERNAME',  198903060000);   // Personnummer
define('PASSWORD',  'fakePW');       // Personlig kod
define('BANKID',    'swedbank');     // Byt mot motsvarnde IOS/Android mobil app. Alternativ: swedbank, sparbanken, swedbank_ung, sparbanken_ung, swedbank_företag

$bankConn    = new SwedbankJson(USERNAME, PASSWORD, AppData::bankAppId(BANKID));
$accounts    = $bankConn->accountList();
$accountInfo = $bankConn->accountDetails($accounts->transactionAccounts[0]->id); // Hämtar från första kontot, sannolikt lönekontot
$bankConn->terminate();

echo '<strong>Konton<strong><pre>';
print_r($accounts);

echo '<strong>Kontoutdrag</strong>';
print_r($accountInfo);
```

## Systemkrav

* PHP 5.4+
* Curl

## Installation via Composer

Rekomendationen är att instllera SwedbankJson med [Composer](http://getcomposer.org).

Kör följande i ett terminalfönster:
```bash
# Installera Composer
curl -sS https://getcomposer.org/installer | php
```

Lägg in SwebankJson i composer.json antingen med följande kommando:
```bash
# Uppdatera eller skapa composer.json samt kör installation
php composer.phar require walle89/swedbank-json ~0.3
```

***Eller*** manuellt med:
```javascript
{
    "require": {
        "walle89/swedbank-json": "~0.3"
    }
}
```

Efter lyckad installation, ladda in autoload.php i vendor mappen.

```php
require 'vendor/autoload.php';
```

## Dokumentation

Finns i form av PHPDoc kommentarer i filerna. Utförligare dokumentation med API-anrop finns på todo-listan.

## Uppdateringar

Kör följande kommando:
```bash
php composer.phar update
```

Det är främst [appdata.php](https://github.com/walle89/SwedbankJson/blob/master/src/appdata.php) som kan komma att ändras i samband med Swedbank uppdaterar sina appar och därmed appID:n och User Agents.

## Feedback, frågor, buggar, etc.

Skapa en [Github Issue](https://github.com/walle89/SwedbankJson/issues).

## Licens (MIT)
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
