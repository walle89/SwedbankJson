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

### I'm not a Swedbank customer in Sweden, can I use this API?
No, Swedbank's API is unique for the Swedish market and is not compatible with eg. Swedbank Denmark or Swedbank Lithuania.

## Support and Feedback
This project utilize Github Issues for both support and feedback. Before creating a new issue, please do the following:

1. Check the documentation (see links under [Installation and documentation](#installation-and-documentation)).
1. [Search in issues](https://github.com/walle89/SwedbankJson/issues).

If you didn't find your answer, you are welcome to [create a new issue](https://github.com/walle89/SwedbankJson/issues).

## License
[MIT](LICENSE)

[INSTALL.md]: INSTALL.md