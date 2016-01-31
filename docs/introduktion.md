# Introduktion

* [Systemkrav](#systemkrav)
* [Installation](#installation)
* [Komplett exempel](#komplett-exempel)
* [Kontotransaktioner](#kontotransaktioner)
* [Välja konto](#välja-konto)
* [Profilväljare (företag)](#profilväljare-företag)

## Systemkrav

* PHP 5.5+
* Curl

## Installation
Projektet finns på Packagist ([walle89/swedbank-json](https://packagist.org/packages/walle89/swedbank-json)) och kan därmed installeras med [Composer](http://getcomposer.org).

```bash
composer require walle89/swedbank-json ~0.6
```

Mer ingående [instruktioner för installation med Composer](composer.md)

## 

### Komplett exempel
```php
<?php 
require 'vendor/autoload.php';

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

## 

### Kontotransaktioner
Lista kontotransaktioner från första kontot.

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
I Swedbanks API finns det stöd för att ha flera företagsprofiler kopplat till sin inlogging. Glöm inte att ändra BANK_APP till an av Swedbanks företagsappar.

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