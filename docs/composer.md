# Composer

## Linux och OS X

Kör följande i ett terminalfönster (OS X: Öppna Applikationer > Verktygsprogram > Terminal):
```bash
curl -sS https://getcomposer.org/installer | php
```

Lägg in SwebankJson i composer.json antingen med följande kommando:
```bash
php composer.phar require walle89/swedbank-json ~0.6
```
Efter lyckad installation, ladda in autoload.php.

```php
require 'vendor/autoload.php';
```

## Windows

Se till att php.exe finns installerat och den fulla sökvägen till den (ex. C:\php\php.exe).

Kör sedan [Compoer-Setup.exe](https://getcomposer.org/doc/00-intro.md#using-the-installer) och följ instruktionerna samt se till att "Shell menus" installeras.

Skapa eller ändra composer.json med följande innehåll:
```javascript
{
    "require": {
        "walle89/swedbank-json": "~0.6"
    }
}
```

Högerklicka på composer.json och välj "Composer Install". Efter lyckad installation, ladda in autoload.php.
```php
require 'vendor/autoload.php';
```

## Uppdatering

### Linux och OS X
Kör följande kommando:
```bash
php composer.phar update
```

### Windows
Högerklicka på den katalog som innehåller composer.json, högerklicka och välj "Composter update".