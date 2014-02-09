<?php

require_once 'swedbankJson.php';

// Inställningar
define('USERNAME', 198903060000);   // Personnummer
define('PASSWORD', 'fakePW');       // Personlig kod

echo '
Auth-nyckel:
';
try
{
    $bankConn = new SwedbankJson(USERNAME, PASSWORD);
    echo $bankConn->getAuthorizationKey();
}
catch (Exception $e)
{
    echo 'Swedbank-fel: ' . $e->getMessage() . ' (Err #' . $e->getCode() . ")\r\n" . $e->getTraceAsString();
    exit;
}



try
{
    $bankConn    = new SwedbankJson(USERNAME, PASSWORD, AUTH_KEY);
    $accounts    = $bankConn->accountList();
    $accountInfo = $bankConn->accountDetails($accounts->transactionAccounts[0]->id); // Hämtar från första kontot, sannolikt lönekontot
    $bankConn->terminate();
}
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

####
