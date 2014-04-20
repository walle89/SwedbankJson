<?php
require_once 'swedbankJson.php';
require_once 'appData.php';

// Inställningar
define('USERNAME',  198903060000);   // Personnummer
define('PASSWORD',  'fakePW');       // Personlig kod
define('BANKID',    'swedbank');     // Byt mot motsvarnde IOS/Android mobil app. Alternativ: swedbank, sparbanken, swedbank_ung, sparbanken_ung, swedbank_företag

try
{
    $bankConn    = new SwedbankJson(USERNAME, PASSWORD, $appData[BANKID]);
    $accounts    = $bankConn->accountList();
    $accountInfo = $bankConn->accountDetails($accounts->transactionAccounts[0]->id); // Hämtar från första kontot, sannolikt lönekontot
    $bankConn->terminate();
}
// Fel av användare
catch (UserException $e)
{
    echo $e->getMessage();
    exit;
}

// Systemfel och övriga fel
catch (Exception $e)
{
    echo 'Swedbank-fel: ' . $e->getMessage() . ' (Err #' . $e->getCode() . ")\r\n" . $e->getTraceAsString();
    exit;
}

####

echo '<strong>Konton<strong><pre>';
print_r($accounts);

####

echo '
<strong>Kontoutdrag</strong>
';
print_r($accountInfo);