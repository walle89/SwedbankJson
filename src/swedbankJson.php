<?php
/**
 * Wraper för Swedbanks stänga mobilapps API
 *
 * @package SwedbankJSON
 * @author  Eric Wallmander
 *          Date: 2012-01-01
 *          Time: 21:36
 */
namespace walle89\SwedbankJson;

use Exception;
use Rhumsaa\Uuid\Uuid;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

/**
 * Class SwedbankJson
 */
class SwedbankJson
{
    /**
     * Bas-url för API-anrop
     */
    const baseUri = 'https://auth.api.swedbank.se/TDE_DAP_Portal_REST_WEB/api/v1/';

    /**
     * @var int Inlogging personnummer
     */
    private $_username;

    /**
     * @var string Personlig kod.
     */
    private $_password;

    /**
     * @var string  AppID som skapas av Swedbank
     */
    private $_appID;

    /**
     * @var string  User-agent för appen
     */
    private $_useragent;

    /**
     * @var string Auth-nyckel mot Swedbank
     */
    private $_authorization;

    /**
     * @var resource CURL-resurs
     */
    private $_client;

    /**
     * @var string Sökväg för cookiejarl
     */
    private $_ckfile;

    /**
     * @var string Profiltyp
     */
    private $_profileType;

    /**
     * Grundläggande upgifter
     *
     * @param int    $username      Personnummer för inlogging till internetbanken
     * @param string $password      Personlig kod för inlogging till internetbanken
     * @param array  $appdata       En array med nödvändig datar för API:et
     * @param bool   $debug         En array med nödvändig datar för API:et
     * @param string $ckfile        Sökväg till mapp där cookiejar kan temporärt spara
     *
     * @throws $appdata om argumentet $appdata inte är av typen 'array' eller inte har rätt index och värden
     */
    public function __construct($username, $password, $appdata, $debug=false, $ckfile = './temp/')
    {
        $this->_username      = $username;
        $this->_password      = $password;
        $this->setAppData($appdata);
        $this->_ckfile        = tempnam($ckfile, 'CURLCOOKIE');
        $this->setAuthorizationKey();
        $this->_debug         = (bool)$debug;
    }

    /**
     * Utlogging från API:et.
     *
     * @see self::terminate()
     */
    public function __destruct()
    {
        $this->terminate();
    }

    /**
     * @param string $key Sätta en egen AuthorizationKey
     */
    public function setAuthorizationKey($key = '')
    {
        $this->_authorization = (empty($key)) ? $this->genAuthorizationKey() : $key;
    }

    /**
     * Generera auth-nyckel för att kunna komunicera med Swedbanks servrar
     *
     * @return string en slumpad auth-nyckel
     */
    public function genAuthorizationKey()
    {
        return base64_encode($this->_appID . ':' . strtoupper(Uuid::uuid4()));
    }

    /**
     * Inlogging
     * Loggar in med personummer och personig kod för att få reda på bankID och den tillfälliga profil-id:t
     *
     * @return bool         True om inloggingen lyckades
     * @throws Exception    Fel vid inloggen
     */
    private function login()
    {
        $data_string = json_encode(array('useEasyLogin' => false, 'password' => $this->_password, 'generateEasyLoginId' => false, 'userId' => $this->_username,));
        $output = $this->postRequest('identification/personalcode', $data_string, true);

        if ($output->generateEasyLoginId)
            throw new Exception('Byte av personlig kod krävs av banken. Var god rätta till detta genom att logga in på internetbanken.', 11);
        elseif (!isset($output->links->next->uri))
            throw new Exception('Inlogging misslyckades. Kontrollera användarnman, lösenord och authorization-nyckel.', 10);

        return true;
    }

    /**
     * Profilinfomation
     * Få tillgång till BankID och ID.
     *
     * @return array        JSON-avkodad data om profilen
     * @throws Exception    Fel med  anrop mot API:et
     */
    public function profileList()
    {
        if (empty($this->_client))
            $this->login();

        $output = $this->getRequest('profile/');

        if (!isset($output->hasSwedbankProfile))
            throw new Exception('Något med fel med profilsidan.', 20);

        if (!isset($output->banks[0]->bankId)) {
            if (!$output->hasSwedbankProfile AND $output->hasSavingsbankProfile)
                throw new UserException('Du är inte kund i Swedbank.', 21);

            elseif ($output->hasSwedbankProfile AND !$output->hasSavingsbankProfile)
                throw new UserException('Du är inte kund i Sparbanken.', 22);

            else
                throw new Exception('Profilsidan innerhåller inga bankkonton.', 23);
        }
        return $output->banks[0];
    }

    /**
     * Listar alla bankkonton som finns tillgängliga
     *
     * @param string $profileID     ProfilID
     * @return object               Lista på alla konton
     * @throws \Exception           Något med API-anropet gör att kontorna inte listas
     */
    public function accountList($profileID='')
    {
        $this->selectProfile($profileID);

        $output = $this->getRequest('engagement/overview');

        if (!isset($output->transactionAccounts))
            throw new Exception('Bankkonton kunde inte listas.', 30);

        return $output;
    }

    /**
     * Listar investeringssparande som finns tillgängliga
     *
     * @param string $profileID ProfilID
     * @return object           Lista på alla Investeringssparkonton
     * @throws \Exception       Något med API-anropet gör att kontorna inte listas
     */
    public function portfolioList($profileID='')
    {
        $this->selectProfile($profileID);

        $output = $this->getRequest('portfolio/holdings');

        if (!isset($output->savingsAccounts))
            throw new Exception('Investeringssparkonton kunde inte listas.', 40);

        return $output;
    }

    /**
     * Väjer profil
     *
     * @param $profileID
     * @throws UserException
     * @throws \Exception
     */
    private function selectProfile($profileID)
    {
        // Om profil inte är definerad, hämta standardprofil
        if (empty($profileID)) {
            $profiles = $this->profileList();
            $profileData = $profiles->{$this->_profileType}; // Väljer privat- eller företagskonto beroende på angiven useragent

            $profileID = (isset($profileData->id)) ? $profileData->id : $profileData[0]->id;
        }

        // Väljer profil
        $this->postRequest('profile/' . $profileID);
    }

    /**
     * Visar kontodetaljer och transaktioner för konto
     *
     * @param $accoutID             string  Unik och slumpvis konto-id från Swedbank API
     * @param $transactionsPerPage  int     Antal transaktioner som listas "per sida". Måste vara ett heltal större eller lika med 1.
     * @param $page                 int     Aktuell sida. Måste vara ett heltal större eller lika med 1. $transactionsPerPage måste anges.
     *
     * @return object           Avkodad JSON med kontinformationn
     * @throws Exception        AccoutID inte stämmer
     */
    public function accountDetails($accoutID, $transactionsPerPage = 0, $page = 1)
    {
        $query = array();
        if ($transactionsPerPage > 0 AND $page >= 1)
            $query = array('transactionsPerPage' => (int)$transactionsPerPage, 'page' => (int)$page,);

        $output = $this->getRequest('engagement/transactions/' . $accoutID, $query);

        if (!isset($output->transactions))
            throw new Exception('AccountID stämmer inte', 50);

        return $output;
    }

    /**
     * Loggar ut från API:et
     */
    public function terminate()
    {
        return $this->putRequest('identification/logout');
    }

    /**
     * @param $appdata
     * @throws \Exception
     */
    private function setAppData(array $appdata)
    {
        if(!is_array($appdata) OR empty($appdata['appID']) OR empty($appdata['useragent']))
            throw new Exception('Fel inmatning av AppData!');

        $this->_appID       = $appdata['appID'];
        $this->_useragent   = $appdata['useragent'];
        $this->_profileType = (strpos($this->_useragent, 'Corporate')) ? 'corporateProfiles' : 'privateProfile'; // För standardprofil
    }

    /**
     * Skickar GET-förfrågan
     *
     * @param string $apiRequest   Typ av anrop mot API:et
     * @param array  $query         Fråga för GET-anrop
     *
     * @return object    JSON-avkodad information från API:et
     */
    private function getRequest($apiRequest, $query = array())
    {
        $request = $this->createRequest('get', $apiRequest, $query);
        $response = $this->_client->send($request);

        return $response->json(['object' => true]);
    }

    /**
     * Skickar POST-förfrågan
     *
     * @param string $apiRequest    Typ av anrop mot API:et
     * @param string $data_string   Data som ska skickas i strängformat
     *
     * @return object    JSON-avkodad information från API:et
     */
    private function postRequest($apiRequest, $data_string = '')
    {
        $request = $this->createRequest('post', $apiRequest);

        if(!empty($data_string))
        {
            $request->addHeader('Content-Type', 'application/json; charset=UTF-8');
            $request->setBody(Stream::factory($data_string));
        }

        $response = $this->_client->send($request);
        return $response->json(['object' => true]);
    }

    /**
     * Skickar PUT-förfrågan
     *
     * @param string $apiRequest Typ av anrop mot API:et
     *
     * @return object    Avkodad JSON-data från API:et
     */
    private function putRequest($apiRequest)
    {
        $request = $this->createRequest('put', $apiRequest);
        $response = $this->_client->send($request);

        return $response->json(['object' => true]);
    }

    /**
     * Gemensam hantering av HTTP requests
     *
     * @param string    $method
     * @param string    $apiRequest Requesttyp till API
     * @param array     $query      Ev. query till URL
     * @return mixed    @see \GuzzleHttp\Client\createRequest
     */
    private function createRequest($method, $apiRequest, array $query=[])
    {
        if (empty($this->_client))
        {
            $this->_client = new Client([
                'base_url' => self::baseUri,
                'defaults' => [
                    'headers' => [
                        'Authorization' => $this->_authorization,
                        'Accept' => '*/*',
                        'Accept-Language' => 'sv-se',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Proxy-Connection' => 'keep-alive',
                        'User-Agent' => $this->_useragent,
                    ],
                    'allow_redirects' => ['max' => 10, 'referer' => true],
                    'config' => [
                        'curl' => [
                            CURLOPT_COOKIEJAR => $this->_ckfile,
                            CURLOPT_COOKIEFILE => $this->_ckfile,
                        ],
                    ],
                ],
                'debug' => $this->_debug,
            ]);
        }

        $dsidStr = ['dsid' => $this->dsid()];

        $httpQuery = array_merge($query, $dsidStr);

        return $this->_client->createRequest($method, $apiRequest, [
            'cookies'   => $dsidStr,
            'query'     => $httpQuery,
        ]);
    }

    /**
     * Generering av dsid
     * Slumpar 8 tecken som måste skickas med i varje anrop.
     *
     * @return string   8 slumpvalda tecken
     */
    private function dsid()
    {
        // Välj 8 tecken
        $dsid = substr(sha1(mt_rand()), rand(1, 30), 8);

        // Gör 4 tecken till versaler
        $dsid = substr($dsid, 0, 4) . strtoupper(substr($dsid, 4, 4));

        return str_shuffle($dsid);
    }
}

class UserException extends Exception{}
