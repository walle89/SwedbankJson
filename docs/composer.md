# Composer

## Linux and MacOS

Run the following in a terminal window (MacOS: Applications > Utilities > Terminal):

```bash
curl -sS https://getcomposer.org/installer | php
```

Insert SwedbankJson in composer.json with the following command:

```bash
php composer.phar require walle89/swedbank-json
```

After successful installation, load autoload.php.

```php
<?php
require 'vendor/autoload.php';
```

## Windows

To run Composer on windows, you need php.exe. You can either [download it from php.net](http://windows.php.net/download/) or 
install PHP web development package like [WampServer](http://www.wampserver.com/en/). Make sure you know the full path to php.exe, Composer will likely need it. 

Download, run [Composer-Setup.exe](https://getcomposer.org/doc/00-intro.md#installation-windows) and follow the instructions.

When you have installed Composer, create or modify composer.json with the following content:

```javascript
{
    "require": {
        "walle89/swedbank-json": "^0.7"
    }
}
```

Right-click the composer.json and select "Composer Install". After successful installation, load autoload.php.

```php
<?php
require 'vendor/autoload.php';
```

## Updates

### Linux och MacOS
Run this command:
```bash
php composer.phar update
```

### Windows
Right click on the directory containing composer.json, right click and select "Composer update."