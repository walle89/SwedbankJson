# Authentication methods

* [Introduction](#introduction)
* [No authentication](#no-authentication)
* [Mobile BankID](#mobile-bankid)
* [Security token](#security-token)
* [Personal code - Discontinued](#personal-code---discontinued)

## Introduction
Common settings for code examples below.

```php
// Settings
$bankApp  = 'swedbank'; // Bank type
```

### Bank types
This API client is based on the same API that Swedbank using for there mobile apps. Before first run, you need to set $bankApp to one of the 4 apps form the list below.
What to $bankApp value to choose depends on the bank that the user is customer of. The rule of thumb is to choose whatever Swedbank's or Sparbanken's mobile app the user is using.

#### Swedbank
| $bankApp | Intended for | Mobile app name |
| --- | --- | --- |
| swedbank | Private individual | Swedbank | 
| swedbank_foretag | Organisation | Swedbank Företag | 

#### Sparbanken
| $bankApp | Intended for | Mobile app name |
| --- | --- | --- |
| sparbanken | Private individual | Sparbanken | 
| sparbanken_foretag | Organisation | Sparbanken Företag | 

If you are unsure, 'swedbank' or 'swedbank_foretag' is often a quite a safe bet.

#### Youth apps
As of November 2020, app types 'swedbank_ung' and 'sparbanken_ung' have been removed these apps are no longer maintained by Swedbank. Use 'swedbank' or 'sparbanken' bank types instead.

## No authentication
As the name suggests, for some API requests (eg. [Quick balance](../INSTALL.md#quick-balance)) there is no authentication required.
This makes it very easy to automatically fetch information with a cron job.
But most of Swedbank's APIs requires authentication with Mobile BankID or security token. 

```php
$appData  = new SwedbankJson\AppData($bankApp, __DIR__.'/AppData.json');
$auth     = new SwedbankJson\Auth\UnAuth($appData);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

## Mobile BankID
Mobile [BankID](https://www.bankid.com/en/) is an electronic identification (eID) issued by Swedish banks to authenticate persons and organizations with a smartphone app.
To use this authentication method, you must download the "BankID säkerhetsapp" app by Finansiell ID-Teknik BID AB for [IOS](https://itunes.apple.com/us/app/bankid-sakerhetsapp/id433151512?mt=8) or [Android](https://play.google.com/store/apps/details?id=com.bankid.bus&hl=en)
and follow the instructions in the app to activate BankID. If you use Mobile BankId today for sign in into one of the Swedbank apps, you are all set.

There are two variants of mobile BankID authentication; On same device and QR code. 

### Mobile BankID same device
For authentication on the same device as where the Mobile BankID app is installed. This makes it possible to authenticate without requiring use of a second device.
It will authenticate with the Personal identity number associated the mobile BankID app.

```php
session_start();

// Step 1 - Start the authentication process
if (!isset($_SESSION['swedbankjson_auth']))
{
    $appData = new SwedbankJson\AppData($bankApp, __DIR__.'/AppData.json');
    $auth    = new SwedbankJson\Auth\MobileBankID($appData);
    $auth->sameDevice(true);
    $auth->initAuth();
    
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
    exit;
}

// Step 2 - Verify authentication
$auth = unserialize($_SESSION['swedbankjson_auth']);
if (!$auth->verify()) {
    exit("<p>Waiting for verification, try to reload this page again.</p>");
}

// Step 3 - You are in!
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

You can modify the URL for `$bankIdAppUrl` in different ways depending on your use case. More info about it can be found in [BankID launching integrationsguide](https://www.bankid.com/utvecklare/guider/teknisk-integrationsguide/programstart).

### Mobile BankID QR code
With a generated QR code allows authenticating on a device that doesn't have Mobile BankID app installed and configured such as internet connected computer.
It will authenticate with the Personal identity number associated the mobile BankID app used to scan the QR code.

```php
session_start();

// Step 1 - Start the authentication process
if (!isset($_SESSION['swedbankjson_auth']))
{
    $appData = new SwedbankJson\AppData($bankApp, __DIR__.'/AppData.json');
    $auth    = new SwedbankJson\Auth\MobileBankID($appData);
    $auth->initAuth();

    // Need to reload page
    exit('<meta http-equiv="refresh" content="0">');
}

// Step 2 - Verify authentication
$auth = unserialize($_SESSION['swedbankjson_auth']);
if (!$auth->verify()) {

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
    exit;
}

// Step 3 - You are in!
$bankConn = new SwedbankJson\SwedbankJson($auth);
```
Note that the generated QR code becomes invalid within seconds sense Swedbank have implemented [Animated QR code](https://www.bankid.com/utvecklare/guider/teknisk-integrationsguide/qrkoder).
You have to either fetch and update the QR code around once per 2 seconds, or scan the code immediately once the code has been generated in order to successfully verify the authentication in the Bank ID app.

### Combined example
If you want to implement both Mobile Bank ID methods, see an example of this in [mobileBankIdSample.php](./mobileBankIdSample.php).

### Mobile BankID with Personal identity number - Discontinued
Swedbank discontinued Mobile BankID authentication with personal identity number in July 2021.

## Security token
Swedbank provides a hardware security token for all its internet bank customers. There are two main variations of the security tokens issued by the bank:

1. [Security token with one time code.](#security-token-with-one-time-code)
1. [Security token with control number and response code.](#security-token-with-control-number-and-response-code)

Both requires a pin to unlock the security token before any codes can be generated. 

[Read more about Swedbank security tokens](https://hjalp.swedbank.se/sidhjalp-internetbanken-privat/sakerhetsdosa/index.htm) (in Swedish).

### Security token with one time code
This type of security token generates a one time use 8-digit code. To generate this code, you need to press 1 when "Appli" displays on the screen.

From the perspective of login in into one of the mobile apps, the flow should look like this:

**Choose "Säkerhetsdosa" -> Enter the code from security token -> Authenticated**

```php
$username = 198903060000; // Personal identity number (personnummer).

if(empty($_POST['challengeResponse'])
{
    echo '
    <form action="" method="post">
        <p>Please enter the 8 digit time code from the security token</p>
        <input name="challengeResponse" type="text" />
        <button>Sign in</button>
    </form>';
    exit;
}
if(!is_numeric($_POST['challengeResponse']))
    exit('Wrong code!');

$appData  = new SwedbankJson\AppData($bankApp, __DIR__.'/AppData.json');
$auth     = new SwedbankJson\Auth\SecurityToken($appData, $username, $_POST['challengeResponse']);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

### Security token with control number and response code
Unlike security token with one time code, this type of token needs an 8-digit control number before it can generate a response code.
The response code is an 8-digit number used to enter into the app to verify authentication.

From the perspective of login into one of the mobile apps, the flow should look like this:

**Choose "Säkerhetsdosa" -> Enter control number into security token -> Enter response code -> Authenticated**

Currently, there is only an alfa implementation of this authentication method. If you are interested to try it out, [send me an email](http://wallmander.net/contact/) (NOTE: *Not* for support, [create an issue for that](https://github.com/walle89/SwedbankJson/issues)).

## Personal code - Discontinued
Swedbank discontinued personal code authentication method in February 2016.