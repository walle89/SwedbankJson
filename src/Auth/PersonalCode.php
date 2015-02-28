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

class PersonalCode extends AbstractAuth
{
    /**
     * @var int Inlogging personnummer
     */
    private $_username;

    /**
     * @var string Personlig kod.
     */
    private $_password;

    /**
     * Grundläggande upgifter
     *
     * @param string|array      $bankID     ID för vilken bank som ska anropas, eller array med appdata uppgifter.
     * @param int               $username   Personnummer för inlogging till internetbanken
     * @param string            $password   Personlig kod för inlogging till internetbanken
     * @param bool              $debug      Sätt true för att göra felsökning, annars false eller null
     * @param string            $ckfile     Sökväg till mapp där cookiejar kan sparas temporärt
     *
     *
     * @throws \Exception
     * @throws \SwedbankJson\UserException
     */
    public function __construct($bankID, $username, $password, $debug = false, $ckfile = './temp/')
    {
        $this->setAppData((!is_array($bankID)) ? AppData::bankAppId($bankID) : $bankID);
        $this->_username    = $username;
        $this->_password    = $password;
        $this->_debug       = (bool)$debug;
        $this->_ckfile      = tempnam($ckfile, 'CURLCOOKIE');
        $this->setAuthorizationKey();
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
        $data_string = json_encode(['useEasyLogin' => false, 'password' => $this->_password, 'generateEasyLoginId' => false, 'userId' => $this->_username,]);
        $output = $this->postRequest('identification/personalcode', $data_string);

        if (!empty($output->personalCodeChangeRequired))
            throw new Exception('Byte av personlig kod krävs av banken. Var god rätta till detta genom att logga in på internetbanken.', 11);

        elseif (!isset($output->links->next->uri))
            throw new Exception('Inlogging misslyckades. Kontrollera användarnman, lösenord och authorization-nyckel.', 10);

        return true;
    }
}
