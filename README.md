# SwedbankJson

Wrapper för Swedbanks stängda API som används för swedbanks- och sparbakernas mobilappar. Inlogging görs med hjälp av internetbankens personliga kod (person- eller orginisationsnummer och lösenord).

Målet för projektet är att låta vem som helst använda wrappen till sina egna projekt så som budget- och ekonomisystem.

## Systemkrav

* PHP 5.3+
* Curl

## Installation

### 1. Kör git clone

```bash
git clone https://github.com/walle89/SwedbankJson.git
```

_Notering:_ Det går självfallet ladda ned som en zip-fil, men det blir enkalre att ladda ned framtida uppdateringar.

### 2. Kopiera example.sample.php till en ny fil och ändra inställningarna

### 3. Ladda upp till en webbserver med PHP

### 4. Testa genom att gå in på den nyligen skapade filen

## Användning och exempel

Se [example.sample.php](https://github.com/walle89/SwedbankJson/blob/master/example.sample.php).

## Dokumentation

Finns i form av PHPDoc kommentarer i filerna. Utförligare dokumentation med API-
anrop finns på todo-listan.

## Uppdateringar

```bash
git pull
```

Det är främst [appData.php](https://github.com/walle89/SwedbankJson/blob/master/appData.php) som kan komma att ändras i takt med Swedbank uppdaterar sina appar och därmed appid:n och useragents.

## Feedback, frågor, buggar, etc.

Skapa en [Github Issue](https://github.com/walle89/SwedbankJson/issues).

## Licens (MIT)
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.