# SwedbankJson

Unofficial API client for the Swedbank's and Sparbanken's mobile apps in Sweden.

> [!IMPORTANT]
> **Independent project.** SwedbankJson is not affiliated with or endorsed by Swedbank AB or any savings bank operating under the Sparbanken name. All trademarks belong to their respective owners.
>
> **Responsible use.** This software is intended for education, research, and interoperability. Use it only with accounts and systems you are authorized to access, and comply with applicable laws and service terms.

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

To the best of my knowledge, the API utilized by SwedbankJson is specific to the Swedish market and is not used by banking apps outside of Sweden.

### Why use the mobile apps API instead of the official Open Banking API?

In short, if you have access to the Swedish mobile banking apps for certain major banks, this library can be used immediately to, for example, log in and fetch real transaction
data (i.e., production access).

To obtain similar production access through the official Open Banking API, the following are generally required:

1. Hold a PISP, AISP, or equivalent license from a local financial regulatory authority such
   as [Finansinspektionen](https://www.fi.se/sv/betalningar/andra-betaltjanstdirektivet-psd-2/). Applying for such a license may involve fees.
2. Valid QSEAL and QWAC certificates.
3. Apply for and receive approval for production access from the bank.

In summary, getting started with the official Open Banking API typically involves a lengthy, complex, and costly process.

## Support and Feedback

This project utilize GitHub Issues for both support and feedback. Before creating a new issue, please do the following:

1. Check the documentation (see links under [Installation and documentation](#installation-and-documentation)).
2. [Search in issues](https://github.com/walle89/SwedbankJson/issues).

If you didn't find your answer, you are welcome to [create a new issue](https://github.com/walle89/SwedbankJson/issues).

## License

[MIT](LICENSE)
