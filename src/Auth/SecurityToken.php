<?php
namespace SwedbankJson\Auth;

use Exception;
use SwedbankJson\AppData;
use SwedbankJson\Exception\UserException;

/**
 * Class SecurityToken
 * @package SwedbankJson\Auth
 */
class SecurityToken extends AbstractAuth
{
    /** @var string Username. Personal identity number or corporate identity number (personnummer/organisationsnummer) */
    private $_username;

    /** @var string One time code or response code */
    private $_challengeResponse;

    /** @var string Control number */
    private $_challenge;

    /** @var bool Security token type. */
    private $_useOneTimePassword;

    /**
     * SecurityToken constructor.
     *
     * @param string|array $bankApp           Bank type AppID
     * @param string       $username          Personal identity number or corporate identity number (personnummer/organisationsnummer)
     * @param int          $challengeResponse One time code or response code from security token
     * @param bool         $debug             Enable debugging
     *
     * @throws Exception
     */
    public function __construct($bankApp, $username, $challengeResponse = 0, $debug = false)
    {
        $this->setAppData((!is_array($bankApp)) ? AppData::bankAppId($bankApp) : $bankApp);
        $this->_username = $username;
        $this->setChallengeResponse($challengeResponse);
        $this->_debug = (bool)$debug;
        $this->setAuthorizationKey();
    }

    /**
     * Fetch control number
     *
     * For security token with control number and response code.
     *
     * @return string|null Control number. Null if the security token only requires a generated one time code.
     * @throws Exception
     */
    public function getChallenge()
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
                throw new Exception('Cannot fetch control number. Check if the username is correct.', 10);

            $this->_challenge          = $output->challenge;
            $this->_useOneTimePassword = (bool)$output->useOneTimePassword;
        }

        return $this->_challenge;
    }

    /**
     * Sign in
     *
     * It is a good idea to check security token type before sign in with getChallenge() and isUseOneTimePassword().
     *
     * @param string $challengeResponse One time code or response code from security token
     *
     * @return bool      True on successful sign in
     * @throws Exception
     */
    public function login($challengeResponse = '')
    {
        if (!empty($challengeResponse))
            $this->setChallengeResponse($challengeResponse);

        if (is_null($this->_challenge))
            $this->getChallenge();

        if (empty($this->_challengeResponse))
            throw new UserException('One time code or response code from security token is missing.', 11);

        $output = $this->postRequest('identification/securitytoken', ['response' => (string)$this->_challengeResponse,]);

        if (!isset($output->links->next->uri))
        {
            $code      = ($this->isUseOneTimePassword()) ? 'one time code' : 'response code';
            $errorCode = ($this->isUseOneTimePassword()) ? 12 : 13;

            throw new Exception(sprintf('Cannot sign in. Probably due to invalid or too old %s.', $code), $errorCode);
        }

        return true;
    }

    /**
     * Set one time code or response code
     *
     * @param string $challengeResponse One time code or response code from security token.
     */
    public function setChallengeResponse($challengeResponse)
    {
        $this->_challengeResponse = $challengeResponse;
    }

    /**
     * Security token type
     *
     * @return bool True for one time code, else control number and response code security token
     */
    public function isUseOneTimePassword()
    {
        return $this->_useOneTimePassword;
    }
}