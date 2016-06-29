<?php
/**
 * Wrapper för Swedbanks stänga API för mobilappar
 *
 * @package SwedbankJson
 * @author  Eric Wallmander
 *          Date: 2014-02-25
 *          Time: 21:36
 */

namespace SwedbankJson\Auth;

use SwedbankJson\AppData;
use Exception;
use SwedbankJson\Exception\UserException;

/**
 * Class SecurityToken
 * @package SwedbankJson\Auth
 */
class SecurityToken extends AbstractAuth
{

    /**
     * @var int Inlogging personnummer
     */
    private $_username;

    /**
     * @var int Engångskod eller svarskod
     */
    private $_challengeResponse;

    /**
     * @var int Kontrolkod från APIet
     */
    private $_challenge = null;

    /**
     * @var bool Om det är engångskod eller ej
     */
    private $_useOneTimePassword;

    /**
     * SecurityToken constructor.
     *
     * @param string|array $bankApp           Banktyp för vilken bankapp som ska användas, eller array med appdata uppgifter.
     * @param int          $username          Personnummer för inlogging till internetbanken
     * @param int          $challengeResponse Engångskod för inlogging till internetbanken
     * @param bool         $debug             Sätt true för att göra felsökning, annars false eller null
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
     * Hämta kontrollkod från APIet
     *
     * @return string Kontrollkoden
     * @throws Exception
     */
    public function getchallenge()
    {
        if (empty($this->_challenge))
        {
            $output = $this->postRequest(
                'identification/securitytoken/challenge',
                [
                    'useEasyLogin'        => false,
                    'generateEasyLoginId' => false,
                    'userId'              => $this->_username,
                ]
            );

            if (!isset($output->links->next->uri))
                throw new Exception('Inlogging misslyckades. Kontrollera användarnamn och authorization-nyckel.', 10);

            $this->_challenge          = $output->challenge;
            $this->_useOneTimePassword = (bool)$output->useOneTimePassword;
        }

        return $this->_challenge;
    }

    /**
     * Inlogging
     *
     * Loggar in med personummer och personig kod för att få reda på bankID och den tillfälliga profil-id:t
     *
     * @param int $challengeResponse Engångskod eller svarskod
     *
     * @return bool Om inloggingen lyckades eller ej
     * @throws Exception
     */
    public function login($challengeResponse = 0)
    {
        if (!empty($challengeResponse))
            $this->setchallengeResponse($challengeResponse);

        if (is_null($this->_challenge))
            $this->getchallenge();

        if (empty($this->_challengeResponse))
            throw new UserException('Säkerhetsdosans kod saknas, vänligen sätt den innan inlogging.', 11);

        $output = $this->postRequest('identification/securitytoken', ['response' => (string)$this->_challengeResponse,]);

        if (!isset($output->links->next->uri))
        {
            $code      = ($this->isUseOneTimePassword()) ? 'engångslösenord' : 'responskod';
            $errorCode = ($this->isUseOneTimePassword()) ? 12 : 13;

            throw new Exception(sprintf('Kan ej logga in! Beror troligen på ogiltig eller föråldrad %s.', $code), $errorCode);
        }

        return true;
    }

    /**
     * Sätt engångskod eller svarskod
     *
     * @param string $challengeResponse Engångskod eller svarskod
     */
    public function setchallengeResponse($challengeResponse)
    {
        $this->_challengeResponse = $challengeResponse;
    }

    /**
     * Om det är en engångskod eller ej
     *
     * @return boolean Om det är en engångskod eller ej
     */
    public function isUseOneTimePassword()
    {
        return $this->_useOneTimePassword;
    }
}
