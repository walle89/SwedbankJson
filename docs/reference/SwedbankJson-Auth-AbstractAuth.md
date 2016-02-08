SwedbankJson\Auth\AbstractAuth
===============

Class AbstractAuth




* Class name: AbstractAuth
* Namespace: SwedbankJson\Auth
* This is an **abstract** class
* This class implements: [SwedbankJson\Auth\AuthInterface](SwedbankJson-Auth-AuthInterface.md)


Constants
----------


### authSession

    const authSession = 'swedbankjson_auth'





### cookieJarSession

    const cookieJarSession = 'swedbankjson_cookiejar'





Properties
----------


### $_baseUri

    private string $_baseUri = 'https://auth.api.swedbank.se/TDE_DAP_Portal_REST_WEB/api/'





* Visibility: **private**


### $_apiVersion

    private string $_apiVersion = 'v4'





* Visibility: **private**


### $_appID

    protected string $_appID





* Visibility: **protected**


### $_userAgent

    protected string $_userAgent





* Visibility: **protected**


### $_authorization

    protected string $_authorization





* Visibility: **protected**


### $_client

    protected resource $_client





* Visibility: **protected**


### $_profileType

    protected string $_profileType





* Visibility: **protected**


### $_debug

    protected boolean $_debug





* Visibility: **protected**


### $_cookieJar

    protected object $_cookieJar





* Visibility: **protected**


### $_persistentSession

    protected boolean $_persistentSession = false





* Visibility: **protected**


Methods
-------


### setAuthorizationKey

    mixed SwedbankJson\Auth\AbstractAuth::setAuthorizationKey(string $key)

Ange AuthorizationKey

Om ingen nyckel anges, genereras automaiskt en nyckel.

* Visibility: **public**


#### Arguments
* $key **string** - &lt;p&gt;Sätta en egen AuthorizationKey&lt;/p&gt;



### genAuthorizationKey

    string SwedbankJson\Auth\AbstractAuth::genAuthorizationKey()

Generera auth-nyckel för att kunna kommunicera med Swedbanks servrar



* Visibility: **public**




### terminate

    mixed SwedbankJson\Auth\AbstractAuth::terminate()

Loggar ut från API:et



* Visibility: **public**




### cleanup

    mixed SwedbankJson\Auth\AbstractAuth::cleanup()

Uppresning av cookiejar och sessioner



* Visibility: **private**




### setAppData

    mixed SwedbankJson\Auth\AbstractAuth::setAppData(array $appdata)

Lägger nödvändig appdata för att kommunicera med API:et. Bland annat appID för att generera nycklar.



* Visibility: **protected**


#### Arguments
* $appdata **array**



### getRequest

    object SwedbankJson\Auth\AbstractAuth::getRequest(string $apiRequest, array $query)

Skickar GET-förfrågan



* Visibility: **public**


#### Arguments
* $apiRequest **string** - &lt;p&gt;Typ av anrop mot API:et&lt;/p&gt;
* $query **array** - &lt;p&gt;Fråga för GET-anrop&lt;/p&gt;



### postRequest

    object SwedbankJson\Auth\AbstractAuth::postRequest(string $apiRequest, string $data)

Skickar POST-förfrågan



* Visibility: **public**


#### Arguments
* $apiRequest **string** - &lt;p&gt;Typ av anrop mot API:et&lt;/p&gt;
* $data **string** - &lt;p&gt;Data som ska skickas i strängformat&lt;/p&gt;



### putRequest

    object SwedbankJson\Auth\AbstractAuth::putRequest(string $apiRequest)

Skickar PUT-förfrågan



* Visibility: **public**


#### Arguments
* $apiRequest **string** - &lt;p&gt;Typ av anrop mot API:et&lt;/p&gt;



### deleteRequest

    object SwedbankJson\Auth\AbstractAuth::deleteRequest(string $apiRequest)

Skickar DELETE-förfrågan



* Visibility: **public**


#### Arguments
* $apiRequest **string** - &lt;p&gt;Typ av anrop mot API:et&lt;/p&gt;



### getProfileType

    string SwedbankJson\Auth\AbstractAuth::getProfileType()

Retunterar inställd profil



* Visibility: **public**




### getClient

    resource SwedbankJson\Auth\AbstractAuth::getClient()

Guzzle klientobjekt



* Visibility: **public**




### createRequest

    \GuzzleHttp\Psr7\Request SwedbankJson\Auth\AbstractAuth::createRequest(string $method, string $apiRequest, array $headers, string $body)

Gemensam hantering av HTTP requests



* Visibility: **private**


#### Arguments
* $method **string** - &lt;p&gt;Typ av HTTP förfrågan (ex. GET, POST)&lt;/p&gt;
* $apiRequest **string** - &lt;p&gt;Requesttyp till API&lt;/p&gt;
* $headers **array** - &lt;p&gt;Extra HTTP headers&lt;/p&gt;
* $body **string** - &lt;p&gt;Body innehåll&lt;/p&gt;



### sendRequest

    mixed SwedbankJson\Auth\AbstractAuth::sendRequest(\GuzzleHttp\Psr7\Request $request, array $query, array $options)

Skicka/verkställ HTTP request



* Visibility: **private**


#### Arguments
* $request **GuzzleHttp\Psr7\Request**
* $query **array** - &lt;p&gt;Fråga för GET-anrop&lt;/p&gt;
* $options **array** - &lt;p&gt;Guzzle konfiguration&lt;/p&gt;



### persistentSession

    mixed SwedbankJson\Auth\AbstractAuth::persistentSession()

Slår på sessions-data ska sparas mellan sessioner



* Visibility: **protected**




### saveSession

    mixed SwedbankJson\Auth\AbstractAuth::saveSession()

Sparar auth session



* Visibility: **protected**




### __sleep

    array SwedbankJson\Auth\AbstractAuth::__sleep()

För sparande av session



* Visibility: **public**




### dsid

    string SwedbankJson\Auth\AbstractAuth::dsid()

Generering av dsid
Slumpar 8 tecken som måste skickas med i varje anrop.



* Visibility: **private**




### setBaseUri

    mixed SwedbankJson\Auth\AbstractAuth::setBaseUri(string $baseUri)

Sätta en annan adress till API-server



* Visibility: **protected**


#### Arguments
* $baseUri **string** - &lt;p&gt;URI till API-server (Exlusive version)&lt;/p&gt;



### login

    boolean SwedbankJson\Auth\AuthInterface::login()

Inled inloggon



* Visibility: **public**
* This method is defined by [SwedbankJson\Auth\AuthInterface](SwedbankJson-Auth-AuthInterface.md)



