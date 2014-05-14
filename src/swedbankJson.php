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
     * @var resource CURL-resurs
     */
    private $_ch;

    /**
     * @var string Sökväg för cookiejarl
     */
    private $_ckfile;

    /**‚
     * @var string Auth-nyckel mot Swedbank
     */
    private $_authorization;

    /**
     * @var array Gemensamma headrs i alla anrop mot API:et
     */
    private $_commonHttpHeaders;

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
     * @var string Profiltyp
     */
    private $_profileType;

    /**
     * Grundläggande upgifter
     *
     * @param int    $username      Personnummer för inlogging till internetbanken
     * @param string $password      Personlig kod för inlogging till internetbanken
     * @param array  $appdata       En array med nödvändig datar för API:et
     * @param string $ckfile        Sökväg till mapp där cookiejar kan temporärt spara
     *
     * @throws $appdata om argumentet $appdata inte är av typen 'array' eller inte har rätt index och värden
     */
    public function __construct($username, $password, $appdata, $ckfile = './temp/')
    {
        $this->_username      = $username;
        $this->_password      = $password;
        $this->setAppData($appdata);
        $this->_ckfile        = tempnam($ckfile, 'CURLCOOKIE');
        $this->setAuthorizationKey();
    }

    /**
     * Utlogging från API:et.
     * @see self::terminate()
     */
    public function __destruct()
    {
        $this->terminate();
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
        if (empty($ch))
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
        return $output->banks;
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
     * Visar kontodetaljer och transaktioner för konto
     *
     * @param $accoutID             string  Unika och slumpade konto-id från Swedbank API
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

        $output = $this->getRequest('engagement/transactions/' . $accoutID, null, $query);

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
     * @param $appdata
     * @throws \Exception
     */
    private function setAppData(array $appdata)
    {
        if(!is_array($appdata) OR empty($appdata['appID']) OR empty($appdata['useragent']))
            throw new Exception('Fel inmatning av AppData!');

        $this->_appID       = $appdata['appID'];
        $this->_useragent   = $appdata['useragent'];
        $this->_profileType = (strpos($this->_useragent, 'Corporate')) ? 'corporateProfiles' : 'privateProfile';
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
        $data_string = json_encode(array('useEasyLogin' => false, 'password' => $this->_password, 'generateEasyLoginId' => false, 'userId' => $this->_username,  ));
        $output      = $this->postRequest('identification/personalcode', $data_string, true);

        if($output->generateEasyLoginId)
            throw new Exception('Byte av personlig kod krävs av banken. Var god rätta till detta genom att logga in på internetbanken.', 11);
        elseif (!isset($output->links->next->uri))
            throw new Exception('Inlogging misslyckades. Kontrollera användarnman, lösenord och authorization-nyckel.', 10);

        return true;
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
        if (empty($profileID))
        {
            $profiles = $this->profileList();
            $profileData = $profiles->banks[0]->{$this->_profileType}; // Väljer privat- eller företagskonto beroende på angiven useragent

            $profileID = (isset($profileData->id)) ? $profileData->id : $profileData[0]->id;
        }

        // Väljer profil
        $this->postRequest('profile/' . $profileID);
    }

    /**
     * Skickar GET-förfrågan
     *
     * @param string $requestType   Typ av anrop mot API:et
     * @param string $baseURL       Bas-url, om inget anges körs self::baseUri @see self::baseUri
     * @param array  $query         Fråga för GET-anrop
     * @param bool   $debug         True om debug-data ska retuneras
     *
     * @return object    JSON-avkodad information från API:et eller debugdata om $debug är satt till true.
     */
    private function getRequest($requestType, $baseURL = self::baseUri, $query = array(), $debug = false)
    {
        if (empty($this->_ch))
            $this->initCurl();

        if (is_null($baseURL))
            $baseURL = self::baseUri;

        $dsid = $this->dsid();

        $httpQuery = http_build_query(array_merge($query, array( 'dsid' => $dsid )));

        curl_setopt($this->_ch, CURLOPT_URL, $baseURL . $requestType . '?' . $httpQuery);
        curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->requestHeaders($dsid));
        if ($debug)
        {
            curl_setopt($this->_ch, CURLOPT_HEADER, 1);
            curl_setopt($this->_ch, CURLINFO_HEADER_OUT, 1);
        }

        $data = curl_exec($this->_ch);

        if ($debug)
        {
            $headers = curl_getinfo($this->_ch, CURLINFO_HEADER_OUT);

            return array( 'request' => $headers, 'response' => $data );
        }
        else
            return json_decode($data);
    }

    /**
     * Skickar POST-förfrågan
     *
     * @param string $requestType   Typ av anrop mot API:et
     * @param string $data_string   Data som ska skickas i strängformat och enligt http_build_query() @see http_build_query()
     * @param bool   $debug         True om debug-data ska retuneras
     *
     * @return object    JSON-avkodad information från API:et eller debugdata om $debug är satt till true.
     */
    private function postRequest($requestType, $data_string = '', $debug = false)
    {
        if (empty($this->_ch))
            $this->initCurl();

        $dsid = $this->dsid();
        curl_setopt($this->_ch, CURLOPT_URL, self::baseUri . $requestType . '?dsid=' . $dsid);
        curl_setopt($this->_ch, CURLOPT_POST, true);
        if (!empty($data_string))
        {
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->requestHeaders($dsid, $data_string));
        }
        else
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->requestHeaders($dsid));

        if ($debug)
        {
            curl_setopt($this->_ch, CURLOPT_HEADER, 1);
            curl_setopt($this->_ch, CURLINFO_HEADER_OUT, 1);
            curl_setopt($this->_ch, CURLOPT_NOPROGRESS, 0);
        }

        $data = curl_exec($this->_ch);

        if ($debug)
        {
            $headers = curl_getinfo($this->_ch, CURLINFO_HEADER_OUT);

            return array( 'request' => $headers.$data_string, 'response' => $data );
        }
        else
            return json_decode($data);
    }

    /**
     * Skickar PUT-förfrågan
     *
     * @param string $requestType     Typ av anrop mot API:et
     * @param bool   $debug           Sätt till true för att via debug-information
     *
     * @return object    Avkodad JSON-data från API:et eller debuginformation om $debug är satt till true.
     */
    private function putRequest($requestType, $debug = false)
    {
        if (empty($this->_ch))
            $this->initCurl();

        $dsid = $this->dsid();
        curl_setopt($this->_ch, CURLOPT_URL, self::baseUri . $requestType . '?dsid=' . $dsid);
        curl_setopt($this->_ch, CURLOPT_PUT, true);
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->requestHeaders($dsid));
        if ($debug)
        {
            curl_setopt($this->_ch, CURLOPT_HEADER, 1);
            curl_setopt($this->_ch, CURLINFO_HEADER_OUT, 1);
        }

        $data = curl_exec($this->_ch);

        if ($debug)
        {
            $headers = curl_getinfo($this->_ch, CURLINFO_HEADER_OUT);

            return array( 'request' => $headers, 'response' => $data );
        }
        else
            return json_decode($data);
    }

    /**
     * Iniserar CURL och gemensamma HTTP-headers
     */
    private function initCurl()
    {
        $this->_commonHttpHeaders = array(
            'Authorization: ' . $this->_authorization,
            'Accept: */*',
            'Accept-Language: sv-se',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'Proxy-Connection: keep-alive'
        );

        $this->_ch = curl_init();
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR, $this->_ckfile);
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE, $this->_ckfile);
        curl_setopt($this->_ch, CURLOPT_USERAGENT, $this->_useragent);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);
    }

    /**
     * Gemensama HTTP-headers som anapassas efter typ av anriop
     *
     * @param string $dsid          8 slumpvalda tecken @see self::dsid();
     * @param string $data_string   Data som ska skickas. Används för att räkna ut längen på texten.
     *
     * @return array    HTTP:haders för att användas i CURL-anrop.
     */
    private function requestHeaders($dsid, $data_string = '')
    {
        curl_setopt($this->_ch, CURLOPT_COOKIE, "dsid=$dsid");
        $requestHeader = $this->_commonHttpHeaders;

        if (!empty($data_string))
        {
            $requestHeader[] = 'Content-Type: application/json; charset=UTF-8';
            $requestHeader[] = 'Content-Length: ' . strlen($data_string);
        }

        return $requestHeader;
    }

    /**
     * Generering av dsid
     * Slumpar 8 tecken som måste skickas med i varje anrop. Antagligen för att API:et vill förhindra en cache skapas
     *
     * @return string   8 slumpvalda tecken
     */
    private function dsid()
    {
        $dsid = substr(sha1(mt_rand()), rand(1, 30), 8);
        $dsid = substr($dsid, 0, 4) . strtoupper(substr($dsid, 4, 4));

        return str_shuffle($dsid);
    }
}

class UserException extends Exception{}
