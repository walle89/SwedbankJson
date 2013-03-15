<?php

require_once 'swedbankJson.php';

$username = 198903060000;   // Personnummer
$password = 'fakePW';       // Personlig kod

try
{
    $bankConn    = new SwedbankJson($username, $password);
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

echo '<pre>
Konton
';
print_r($accounts);

####

echo '
Kontoutdrag
';
print_r($accountInfo);

####

echo '
Auth-nyckel:
';

$bankConn = new SwedbankJson($username, $password);
echo $bankConn->getAuthorizationKey();