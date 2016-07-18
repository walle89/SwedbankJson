# SwedbankJson

Unoffical API client for the Swedbank's and Sparbankerna's mobile apps in Sweden. With this API client, you can do the following:

* Overview of your bank accounts, lones and loans, debit and credit cards.
* List account transactions
* Transfer money between accounts
* Login with different profiles, ideal for Swedbank Företag customers.
* Activate, deactivate, and view quick balance (aka. snabbsaldo)

Supported authentication methods

* Mobile BankID
* Security Token with single-use code
* No login (used for some functionality eg. quick balance)

Traffic between Swedbank servers and the API client uses the same encryption that Swedbank apps using and without middlemen.

## Installation instructions and documentation

* [Installation and introduction](INSTALL.md)
* [Authentication methods](docs/authentication.md)
* [Reference](docs/reference.md)

## Code example
Show bank statements with authentication method [security token with one time code](docs/authentication.md#security-token-with-one-time-code).

```php
$auth     = new SwedbankJson\Auth\SecurityToken($bankApp, $username, $challengeResponse);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$accountInfo = $bankConn->accountDetails();
$bankConn->terminate(); // Sign out

echo 'Bank statements
<pre>';
print_r($accountInfo);
```

All APIs does not require login. Quick balance example.
```php
$auth     = new SwedbankJson\Auth\UnAuth($bankApp);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$quickBalance = $bankConn->quickBalance($subID);

echo 'Quick balance
<pre>';
print_r($quickBalance);
```

## FAQ

### How do I install this?
You can read about it in [INSTALL.md].

### Can I install it without Composer?
Short awser: No.
Longer awnser: You can, but it's either recommended or supported. It's much easier to do with Composer. [Read more about installing with Composer](docs/composer.md).

### I'm not Swedbank customer in Sweden, can I use this API?
No, Swedbank's API is unique for the Swedish market and is not compatible with eg. Swedbank Denmark or Swedbank Lithuania.

## Feedback, questions, bugs, etc.
Please search for similar issues before [creating a new issue](https://github.com/walle89/SwedbankJson/issues). It's most your question have alredy been awnsered or it's a know bug or issue. Also check the documentation.

Github issues is the only way to get support.

## Licens
[MIT](LICENSE)

[INSTALL.md]: INSTALL.md