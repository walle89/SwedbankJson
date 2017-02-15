# Composer
* [Introduction](#introduction)
* [Installation](#installation)
  * [1. Run Composer](#1-run-composer)
  * [2. Upload](#2-upload)
  * [3. Run example code](#3-run-example-code)
* [Updates](#updates)

## Introduction
This project uses third-party libraries like HTTP client to send API requests.
To manage these libraries, SwedbankJson is using [Composer] as a dependency manager.
If your projects don't already use Composer, it's highly recommended to start using it.

Composer makes it easy to use third-party components and libraries like [Symfony Components] and other libraries listed on [Packagist]. 
Instead downloading all dependencies (and a dependency dependencies) manually, Composer takes care of it for you.
It also helps developers with keep all dependencies up to date with new updates that includes new features, security and bug fixes.

Many modern PHP projects like [Zend Framework 3], [Symfony], [Laravel] and [Guzzle] all uses Composer.

You can read more about Composer in [this guide] or [on its official website].
 
## Installation
One of the simplest way to install with composer is run the installer local on the computer and than upload the files to the web server.
If you want to, you can run Composer directly on the web server if you have permissions to do so. 

### 1. Run Composer
First we need to download and run Composer. Follow one of the instructions on respective platform. 

#### Linux and MacOS
First open a terminal window (MacOS: Applications > Utilities > Terminal), then follow these [instructions for local installation](https://getcomposer.org/doc/00-intro.md#locally).
After you got composer.phar, insert SwedbankJson in `composer.json` with the following command:

```bash
php composer.phar require walle89/swedbank-json
```

After successful installation, it's time to [upload the files](#upload). 

#### Windows
To run Composer in Windows, you need `php.exe`. You can either [download it from php.net] or 
install PHP web development package like [WampServer]. Make sure you know the full path to php.exe, Composer may need it. 

An easy way to install Composer is to [run and follow instructions for the installer](https://getcomposer.org/doc/00-intro.md#installation-windows).   

When you have installed Composer, create or modify `composer.json` with the following content:

```javascript
{
    "require": {
        "walle89/swedbank-json": "^0.7"
    }
}
```

Right-click the composer.json and select "Composer Install". 

### 2. Upload
Now upload the generated `vendor` folder to the web server or your hosting provider. Where you place it doesn't really matter, 
the important thing is PHP have access to it. You can also upload composer.json and composer.lock if you want to, but it's not required in order to make it work with PHP.

### 3. Run example code
Now you are now ready to run one of the [example codes]. Make sure you have the right path to the vendor folder.

## Updates
Update is easy, usually all you need follow instructions below and upload the vendor folder to the server.

### Linux and MacOS
Run this command:
```bash
php composer.phar update
```

### Windows
Right click on the directory containing composer.json, right click and select "Composer update."

[Composer]: https://getcomposer.org/
[Symfony Components]: http://symfony.com/components 
[Packagist]: https://packagist.org/

[Zend Framework 3]: https://framework.zend.com/
[Symfony]: https://github.com/symfony/symfony
[Laravel]: https://laravel.com/

[Guzzle]: https://github.com/guzzle/guzzle
[this guide]: https://www.codementor.io/php/tutorial/composer-install-php-dependency-manager
[on its official website]: https://getcomposer.org/

[download it from php.net]: http://windows.php.net/download/
[WampServer]: http://www.wampserver.com/en/

[example codes]: ../INSTALL.md#example-code
