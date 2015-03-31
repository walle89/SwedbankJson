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
    private $_verified=false;

    /**
     * @var bool
     */
    private $_firstRun=true;

    /**
     * Grundläggande upgifter
     *
     * @param string|array      $bankApp    ID för vilken bank som ska anropas, eller array med appdata uppgifter.
     * @param int               $username   Personnummer för inlogging till internetbanken
     * @param bool              $debug      Sätt true för att göra felsökning, annars false eller null
     * @param string            $ckfile     Sökväg till mapp där cookiejar kan sparas temporärt
     *
     * @throws \Exception
     * @throws \SwedbankJson\UserException
     */
    public function __construct($bankApp, $username, $debug = false, $ckfile = './temp/')
    {
        $this->setAppData((!is_array($bankApp)) ? AppData::bankAppId($bankApp) : $bankApp);
        $this->_username    = $username;
        $this->_debug       = (bool)$debug;
        $this->_ckfile      = tempnam($ckfile, 'CURLCOOKIE');
        $this->setAuthorizationKey();
    }

    /**
     *
     *
     * @return bool
     * @throws Exception
     */
    public function verify()
    {
        if($this->_verified)
            return true;

        $urlAddon = (!$this->_firstRun) ? '/verify' : '';
        $this->_firstRun = false;

        $output = $this->getRequest('bankid/mobile'.$urlAddon);

        if(empty($output->status))
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
    public function autoVerify($intervall=5, $maxTries=12)
    {
        for($i=0; $i< $maxTries; $i++)
        {
            if($this->verify())
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
