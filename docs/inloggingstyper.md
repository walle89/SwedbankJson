# Inloggingstyper

* [Inledning](#inledning)
* [Ingen inlogging](#ingen-inlogging)
* [Säkerhetsdosa med engångskod](#säkerhetsdosa-med-engångskod)
* [Säkerhetsdosa med kontrollnummer och svarskod](#säkerhetsdosa-med-kontrollnummer-och-svarskod)
* [Mobilt BankID](#mobilt-bankid)
* [Personlig kod](#personlig-kod-nedlagd)

## Inledning
Gemensama instälningar för kodexempelen nedan.

```php
// Inställningar
$username = 198903060000; // Person- eller organisationsnummer
$bankApp  = 'swedbank';   // Banktyp 
```

### Banktyper
Swedbank har valt att dela upp sina tjänster i flera olika mobilappar. Man behöver välja banktyp beroende på om man är kund i Swedbank eller Sparbanken samt om man är privatperson, privat eller ungdom. $bankApp har enbart stöd för exakt en banktyp i taget och ska ange som en sträng.

#### Swedbank
| $bankApp | Mobilapp |
| --- | --- |
| swedbank | Swedbank (för privatpersoner) |
| swedbank_foretag | Swedbank Företag |
| swedbank_ung | Swedbank Ung |

#### Sparbanken
| $bankApp | Mobilapp |
| --- | --- |
| sparbanken | Sparbanken (för privatpersoner) |
| sparbanken_foretag | Sparbanken Företag |
| sparbanken_ung | Sparbanken Ung |

## Ingen inlogging
Absoult den enklaste inloggingstypen som enbart kräver att man anger banktyp. Detta gör att den går att automatisera utan användarens inverkan. Dock kan denna inloggningstyp enbart användas till ett fåtal förfrågningar så som snabbsaldo.

```php
$auth     = new SwedbankJson\Auth\UnAuth($bankApp);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

## Säkerhetsdosa med engångskod
Det finns två typer av varianter för inlogging med säkerhetsdosa. Ett av dessa är engångskod, som ger ett 8-siffrig kod när man har låst upp dosan och väljer 1 när "Appli" visas.

Utgår man från inlogginsflöde i mobilappen ser den ut som följande:

**Välj säkerhetsdosa -> Fyll i engångskod från säkerhetsdosan -> Inloggad**

```php
if(empty($_POST['challengeResponse'])
{
    echo '
    <form action="" method="post">
        <p>Fyll i 8-siffrig engångskod från säkerhetsdosa</p>
        <input name="challengeResponse" type="text" />
        <button>Logga in</button>
    </form>';
    exit;
}
if(!is_numeric($_POST['challengeResponse']))
    exit('Fel indata!');

$auth     = new SwedbankJson\Auth\SecurityToken($bankApp, $username, $_POST['challengeResponse']);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

## Säkerhetsdosa med kontrollnummer och svarskod
Den andra typen av inlogginsmetod för säkerhetsdosa är kontrollnummer med svarskod. Denna metod innebär att man får en 8-siffrigt kontrollnummer som ska matas in i dosan och som svar får man ett nytt 8-siffrigt svarskod som skrivs in i antingen appen eller i internetbanken.

Utgår man från inlogginsflöde i mobilappen ser den ut som följande:

**Välj säkerhetsdosa -> Mata in kontrollnummer i dosan -> Skriv av savarskod -> Inloggad**

För närvarande finns det inte stöd i wrappern för denna typ av inlogging.

## Mobilt BankID
Inloggingsprocessen för mobilt BankID sker i flera steg som kräver att sessionen sparas mellan förfrågningarna.

```php
session_start();

// Inled inloggning
if (!isset($_SESSION['swedbankjson_auth']))
{
    $auth = new SwedbankJson\Auth\MobileBankID($bankApp, $username);
    $auth->initAuth();
    exit('Öppna BankID-appen och godkänn inloggingen. Därefter uppdatera sidan.');
}

// Verifiera inlogging
$auth = unserialize($_SESSION['swedbankjson_auth']);
if (!$auth->verify())
    exit("Du uppdaterade sidan, men inloggningen är inte godkänd i BankID-appen. Försök igen.");

// Inloggad
$bankConn = new SwedbankJson\SwedbankJson($auth);
```

Tanken med flödet med exempelkoden ovan blir som följande:

**Gå till sidan -> Öppna BankID appen och verifiera -> Uppdatera sidan -> Inloggad**

För en mer elegant lösning rekommenderas att man lägger inloggingsverifieringen i ett ajax-anrop som kontrolleras exempelvis var femte sekund och skickar vidare användaren när inloggingen är verifierad. 

## Personlig kod (nedlagd)
Swedbank hade fram till februari 2016 en inloggingstyp som innebar att det räckte med personnummer och ett kortare lösenord för att kunna logga in och exempelvis titta på transaktioner.

```php
$password = 'fakePW';
$auth     = new SwedbankJson\Auth\PersonalCode($bankApp, $username, $password);
$bankConn = new SwedbankJson\SwedbankJson($auth);
```