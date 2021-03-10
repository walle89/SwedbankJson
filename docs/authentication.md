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
$username = 198903060000; // Personal identity number (personnummer).
$bankApp  = 'swedbank';   // Bank type
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

If you are unsure, 'swedbank' or 'swedbank_foretag' is often a quite a safe bet. There is no reliable way to find out in which bank the user is a customer of.

#### Youth apps
As of November 2020, app types 'swedbank_ung' and 'sparbanken_ung' have been removed these apps are no longer maintained by Swedbank. Use 'swedbank' or 'sparbanken' bank types instead.

## No authentication
As the name suggests, for some API requests (eg. [Quick balance](../INSTALL.md#quick-balance)) there is no authentication required.
This makes it very easy to automatically fetch information with a cron job.
But most of Swedbank's APIs requires authentication with Mobile BankID or security token. 

```php
$auth     = new SwedbankJson\Auth\UnAuth($bankApp);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

## Mobile BankID
Mobile [BankID](https://www.bankid.com/en/) is an electronic identification (eID) issued by Swedish banks to authenticate persons and organizations with a smartphone app.
To use this authentication method, you must download the "BankID säkerhetsapp" app by Finansiell ID-Teknik BID AB for [IOS](https://itunes.apple.com/us/app/bankid-sakerhetsapp/id433151512?mt=8) or [Android](https://play.google.com/store/apps/details?id=com.bankid.bus&hl=en)
and follow the instructions in the app to activate BankID. If you use Mobile BankId today for sign in into one of the Swedbank apps, you are all set.

The authentication process takes several steps to complete and between each step requires the session is saved. 

```php
session_start();

// Step 1 - Start the authentication process
if (!isset($_SESSION['swedbankjson_auth']))
{
    $auth = new SwedbankJson\Auth\MobileBankID($bankApp, $username);
    $auth->initAuth();
    exit("Open the BankID app and confirm the login. Then refresh the page.");
}

// Step 2 - Verify authentication
$auth = unserialize($_SESSION['swedbankjson_auth']);
if (!$auth->verify())
    exit("You updated the page, but the login is not approved in the BankID app. Please try again.");

// Step 3 - You are in!
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

The idea of the flow of the sample code above is as follows:

**Visit site -> Open the BankID app and verify -> Refresh page -> Authenticated**

This sample code is not an elegant solution. To make it more user friendly, use Javascript and Ajax to check login verification every 5 secounds and automatically forward the user to the next page when verified.

## Security token
Swedbank provides a hardware security token for all its internet bank customers. There are two main variations of the security tokens issued by the bank:

1. [Security token with one time code.](#security-token-with-one-time-code)
1. [Security token with control number and response code.](#security-token-with-control-number-and-response-code)

Both requires a pin number to unlock the security token before any codes can be generated. 

[Read more about Swedbank security tokens](https://hjalp.swedbank.se/sidhjalp-internetbanken-privat/sakerhetsdosa/index.htm) (in Swedish).

### Security token with one time code
This type of security token generates a one time use 8 digit code. To generate this code, you need to press 1 when "Appli" displays on the screen.

From the perspective of login in into one of the mobile apps, the flow should look like this:

**Choose "Säkerhetsdosa" -> Enter the code from security token -> Authenticated**

```php
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

$auth     = new SwedbankJson\Auth\SecurityToken($bankApp, $username, $_POST['challengeResponse']);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

### Security token with control number and response code
Unlike security token with one time code, this type of token needs a 8 digit control number before it can generate a response code.
The response code is a 8 digit number used to enter into the app to verify authentication.

From the perspective of login into one of the mobile apps, the flow should look like this:

**Choose "Säkerhetsdosa" -> Enter control number into security token -> Enter response code -> Authenticated**

Currently there is only an alfa implementation of this authentication method. If you are interested to try it out, [send me an email](http://wallmander.net/contact/) (NOTE: *Not* for support, [create an issue for that](https://github.com/walle89/SwedbankJson/issues)).

## Personal code - Discontinued
Swedbank discontinued personal code authentication method in February 2016.
This authentication type allowed to sign in with personal identity number or corporate identity number as username and a password to show bank statements.
