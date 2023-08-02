# SwedbankJson

Unofficial API client for the Swedbank's and Sparbanken's mobile apps in Sweden.

* Overview of your bank accounts, loans, debit and credit cards
* List account transactions
* Transfer money between accounts
* Sign in with different profiles, ideal for Swedbank Företag app users
* Activate, deactivate, and view quick balance (aka. snabbsaldo)
* No authentication required to view quick balance, ideal for unattended monitoring and automation

**Authentication methods**

* Mobile BankID
* Security token with one time code

## Security

All SwedbankJson API client traffic is TLS encrypted and strictly between your device/server that runs the code and Swedbank's servers. The client can not and will not send any
information to any third party for any reason. 

## Installation and documentation

* [Introduction and installation](INSTALL.md)
* [Authentication methods](docs/authentication.md)
* [Reference](docs/reference.md)

## Code example

List bank statements with authentication method [security token with one time code](docs/authentication.md#security-token-with-one-time-code).

```php
$auth     = new SwedbankJson\Auth\SecurityToken($appData, $username, $challengeResponse);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$accountInfo = $bankConn->accountDetails();
$bankConn->terminate(); // Sign out

echo 'Bank statements
<pre>';
print_r($accountInfo);
```

All API endpoints do not require to sign in. One example is quick balance.

```php
$auth     = new SwedbankJson\Auth\UnAuth($appData);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$quickBalance = $bankConn->quickBalance($subID);

echo 'Quick balance
<pre>';
print_r($quickBalance);
```

## FAQ

### Can I install SwedbankJson without Composer?

No, it's either recommended or supported. It's much easier to use Composer than manually download all the
dependencies. [Read more about installing with Composer](docs/composer.md).

### Is SwedbankJson compatible with Swedbank's non-swedish apps?

As far as I know, the Swedbank's API that SwedbankJson is using is unique for the Swedish market and is not used outside of Sweden.

### Why use mobile apps API instead of Swedbank Open Banking API?

In short, if you can use of Swedbank's or Sparbanken's Swedish mobile apps for Android or Ios, then you can use this library right now to e.g. login to your own account and fetch
real transaction data (aka. production access).

In order get similar production access for the Swedbank Open Banking API, the following are required:

1. Hold a PISP, AISP or similar license from a local financial regulatory authority such
   as [Finansinspektionen](https://www.fi.se/sv/betalningar/andra-betaltjanstdirektivet-psd-2/)
   (Swedish). Applying for a license [may involve fees](https://www.fi.se/en/payments/apply-for-authorisation/payment-services/).
2. Valid QSEAL and QWAC certificates.
3. Apply for production access and be approved by Swedbank.

In other words, it's a long, complex (and costly) process to get started with Open Banking API.

## Support and Feedback

This project utilize GitHub Issues for both support and feedback. Before creating a new issue, please do the following:

1. Check the documentation (see links under [Installation and documentation](#installation-and-documentation)).
2. [Search in issues](https://github.com/walle89/SwedbankJson/issues).

If you didn't find your answer, you are welcome to [create a new issue](https://github.com/walle89/SwedbankJson/issues).

## License

[MIT](LICENSE)