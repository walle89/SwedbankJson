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

class SecurityToken extends AbstractAuth
{

    /**
     * @var int Inlogging personnummer
     */
    private $_username;

    /**
     * @var int
     */
    private $_challengeResponse;

    /**
     * @var int
     */
    private $_challenge=null;

    /**
     * @var bool
     */
    private $_useOneTimePassword;

    /**
     * Grundläggande upgifter
     *
     * @param string|array $bankApp     ID för vilken bank som ska anropas, eller array med appdata uppgifter.
     * @param int $username             Personnummer för inlogging till internetbanken
     * @param int $challengeResponse    Personlig kod för inlogging till internetbanken
     * @param bool $debug               Sätt true för att göra felsökning, annars false eller null
     *
     * @throws \Exception
     * @throws \SwedbankJson\UserException
     */
    public function __construct($bankApp, $username, $challengeResponse = 0, $debug = false)
    {
        $this->setAppData((!is_array($bankApp)) ? AppData::bankAppId($bankApp) : $bankApp);
        $this->_username = $username;
        $this->setchallengeResponse($challengeResponse);
        $this->_debug = (bool)$debug;
        $this->setAuthorizationKey();
    }

    /**
     * @return object
     * @throws Exception
     */
    public function getchallenge()
    {
        if(empty($this->_challenge))
        {
            $data_string = json_encode(['useEasyLogin' => false, 'generateEasyLoginId' => false, 'userId' => $this->_username,]);
            $output = $this->postRequest('identification/securitytoken/challenge', $data_string);

            if (!isset($output->links->next->uri))
                throw new Exception('Inlogging misslyckades. Kontrollera användarnamn och authorization-nyckel.', 10);

            $this->_challenge           = (int)$output->challenge;
            $this->_useOneTimePassword  = (bool)$output->useOneTimePassword;
        }

        return $this->_challenge;
    }

    /**
     * Inlogging
     * Loggar in med personummer och personig kod för att få reda på bankID och den tillfälliga profil-id:t
     *
     * @param int $challengeResponse
     * @return bool
     * @throws Exception
     */
    public function login($challengeResponse = 0)
    {
        if(!empty($challengeResponse))
            $this->setchallengeResponse($challengeResponse);

        if(is_null($this->_challenge))
            $this->getchallenge();

        if(empty($this->_challengeResponse))
            throw new UserException('Säkerhetsdosans kod saknas, vänligen sätt den innan inlogging.',11);

        $data_string = json_encode(['response' => (string)$this->_challengeResponse,]);
        $output = $this->postRequest('identification/securitytoken', $data_string);

        if(!isset($output->links->next->uri))
        {
            $code       = ($this->isUseOneTimePassword()) ? 'engångslösenord' : 'responskod';
            $errorCode  = ($this->isUseOneTimePassword()) ? 12 : 13;

            throw new Exception(sprintf('Kan ej logga in! Beror troligen på ogiltig eller föråldrad %s.',$code), $errorCode);
        }

        return true;
    }

    /**
     * @param int $challengeResponse
     */
    public function setchallengeResponse($challengeResponse)
    {
        $this->_challengeResponse = $challengeResponse;
    }

    /**
     * @return boolean
     */
    public function isUseOneTimePassword()
    {
        return $this->_useOneTimePassword;
    }
}
