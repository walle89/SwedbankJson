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

class MobileBankID extends AbstractAuth
{
    /**
     * @var int Inlogging personnummer
     */
    private $_username;

    /**
     * @var bool
     */
    private $_verified = false;

    /**
     * Grundläggande upgifter
     *
     * @param string|array $bankApp ID för vilken bank som ska anropas, eller array med appdata uppgifter.
     * @param int $username Personnummer för inlogging till internetbanken
     * @param bool $debug Sätt true för att göra felsökning, annars false eller null
     *
     * @throws \Exception
     * @throws \SwedbankJson\UserException
     */
    public function __construct($bankApp, $username, $debug = false)
    {
        $this->setAppData((!is_array($bankApp)) ? AppData::bankAppId($bankApp) : $bankApp);
        $this->_username = $username;
        $this->_debug = (bool)$debug;
        $this->setAuthorizationKey();
        $this->persistentSession();
    }


    /**
     *
     *
     * @return bool
     * @throws Exception
     */
    public function initAuth()
    {
        if ($this->_verified)
            return true;

        $data_string = json_encode(['useEasyLogin' => false, 'generateEasyLoginId' => false, 'userId' => $this->_username,]);
        $output = $this->postRequest('identification/bankid/mobile', $data_string);

        if ($output->status != 'USER_SIGN')
            throw new Exception('Kan inte koppla bankID.', 10);

        return true;
    }


    /**
     *
     *
     * @return bool
     * @throws Exception
     */
    public function verify()
    {
        if ($this->_verified)
            return true;

        $output = $this->getRequest('identification/bankid/mobile/verify');

        if (empty($output->status))
            throw new Exception('BankID är inte verifierad.', 11);

        // Om status är "COMPLETE", är det lyckad inlogging
        $this->_verified = ($output->status == 'COMPLETE');

        return $this->_verified;
    }

    /**
     *
     *
     * @param int $intervall
     * @param int $maxTries
     * @return bool
     * @throws Exception
     */
    public function autoVerify($intervall = 5, $maxTries = 12)
    {
        for ($i = 0; $i < $maxTries; $i++) {
            if ($this->verify())
                return true;

            sleep($intervall);
        }

        return false;
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
        if (!$this->_verified)
            throw new Exception('BankID är inte verifierad.', 12);

        return true;
    }
}
