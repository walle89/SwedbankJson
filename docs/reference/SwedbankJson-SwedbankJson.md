SwedbankJson\SwedbankJson
===============

Class SwedbankJson




* Class name: SwedbankJson
* Namespace: SwedbankJson





Properties
----------


### $_auth

    private object $_auth





* Visibility: **private**


### $_selectedProfileID

    private boolean $_selectedProfileID





* Visibility: **private**


Methods
-------


### __construct

    mixed SwedbankJson\SwedbankJson::__construct(\SwedbankJson\Auth\AbstractAuth $auth)

SwedbankJson constructor.



* Visibility: **public**


#### Arguments
* $auth **[SwedbankJson\Auth\AbstractAuth](SwedbankJson-Auth-AbstractAuth.md)** - &lt;p&gt;Instans en av inloggingsmetoderna&lt;/p&gt;



### profileList

    array SwedbankJson\SwedbankJson::profileList()

Profilinfomation

Få tillgång till lista av profiler och respektive tillfälliga ID-nummer. Varje privatperson och företag har egna profiler.

* Visibility: **public**




### selectProfile

    null SwedbankJson\SwedbankJson::selectProfile(string $profileID)

Väljer profil



* Visibility: **private**


#### Arguments
* $profileID **string**



### reminders

    object SwedbankJson\SwedbankJson::reminders()

Antal avvisade betalningar, osignerade betalningar, osignerade överförningar och inkommna e-fakturor



* Visibility: **public**




### baseInfo

    object SwedbankJson\SwedbankJson::baseInfo()

Lista på konton grupperade på typ



* Visibility: **public**




### accountList

    object SwedbankJson\SwedbankJson::accountList(string $profileID)

Listar alla bankkonton som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.



* Visibility: **public**


#### Arguments
* $profileID **string** - &lt;p&gt;ProfilID&lt;/p&gt;



### portfolioList

    object SwedbankJson\SwedbankJson::portfolioList(string $profileID)

Listar investeringssparande som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.



* Visibility: **public**


#### Arguments
* $profileID **string** - &lt;p&gt;ProfilID&lt;/p&gt;



### accountDetails

    object SwedbankJson\SwedbankJson::accountDetails($accoutID, $transactionsPerPage, $page)

Visar kontodetaljer och transaktioner för konto. Om inget kontoID anges väljs första kontot i listan.



* Visibility: **public**


#### Arguments
* $accoutID **mixed** - &lt;p&gt;string  Unik och slumpvis konto-id från Swedbank API&lt;/p&gt;
* $transactionsPerPage **mixed** - &lt;p&gt;int     Antal transaktioner som listas &quot;per sida&quot;. Måste vara ett heltal större eller lika med 1.&lt;/p&gt;
* $page **mixed** - &lt;p&gt;int     Aktuell sida. Måste vara ett heltal större eller lika med 1. $transactionsPerPage måste anges.&lt;/p&gt;



### registerTransfer

    object SwedbankJson\SwedbankJson::registerTransfer(float $amount, string $fromAccountId, string $recipientAccountId, string $fromAccountNote, string $recipientAccountMessage, string $transferDate, string $perodicity)

Lägg till och förbered överförning



* Visibility: **public**


#### Arguments
* $amount **float** - &lt;p&gt;Belopp att överföra&lt;/p&gt;
* $fromAccountId **string** - &lt;p&gt;ID för avsändarkonto&lt;/p&gt;
* $recipientAccountId **string** - &lt;p&gt;ID för kontomotagare&lt;/p&gt;
* $fromAccountNote **string** - &lt;p&gt;Notering av transaktion&lt;/p&gt;
* $recipientAccountMessage **string** - &lt;p&gt;Meddelande för mottagare&lt;/p&gt;
* $transferDate **string** - &lt;p&gt;Datum när överförningen ska ske i formatet YYYY-MM-DD (dagens datum och framåt). Om inget anges, görs den direkt&lt;/p&gt;
* $perodicity **string** - &lt;p&gt;Periodicitet. För möjliga möjliga valbara perioder, se &#039;perodicity&#039; från resultatet av @see baseInfo()&lt;/p&gt;



### listRegisteredTransfers

    object SwedbankJson\SwedbankJson::listRegisteredTransfers()

Översikt av ej bekräftade överförningar



* Visibility: **public**




### listConfirmedTransfers

    object SwedbankJson\SwedbankJson::listConfirmedTransfers()

Lista aktuella/framtida bekräftade överförningar

Innehåller bland annat schemalagda och periodiska överförningar

* Visibility: **public**




### deleteTransfer

    mixed SwedbankJson\SwedbankJson::deleteTransfer($transfareId)

Ta bort överförning



* Visibility: **public**


#### Arguments
* $transfareId **mixed**



### confirmTransfer

    object SwedbankJson\SwedbankJson::confirmTransfer()

Genomför transaktioner



* Visibility: **public**




### quickBalanceAccounts

    object SwedbankJson\SwedbankJson::quickBalanceAccounts(string $profileID)

Lista möjligar snabbsaldo konton.  Om ingen profil anges väljs första profilen i listan.



* Visibility: **public**


#### Arguments
* $profileID **string** - &lt;p&gt;ProfilID&lt;/p&gt;



### quickBalanceSubscription

    object SwedbankJson\SwedbankJson::quickBalanceSubscription(string $accountQuickBalanceSubID)

Aktiverar och kopplar snabbsaldo till konto.

För att kunna visa (@see quickBalance()) och avaktivera (@see quickBalanceUnsubscription()) snabbsaldo måste man
ange "subscriptionId" som finns med i resultatet. Man bör spara undan subscriptionId i en databas eller
motsvarande.

* Visibility: **public**


#### Arguments
* $accountQuickBalanceSubID **string** - &lt;p&gt;ID hämtad från @see quickBalanceAccounts(). Leta efter ID under quickbalanceSubscription&lt;/p&gt;



### quickBalance

    object SwedbankJson\SwedbankJson::quickBalance(string $quickBalanceSubscriptionId)

Hämta snabbsaldo



* Visibility: **public**


#### Arguments
* $quickBalanceSubscriptionId **string** - &lt;p&gt;SubscriptionId&lt;/p&gt;



### quickBalanceUnsubscription

    object SwedbankJson\SwedbankJson::quickBalanceUnsubscription(string $quickBalanceSubscriptionId, string $profileID)

Avaktiverar snabbsaldo för konto



* Visibility: **public**


#### Arguments
* $quickBalanceSubscriptionId **string** - &lt;p&gt;SubscriptionId&lt;/p&gt;
* $profileID **string** - &lt;p&gt;ProfileID&lt;/p&gt;



### terminate

    mixed SwedbankJson\SwedbankJson::terminate()

Loggar ut från API:et



* Visibility: **public**



