<?php
namespace SwedbankJson\Auth;

use Exception;
use SwedbankJson\AppData;

/**
 * Class UnAuth
 * @package SwedbankJson\Auth
 */
class UnAuth extends AbstractAuth
{
    /**
     * UnAuth constructor.
     *
     * @param string|array $bankApp Bank type AppID
     * @param bool         $debug   Enable debugging
     *
     * @throws Exception
     */
    public function __construct($bankApp, $debug = false)
    {
        $this->setAppData((!is_array($bankApp)) ? AppData::bankAppId($bankApp) : $bankApp);
        $this->_debug = (bool)$debug;
        $this->setAuthorizationKey();
        $this->setBaseUri('https://unauth.api.swedbank.se/TDE_DAP_Portal_REST_WEB/api/');
    }

    /**
     * Inlogging
     * Loggar in med personummer och personig kod för att få reda på bankID och den tillfälliga profil-id:t
     *
     * @return bool         True om inloggingen lyckades
     * @throws Exception    Fel vid inloggen
     */
    public function login()
    {
        $output = $this->getRequest('identification/device/');

        if ($output->status != 'OK')
            throw new Exception('Uppkoppling misslyckades, kontrollera authorization-nyckel.', 10);

        return true;
    }
}