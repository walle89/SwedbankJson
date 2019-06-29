# SwedbankJson

Unofficial API client for the Swedbank's and Sparbanken's mobile apps in Sweden.

* Overview of your bank accounts, loans, debit and credit cards.
* List account transactions
* Transfer money between accounts
* Sign in with different profiles, ideal for Swedbank Företag app users.
* Activate, deactivate, and view quick balance (aka. snabbsaldo)

**Authentication methods**

* Mobile BankID
* Security token with one time code
* No authentication (used for some functionality eg. quick balance)

Traffic between Swedbank's servers and the API client uses the same TLS encryption that Swedbank apps are using and without middlemen.

## Installation and documentation

* [Introduction and installation](INSTALL.md)
* [Authentication methods](docs/authentication.md)
* [Reference](docs/reference.md)

## Code example
List bank statements with authentication method [security token with one time code](docs/authentication.md#security-token-with-one-time-code).

```php
$auth     = new SwedbankJson\Auth\SecurityToken($bankApp, $username, $challengeResponse);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$accountInfo = $bankConn->accountDetails();
$bankConn->terminate(); // Sign out

echo 'Bank statements
<pre>';
print_r($accountInfo);
```

All APIs does not require to sign in. One example is quick balance.

```php
$auth     = new SwedbankJson\Auth\UnAuth($bankApp);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$quickBalance = $bankConn->quickBalance($subID);

echo 'Quick balance
<pre>';
print_r($quickBalance);
```

## FAQ

### Can I install it without Composer?
No, it's either recommended or supported. It's much easier to use Composer than manually download all the dependencies. [Read more about installing with Composer](docs/composer.md).

### I'm not a Swedbank customer in Sweden, can I use this library?
No, Swedbank's API is unique for the Swedish market and is not compatible with eg. Swedbank Denmark or Swedbank Lithuania.

### Why is this library not using the Swedbank Open Banking API?
Swedbank Open Banking API (and Open Banking in general) is in many aspects a fantastic initiative. Now we have an open documented standard for how a third party can fetch bank statements and initiate payment transactions on behalf of a customer. Unlike this library, Swedbank Open Banking API is supported by the bank.

However there are few reasons for why I have chose to not to use the Open Banking API. One of them is it's costly and time consuming to get the required AISP or PISP licence from a local financial supervisory authority such as [Finansinspektionen](https://www.fi.se/sv/bank/andra-betaltjanstdirektivet-psd-2/) (Swedish) in order to get access to real customer data (production access).

This library is instead using Swedbank's Mobile Apps API, the same API that's used for the Swedish Swedbank apps or Sparbanken apps for Android and Ios. There is no need for a AISP or PISP license. If you can use any of Swedbank's or Sparbanken's apps, then you can start coding using this library. Also Mobile Apps API have endpoints such as QuickBalance that's not exist in the Open Banking API.

## Support and Feedback
This project utilize Github Issues for both support and feedback. Before creating a new issue, please do the following:

1. Check the documentation (see links under [Installation and documentation](#installation-and-documentation)).
1. [Search in issues](https://github.com/walle89/SwedbankJson/issues).

If you didn't find your answer, you are welcome to [create a new issue](https://github.com/walle89/SwedbankJson/issues).

## License
[MIT](LICENSE)