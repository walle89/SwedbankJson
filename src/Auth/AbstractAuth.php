<?php
/**
 * Wrapper för Swedbanks stänga API för mobilappar
 *
 * @package SwedbankJSON
 * @author  Eric Wallmander
 *          Date: 2014-02-25
 *          Time: 21:36
 */

namespace SwedbankJson\Auth;

use Exception;

use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Cookie\CookieJar;
use Rhumsaa\Uuid\Uuid;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

abstract class AbstractAuth implements AuthInterface
{
    /**
     * Bas-url för API-anrop
     */
    const baseUri = 'https://auth.api.swedbank.se/TDE_DAP_Portal_REST_WEB/api/v3/';

    /**
     * Namn för cookieJar session
     */
    const cookieJarSession = 'swedbankjson_cookiejar';

    /**
     * @var string AppID. Ett ID som finns i Swedbanks appar.
     */
    protected $_appID;

    /**
     * @var string  User agent för appen
     */
    protected $_userAgent;

    /**
     * @var string Auth-nyckel mot Swedbank
     */
    protected $_authorization;

    /**
     * @var resource CURL-resurs
     */
    protected $_client;

    /**
     * @var string Profiltyp (företag eller privatperson)
     */
    protected $_profileType;

    /**
     * @var bool Debugging
     */
    protected $_debug;

    /**
     * @var object
     */
    protected $_cookieJar;

    /**
     * @var
     */
    protected $_persistentSession = false;

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
     * Loggar ut från API:et
     */
    public function terminate()
    {
        $result = $this->putRequest('identification/logout');

        // Cleanup
        $this->_cookieJar->clear();
        $this->_cookieJar->clearSessionCookies();
        unset($this->_client);

        return $result;
    }

    /**
     * Lägger nödvändig appdata för att kommunicera med API:et. Bland annat appID för att generera nycklar.
     *
     * @param array $appdata
     * @throws \Exception       Om rätt fält inte existerar eller är tomma
     */
    protected function setAppData($appdata)
    {
        if (!is_array($appdata) OR empty($appdata['appID']) OR empty($appdata['useragent']))
            throw new Exception('Fel inmatning av AppData!');

        $this->_appID = $appdata['appID'];
        $this->_userAgent = $appdata['useragent'];
        $this->_profileType = (strpos($this->_userAgent, 'Corporate')) ? 'corporateProfiles' : 'privateProfile'; // För standardprofil
    }

    /**
     * Skickar GET-förfrågan
     *
     * @param string $apiRequest Typ av anrop mot API:et
     * @param array $query Fråga för GET-anrop
     *
     * @return object    JSON-avkodad information från API:et
     */
    public function getRequest($apiRequest, $query = [])
    {
        $request = $this->createRequest('get', $apiRequest);

        return $this->sendRequest($request, $query);
    }

    /**
     * Skickar POST-förfrågan
     *
     * @param string $apiRequest Typ av anrop mot API:et
     * @param string $data_string Data som ska skickas i strängformat
     *
     * @return object    JSON-avkodad information från API:et
     */
    public function postRequest($apiRequest, $data_string = null)
    {
        $headers = [];
        if (!is_null($data_string))
            $headers['Content-Type'] = 'application/json; charset=UTF-8';

        $request = $this->createRequest('post', $apiRequest, $headers, $data_string);

        return $this->sendRequest($request);
    }

    /**
     * Skickar PUT-förfrågan
     *
     * @param string $apiRequest Typ av anrop mot API:et
     *
     * @return object    Avkodad JSON-data från API:et
     */
    public function putRequest($apiRequest)
    {
        $request = $this->createRequest('put', $apiRequest);

        return $this->sendRequest($request);
    }

    /**
     * Skickar DELETE-förfrågan
     *
     * @param string $apiRequest Typ av anrop mot API:et
     *
     * @return object    Avkodad JSON-data från API:et
     */
    public function deleteRequest($apiRequest)
    {
        $request = $this->createRequest('delete', $apiRequest);

        return $this->sendRequest($request);
    }

    /**
     * Gemensam hantering av HTTP requests
     *
     * @param string    $method     Typ av HTTP förfrågan (ex. GET, POST)
     * @param string    $apiRequest Requesttyp till API
     * @param array     $headers    Extra HTTP headers
     * @param string    $body       Body innehåll
     * @return mixed    @see \GuzzleHttp\Client\createRequest
     */
    private function createRequest($method, $apiRequest, $headers=[], $body=null)
    {
        if (empty($this->_client))
        {
            $this->_cookieJar = ($this->_persistentSession) ?new SessionCookieJar(self::cookieJarSession, true) : new CookieJar();

            $this->_client = new Client([
                'base_uri' => self::baseUri,
                'headers' => [
                    'Authorization' => $this->_authorization,
                    'Accept' => '*/*',
                    'Accept-Language' => 'sv-se',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Connection' => 'keep-alive',
                    'Proxy-Connection' => 'keep-alive',
                    'User-Agent' => $this->_userAgent,
                ],
                'cookies' => $this->_cookieJar,
                'allow_redirects' => ['max' => 10, 'referer' => true],
                'verify' => false, // Skippar SSL-koll av Swedbanks API certifikat. Enbart för förebyggande syfte.
                'debug' => $this->_debug,
            ]);
        }

        return new Request($method, $apiRequest, $headers, $body);
    }

    /**
     * @param $request
     * @param array $query
     * @param array $options
     * @return object
     */
    private function sendRequest($request, array $query=[], array $options=[])
    {
        $dsid = $this->dsid();

        $this->_cookieJar->setCookie(new SetCookie([
            'Name'      => 'dsid',
            'Value'     => $dsid,
            'Path'      => '/',
            'Domain'    => 0,
        ]));

        $options['cookies'] = $this->_cookieJar;
        $options['query']   = array_merge($query, ['dsid' => $dsid]);

        $response = $this->_client->send($request, $options);

        return json_decode($response->getBody());
    }

    /**
     *
     */
    protected function persistentSession()
    {
        $this->_persistentSession = true;
    }

    /**
     *
     */
    public function __sleep()
    {
        return ['_appID', '_userAgent', '_authorization', '_profileType', '_debug', '_persistentSession',];
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

    /**
     * @return resource
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @return string
     */
    public function getProfileType()
    {
        return $this->_profileType;
    }
}

class UserException extends Exception{}