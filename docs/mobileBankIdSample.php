<?php
session_start();

require_once '../vendor/autoload.php';

if (!isset($_GET['authType']))
{
    echo '<strong>Select Mobile BankID Method</strong><br><a href="?authType=0">QR Code</a> - <a href="?authType=1">Same Device</a>';
    exit;
}

$bankApp    = 'swedbank';   // Select bank app
$auth       = null;
$sameDevice = (bool)$_GET['authType'];
try
{
    // Step 1 - Start the authentication process
    if (!isset($_SESSION['swedbankjson_auth']))
    {
        $appData = new SwedbankJson\AppData($bankApp, __DIR__.'/AppData.json');
        $auth    = new SwedbankJson\Auth\MobileBankID($appData);

        $auth->sameDevice($sameDevice);
        $auth->initAuth();

        if ($sameDevice)
        {
            // Automatic redirect back to script from BankID app, replace "null" with URL to this script. Eg. "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"
            $redirectURL = urlencode('null');

            $bankIdAppUrl = sprintf('https://app.bankid.com/?autostarttoken=%s&redirect=%s', $auth->getAutoStartToken(), $redirectURL);

            echo '<strong>Instructions: Sign in with Mobile BankID on same device</strong>';
            printf('
                <ol>
                    <li><a href="%s">Use this link to authenticate in BankID app</a></li>
                    <li>Go back to this page and reload it</li>
                </ol>',
                $bankIdAppUrl
            );
        }
        else
        {
            // Need to reload page
            echo '<meta http-equiv="refresh" content="0">';
        }
        exit;
    }

    // Step 2 - Verify authentication
    $auth = unserialize($_SESSION['swedbankjson_auth']);

    if (!$auth->verify())
    {
        if ($sameDevice)
        {
            echo '<p>Waiting for verification. This page will update automatically.</p>';
        }
        else
        {
            // Reload page once per 2 seconds.
            echo '<meta http-equiv="refresh" content="2">';

            echo '<strong>Instructions</strong>
                  <ol>
                      <li>Open the BankID app</li>
                      <li>Press "Scan QR code" and scan the QR code below.</li>
                      <li>Done, this page will update automatically.</li>
                  </ol>';
            printf(
                '<img src="data:image/png;base64,%s" style="max-width:100%%">',
                base64_encode($auth->getChallengeImage())
            );
        }
        exit;
    }

    // Step 3 - You are in!
    $bankConn = new SwedbankJson\SwedbankJson($auth);
} catch (Exception $e)
{
    exit($e->getMessage());
}

// Example of account details call. See documentation for details.
echo '<h1>Account details</h1><pre>';
print_r($bankConn->accountDetails());

// Example of credit card details call. See documentation for details.
echo '<h1>Credit card details</h1><pre>';
print_r($bankConn->creditCardDetails());

// Sign out. If you want to keep the session to next page load, remove this line.
$bankConn->terminate();