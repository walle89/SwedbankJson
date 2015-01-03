<?php
/**
 * Wrapper för Swedbanks stänga API för mobilappar
 *
 * @package SwedbankJSON
 * @author  Eric Wallmander
 *          Date: 2012-01-01
 *          Time: 21:36
 */
namespace SwedbankJson;

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
     * @var string AppID. Ett ID som finns i Swedbanks appar.
     */
    private $_appID;

    /**
     * @var string  User agent för appen
     */
    private $_userAgent;

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
     * @var string Profiltyp (företag eller privatperson)
     */
    private $_profileType;

    /**
     * @var bool Håller koll på om profil är satt
     */
    private $_selectedProfileID;

    /**
     * Grundläggande upgifter
     *
     * @param int    $username      Personnummer för inlogging till internetbanken
     * @param string $password      Personlig kod för inlogging till internetbanken
     * @param array  $appdata       En array med nödvändig data för API:et
     * @param bool   $debug         Sätt true för att göra felsökning, annars false eller null
     * @param string $ckfile        Sökväg till mapp där cookiejar kan sparas temporärt
     *
     * @throws $appdata om argumentet $appdata inte är av typen 'array' eller inte har rätt index och värden
     */
    public function __construct($username, $password, $appdata, $debug = false, $ckfile = './temp/')
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
        unlink($this->_ckfile);
    }

    /**
     * @param string $key Sätta en egen AuthorizationKey
     */
    public function setAuthorizationKey($key = '')
    {
        $this->_authorization = (empty($key)) ? $this->genAuthorizationKey() : $key;
    }

    /**
     * Generera auth-nyckel för att kunna kommunicera med Swedbanks servrar
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
        $data_string = json_encode(['useEasyLogin' => false, 'password' => $this->_password, 'generateEasyLoginId' => false, 'userId' => $this->_username,]);
        $output = $this->postRequest('identification/personalcode', $data_string, true);

        if (!empty($output->personalCodeChangeRequired))
            throw new Exception('Byte av personlig kod krävs av banken. Var god rätta till detta genom att logga in på internetbanken.', 11);

        elseif (!isset($output->links->next->uri))
            throw new Exception('Inlogging misslyckades. Kontrollera användarnman, lösenord och authorization-nyckel.', 10);

        return true;
    }

    /**
     * Profilinfomation
     *
     * Få tillgång till lista av profiler och respektive tillfälliga ID-nummer. Varje privatperson och företag har egna profiler.
     *
     * @return array        JSON-avkodad data om profilen
     * @throws Exception    Fel med anrop mot Swedbank API:et
     */
    public function profileList()
    {
        if (empty($this->_client))
            $this->login();

        $output = $this->getRequest('profile/');

        if (!isset($output->hasSwedbankProfile))
            throw new Exception('Något med profilsidan är fel.', 20);

        if (!isset($output->banks[0]->bankId)) {
            if (!$output->hasSwedbankProfile AND $output->hasSavingsbankProfile)
                throw new UserException('Kontot är inte kopplad till Swedbank. Välj ett annat BankID', 21);

            elseif ($output->hasSwedbankProfile AND !$output->hasSavingsbankProfile)
                throw new UserException('Kontot är inte kund i Sparbanken. Välj ett annat BankID', 22);

            else
                throw new Exception('Profilsidan innerhåller inga bankkonton.', 23);
        }
        return $output->banks[0];
    }

    /**
     * Väljer profil
     *
     * @param string $profileID
     * @throws Exception
     * @throws UserException
     */
    private function selectProfile($profileID = '')
    {
        // Om profil inte är definerad, hämta standardprofil
        if (empty($profileID))
        {
            // Är profileID satt? Hoppa över selectProfile
            if($this->_selectedProfileID)
                return null;

            $profiles = $this->profileList();
            $profileData = $profiles->{$this->_profileType}; // Väljer privat- eller företagskonto beroende på angiven appdata user-agent

            $profileID = (isset($profileData->id)) ? $profileData->id : $profileData[0]->id;
        }

        // Väljer profil
        $this->postRequest('profile/' . $profileID);

        $this->_selectedProfileID = $profileID;
    }

    /**
     * Antal avvisade betalningar, osignerade betalningar, osignerade överförningar och inkommna e-fakturor
     *
     * @return object       Lista med antal
     * @throws Exception
     */
    public function reminders()
    {
        $this->selectProfile();

        return $this->getRequest('message/reminders');
    }

    /**
     * Lista på konton grupperade på typ
     *
     * @return object
     * @throws Exception
     */
    public function baseInfo()
    {
        $this->selectProfile();

        return $this->getRequest('transfer/baseinfo');
    }

    /**
     * Listar alla bankkonton som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.
     *
     * @param string $profileID     ProfilID
     * @return object               Lista på alla konton
     * @throws \Exception           Något med API-anropet gör att kontorna inte listas
     */
    public function accountList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->getRequest('engagement/overview');

        if (!isset($output->transactionAccounts))
            throw new Exception('Bankkonton kunde inte listas.', 30);

        return $output;
    }

    /**
     * Listar investeringssparande som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.
     *
     * @param string $profileID ProfilID
     * @return object           Lista på alla Investeringssparkonton
     * @throws \Exception       Något med API-anropet gör att kontorna inte listas
     */
    public function portfolioList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->getRequest('portfolio/holdings');

        if (!isset($output->savingsAccounts))
            throw new Exception('Investeringssparkonton kunde inte listas.', 40);

        return $output;
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
    public function accountDetails($accoutID = '', $transactionsPerPage = 0, $page = 1)
    {
        if(empty($accoutID))
            $accoutID = $this->accountList()->transactionAccounts[0]->id;

        $query = [];
        if ($transactionsPerPage > 0 AND $page >= 1)
            $query = ['transactionsPerPage' => (int)$transactionsPerPage, 'page' => (int)$page,];

        $output = $this->getRequest('engagement/transactions/' . $accoutID, $query);

        if (!isset($output->transactions))
            throw new Exception('AccountID stämmer inte', 50);

        return $output;
    }


    /**
     * Lista möjliga snabbsaldo konton
     *
     * @param string $profileID
     * @return object
     * @throws Exception
     */
    public function quickBalanceAccounts($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->getRequest('quickbalance/accounts');

        if (!isset($output->accounts))
            throw new Exception('Snabbsaldokonton kan inte listas.', 60);

        return $output;
    }

    /**
     * Aktiverar och kopplar snabbsaldo till konto
     *
     * För att kunna visa (@see quickBalance()) och avaktivera (@see quickBalanceUnsubscription()) snabbsaldo måste man
     * ange "subscriptionId" som finns med i resultatet. Man bör spara undan subscriptionId i en databas eller
     * motsvarande.
     *
     * @param $accountQuickBalanceSubID
     * @return object
     * @throws Exception
     */
    public function quickBalanceSubscription($accountQuickBalanceSubID)
    {
        $output = $this->postRequest('quickbalance/subscription/'. $accountQuickBalanceSubID);

        if (!isset($output->subscriptionId))
            throw new Exception('Kan ej sätta prenumeration, förmodligen fel ID av "quickbalanceSubscription"', 61);

        return $output;
    }

    /**
     * Hämta snabbsaldo
     *
     * @param $quickBalanceSubscriptionId
     * @return object
     * @throws Exception
     */
    public function quickBalance($quickBalanceSubscriptionId)
    {
        $output = $this->getRequest('quickbalance/'. $quickBalanceSubscriptionId);

        if (!isset($output->balance))
            throw new Exception('Kan ej hämta snabbsaldo. Kontrollera ID', 62);

        return $output;
    }

    /**
     * Avaktiverar snabbsaldo för konto
     *
     * @param $quickBalanceSubscriptionId
     * @param string $profileID
     * @return object
     * @throws Exception
     */
    public function quickBalanceUnsubscription($quickBalanceSubscriptionId, $profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->deleteRequest('quickbalance/subscription/' . $quickBalanceSubscriptionId);

        if (!isset($output->subscriptionId))
            throw new Exception('Kan ej sätta prenumeration, förmodligen fel ID av "quickbalanceSubscription"', 63);

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
     * Lägger nödvändig appdata för att kommunicera med API:et. Bland annat appID för att generera nycklar.
     *
     * @param array $appdata
     * @throws \Exception       Om rätt fält inte existerar eller är tomma
     */
    private function setAppData(array $appdata)
    {
        if(!is_array($appdata) OR empty($appdata['appID']) OR empty($appdata['useragent']))
            throw new Exception('Fel inmatning av AppData!');

        $this->_appID       = $appdata['appID'];
        $this->_userAgent   = $appdata['useragent'];
        $this->_profileType = (strpos($this->_userAgent, 'Corporate')) ? 'corporateProfiles' : 'privateProfile'; // För standardprofil
    }

    /**
     * Skickar GET-förfrågan
     *
     * @param string $apiRequest   Typ av anrop mot API:et
     * @param array  $query         Fråga för GET-anrop
     *
     * @return object    JSON-avkodad information från API:et
     */
    private function getRequest($apiRequest, $query = [])
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
     * Skickar DELETE-förfrågan
     *
     * @param string $apiRequest Typ av anrop mot API:et
     *
     * @return object    Avkodad JSON-data från API:et
     */
    private function deleteRequest($apiRequest)
    {
        $request = $this->createRequest('delete', $apiRequest);
        $response = $this->_client->send($request);

        return $response->json(['object' => true]);
    }

    /**
     * Gemensam hantering av HTTP requests
     *
     * @param string    $method     Typ av HTTP förfrågan (ex. GET, POST)
     * @param string    $apiRequest Requesttyp till API
     * @param array     $query      Ev. query till URL
     * @return mixed    @see \GuzzleHttp\Client\createRequest
     */
    private function createRequest($method, $apiRequest, array $query = [])
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
                        'User-Agent' => $this->_userAgent,
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