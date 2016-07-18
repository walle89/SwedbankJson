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

Make sure that the php.exe is installed and you know the full path to it (eg. C:\php\php.exe).

Download and run [Compoer-Setup.exe](https://getcomposer.org/doc/00-intro.md#using-the-installer). Follow the instructions and make sure "Shell menus" is installed.

Create or modify composer.json with the following content:
```javascript
{
    "require": {
        "walle89/swedbank-json": "^0.6.0"
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
Right click on the directory containing composer.json, right click and select "Composter update."