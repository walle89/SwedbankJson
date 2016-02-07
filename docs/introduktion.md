# Introduktion

* [Systemkrav](#systemkrav)
* [Installation](#installation)
* [Komplett exempel](#komplett-exempel)
* [Kontotransaktioner](#kontotransaktioner)
* [Välja konto](#välja-konto)
* [Profilväljare (företag)](#profilväljare-företag)
* [Snabbsaldo](#snabbsaldo)
* [Flytta pengar](#flytta-pengar)

## Systemkrav

* PHP 5.5+
* Curl

## Installation
Projektet finns på Packagist ([walle89/swedbank-json](https://packagist.org/packages/walle89/swedbank-json)) och kan därmed installeras med [Composer](http://getcomposer.org).

```bash
composer require walle89/swedbank-json ~0.6
```

Mer ingående [instruktioner för installation med Composer](composer.md)

## Komplett exempel
Detta exempel använder [säkerhetsdosa med engångskod](inloggingstyper.md#säkerhetsdosa-med-engångskod) som inloggingstyp för att lista kontotransaktioner. 

```php
<?php 
require 'vendor/autoload.php';

// Inställningar
$bankApp  = 'swedbank';
$username = 8903060000; 

if(empty($_POST['challengeResponse'])
{
   echo '
   <form action="" method="post">
       <p>Fyll i 8-siffrig engångskod från säkerhetsdosa</p>
       <input name="challengeResponse" type="text" />
       <button>Logga in</button>
   </form>';
   exit;
}
if(!is_numeric($_POST['challengeResponse']))
   exit('Fel indata!');

$auth     = new SwedbankJson\Auth\SecurityToken($bankApp, $username, $_POST['challengeResponse']);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$accountInfo = $bankConn->accountDetails();
$bankConn->terminate(); // Utlogging

echo 'Kontoutdrag
<pre>';
print_r($accountInfo);
```

### Föredrar en annan inlogginstyp?
[Lista och instruktioner för respektive inloginstyp](inloggingstyper.md).

## Kontotransaktioner
Lista kontotransaktioner från första kontot.

```php
$accountInfo = $bankConn->accountDetails(); // Hämtar från första kontot, sannolikt lönekontot

$bankConn->terminate(); // Utlogging

echo '<strong>Kontoutdrag</strong>';
print_r($accountInfo);
```

## Välja konto
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

## Profilväljare (företag)
I Swedbanks API finns det stöd för att ha flera företagsprofiler kopplat till sin inlogging. Glöm inte att ändra BANK_APP till ett av Swedbanks företagsappar.

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

## Snabbsaldo 
Ett av få API-anrop som kan helautomatiseras, då det inte kräver någon inlogging. Detta föutsätter att man skaffar ett SubscriptionId (se "[Hur hämtar jag SubscriptionId?](#hur-hämtar-jag-subscriptionid)").
SubscriptionId är ett unikt ID per konto som kan bland annat ge följande information:

* Aktuellt totalsaldo för kontot
* Om det finns eller inte finns notiser för användaren (ex. nyinkomen e-faktura)

Detta ID är tänkt att sparas och användas varje gång man begär snabbsaldo.

```php
<?php 
require 'vendor/autoload.php';

// Inställningar
$bankApp        = 'swedbank';
$subscriptionId = 'ExampleXX2GCi3333YpupYBDZX75sOme8Ht9dtuFAKE=';

$auth     = new SwedbankJson\Auth\UnAuth($bankApp);
$bankConn = new SwedbankJson\SwedbankJson($auth);

echo '<pre>';
var_dump($bankConn->quickBalance($subscriptionId));

```

### Hur hämtar jag SubscriptionId?
Enklast är att använda detta verktyg:

```php
<?php 
require 'vendor/autoload.php';

session_start();

// Inställningar
$bankApp  = 'swedbank';
$username = 8903060000; 

// Inled inloggning
if (!isset($_SESSION['swedbankjson_auth']))
{
    $auth = new SwedbankJson\Auth\MobileBankID($bankApp, $username);
    $auth->initAuth();
    exit('Öppna BankID-appen och godkänn inloggingen. Därefter uppdatera sidan.');
}

// Verifiera inlogging
$auth = unserialize($_SESSION['swedbankjson_auth']);
if (!$auth->verify())
    exit("Du uppdaterade sidan, men inloggningen är inte godkänd i BankID-appen. Försök igen.");

// Inloggad
$bankConn = new SwedbankJson\SwedbankJson($auth);

if (empty($_POST['quickbalanceSubscriptionID']))
{
    $quickBalanceAccounts = $bankConn->quickBalanceAccounts();

    echo '<form action="" method="post"><p>Välj konto för subscriptionId</p><select name="quickbalanceSubscriptionID">';

    foreach ($quickBalanceAccounts->accounts as $account)
        echo '<option value="'.$account->quickbalanceSubscription->id.'">'.$account->name.'</option>';

    echo '</select><button>Skapa prenumeration</button></form>';
    exit;
}

$subInfo = $bankConn->quickBalanceSubscription($_POST['quickbalanceSubscriptionID']);
echo "<p>Ditt subscriptionId: {$subInfo->subscriptionId}</p>
<p>Testa direkt:</p>var_dump(\$bankConn->quickBalance('{$subInfo->subscriptionId}'));";

$auth->terminate(); // Utlogging
```

## Flytta pengar
Just nu stöds direktöverförningar samt schemalagd och periodiserade överförningar mellan egna konton.
Möjligtvis finns det stöd för andra typer av överförningar, under förutsättning att de inte behöver signeras.

Exempel på hur man flyttar 0,99 kronor mellan två konton

```php
echo '<pre>':
$baseInfo = $bankConn->baseInfo();

// Hitta konton som passar utifrån dina förutsättningar
print_r($baseInfo);

// Ersätts med fördel av ett fomulär
// OBS! Ändra detta innan du testar koden!
$fromAccountId      = $baseInfo->fromAccountGroup[0]->accounts[0]->id;      // Ex. Lönekonto
$recipientAccountId = $baseInfo->recipientAccountGroup[1]->accounts[3]->id; // Ex. Semensterkonto

// Registera direktöverförning
$result = $bankConn->registerTransfer(0.99, $fromAccountId, $recipientAccountId, 'Från test', 'Till test');

// Se om överförningen registrerades
print_r($result); // Likande output som listRegisteredTransfers()

// Verkställ överförning
print_r($bankConn->confirmTransfer());

// Om man vill, kolla att inga överförningar finns kvar
print_r($bankConn->listRegisteredTransfers());

$auth->terminate(); // Utlogging
```

Det finns stöd för att registera flera överförningar och variationer av överförningar

```php
// Direktöverförning utan meddelande
$bankConn->registerTransfer(0.99, $fromAccountId, $recipientAccountId);

// Schemalagd överförning, kommer endast ske en gång
$bankConn->registerTransfer(1000.00, $fromAccountId, $recipientAccountId, 'Present', 'Present', '2016-03-06');

// Periodiserad överförning, datum måste anges som fungerar som startdatum.
// Möjliga perioder finns avgörs av 'perodicity' som bland annat finns i resultatet av baseInfo().
// Exemel på perioder: ["NONE", "WEEKLY", "EVERY_OTHER_WEEK", "MONTHLY", "EVERY_OTHER_MONTH", "QUARTERLY", "SEMI_ANNUALLY", "ANNUALLY"]
$bankConn->registerTransfer(1000.00, $fromAccountId, $recipientAccountId, 'Present', 'Present', '2017-03-06', 'ANNUALLY');

// Bekräfta alla överförningar
print_r($bankConn->confirmTransfer());

// Se om schemalagda och periodiserade överförningar regisiterades korrekt
print_r($bankConn->listConfirmedTransfers());
```

Var dock noga med att inte registera två likande överförningar (samma summa, sändar- och mottagarkokonton samt datum), då genereras felmeddelande och förlorar sessionen.
Man behöver då logga in igen, men tidigare registerade överförningar finns kvar.

För att ta bort en överförning kan man göra följande

```php
// Ta bort obekräftad överförning
$transfares = $bankConn->listRegisteredTransfers();
$bankConn->deleteTransfer($transfares->transferGroups[0]->transfers[0]->id);

// Ta bort schemalagd eller periodiserad överförning
$confirmedTransfares = $bankConn->listConfirmedTransfers();
$bankConn->deleteTransfer($confirmedTransfares->transferGroups[0]->transfers[2]->id);
```