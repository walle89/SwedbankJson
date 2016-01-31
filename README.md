# SwedbankJson

Inofficiell wrapper för det API som används för Swedbanks och Sparbankernas mobilappar (privatperson, ungdom och företag). Det finns flertalet stöd för olika inloggningstyper:

* Mobilt BankID
* Säkerhetsdosa med engångskod
* Ingen inlogging (för vissa funktioner, tex. snabbsaldo) 

**Detta kan wrappen göra**

* Översikt av tillgängliga konton så som lönekonto, sparkonton investeringsbesparningar, lån, bankkort och kreditkort.
* Lista ett kontos samtliga transaktioner.
* Företagsinloggingar kan välja att lista konton utifrån en vald profil.
* Aktivera, avaktivera och visa snabbsaldo.
* Kommunicationen sker krypterat enbart med Swedbankds servrar utan mellanhänder.
* Autentiseringsnyckel som krävs för inlogging genereras automatiskt per session (standard) eller manuellt sätta en statisk nykel.

[Fler funktioner finns planerade](https://github.com/walle89/SwedbankJson/labels/todo).

## Kodexempel
```php
$auth     = new SwedbankJson\Auth\SecurityToken($bankApp, $username, $challengeResponse);
$bankConn = new SwedbankJson\SwedbankJson($auth);

$accountInfo = $bankConn->accountDetails();
$bankConn->terminate(); // Utlogging

echo 'Kontoutdrag
<pre>';
print_r($accountInfo);
```

## Dokumentation

* [Introduktion](docs/introduktion.md)
* [Inlogginhstyper](docs/inloggingstyper.md)
* [Refferens](docs/refferens.md)

## Uppdateringar

Främsta anledningen till uppdateringar behöver göras är att Swedbank ändrar AppID och User Agent för varje uppdatering av sina appar. AppID och User Agent används som en del av atuetensierings prosessen.
Justera versionen i composer.json och kör sedan `composer update`.

## Feedback, frågor, buggar, etc.

Skapa en [Github Issue](https://github.com/walle89/SwedbankJson/issues), men var god kontrollera att det inte finns någon annan som skapat en likande issue (sökfunktinen är din vän).

## Andra projekt med Swedbanks API
* [SwedbankSharp](https://github.com/DarkTwisterr/SwedbankSharp) av [DarkTwisterr](https://github.com/DarkTwisterr) - C# med .NET implementation.
* [Swedbank-Cli](https://github.com/spaam/swedbank-cli) av [Spaam](https://github.com/spaam) - Swedbank i terminalen. Skriven i Python.
* [SwedbankJson](https://github.com/viktorgardart/SwedbankJson) av [Viktor Gardart](https://github.com/viktorgardart) - Objective-C implementation (för iOS).

## Licens (MIT)
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.