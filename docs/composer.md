# Composer
* [Introduction](#introduction)
* [Installation](#installation)
  * [Run Composer](#run-composer)
  * [Upload](#upload)
  * [Run example code](#run-example-code)
* [Updates](#updates)

## Introduction
This project uses third-party libraries like HTTP client to send API requests.
To manage these libraries, SwedbankJson is using [Composer](https://getcomposer.org/) dependency manager.

What Composer does is help developers to download dependencies to make the code work,
and makes it easy to stay up to date with new updates that includes new features and security or other bug fixes.

Composer makes it easy to use third-party components and libraries like [Symfony Components] and libraries listed on [Packagist].
Projects like [Zend Framework 3], [Symfony], [Laravel] and [Guzzle] all uses Composer.

You can read more about Composer in [this guide] or [on its official website](https://getcomposer.org/).
 
## Installation
One of the simplest way to install with composer is run the installer local on the computer and than upload the files to the web server.
If you want to, you can run Composer directly on the web server if you have permissions to do so. 

### Run Composer
First we need to download and run Composer. Follow one of the instructions on respective platform. 

#### Linux and MacOS

Run the following in a terminal window (MacOS: Applications > Utilities > Terminal):

```bash
curl -sS https://getcomposer.org/installer | php
```

Insert SwedbankJson in composer.json with the following command:

```bash
php composer.phar require walle89/swedbank-json
```

After successful installation, it's time to [upload the files](#upload). 

#### Windows

To run Composer on windows, you need php.exe. You can either [download it from php.net](http://windows.php.net/download/) or 
install PHP web development package like [WampServer](http://www.wampserver.com/en/). Make sure you know the full path to php.exe, Composer may need it. 

Download, run [Composer-Setup.exe](https://getcomposer.org/doc/00-intro.md#installation-windows) and follow the instructions.

When you have installed Composer, create or modify composer.json with the following content:

```javascript
{
    "require": {
        "walle89/swedbank-json": "^0.7"
    }
}
```

Right-click the composer.json and select "Composer Install". 

### Upload
Now upload the generated `vendor` folder to the web server or your hosting provider. Where you place it dose not really matter, 
the important thing is PHP have access to it. 

### Run example code
Now you are now ready to run one of the [example codes](../INSTALL.md#example-code). Make sure you have the right path to the vendor folder.

## Updates
Update is easy, usually all you need follow instructions below and upload the vendor folder to the server.

### Linux och MacOS
Run this command:
```bash
php composer.phar update
```

### Windows
Right click on the directory containing composer.json, right click and select "Composer update."

[Symfony Components]: http://symfony.com/components 
[Packagist]: https://packagist.org/
[Zend Framework 3]: https://framework.zend.com/
[Symfony]: https://github.com/symfony/symfony
[Laravel]: https://laravel.com/
[Guzzle]: https://github.com/guzzle/guzzle
[this guide]: https://www.codementor.io/php/tutorial/composer-install-php-dependency-manager