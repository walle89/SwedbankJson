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

use SwedbankJson\AppData;
use Exception;

/**
 * Class UnAuth
 * @package SwedbankJson\Auth
 */
class UnAuth extends AbstractAuth
{
    /**
     * Grundläggande upgifter
     *
     * @param string|array $bankApp ID för vilken bank som ska anropas, eller array med appdata uppgifter.
     * @param bool         $debug   Sätt true för att göra felsökning, annars false eller null
     *
     * @throws \Exception
     * @throws \SwedbankJson\UserException
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
