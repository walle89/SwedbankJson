<?php
namespace SwedbankJson\Auth;

use Exception;
use SwedbankJson\AppData;

/**
 * Class MobileBankID
 * @package SwedbankJson\Auth
 */
class MobileBankID extends AbstractAuth
{
    /** @var bool If Mobile BankID authentication have been verified */
    protected $_verified = false;

    /**
     * MobileBankID constructor.
     *
     * @param AppData $appData  Bank type AppID
     * @param bool    $debug    Enable debugging
     *
     * @throws Exception
     */
    public function __construct(AppData $appData, $debug = false)
    {
        $this->setAppData($appData);
        $this->_debug    = (bool)$debug;
        $this->setAuthorizationKey();
        $this->persistentSession();
    }

    /**
     * Initiate Mobile BankID authentication
     *
     * Sends verification request to the users Mobile BankID app.
     *
     * @return bool
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function initAuth()
    {
        if ($this->_verified)
            return true;

        $output = $this->postRequest('identification/bankid/mobile', ['bankIdOnSameDevice'  => false,]);

        if ($output->status != 'USER_SIGN') {
            throw new Exception('Unable to use Mobile BankID. Check if the user have enabled Mobile BankID.', 10);
        }

        $this->saveSession();

        return true;
    }

    /**
     * Check Mobile BankID verification
     *
     * See if the user have confirmed the authentication verification request.
     *
     * @return bool True if verified. False to check later (eg. 5 seconds) for user verification.
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify()
    {
        if ($this->_verified)
            return true;

        $output = $this->getRequest('identification/bankid/mobile/verify');

        if (empty($output->status))
            throw new Exception('Mobile BankID cannot be verified. Maybe a session timeout.', 11);

        $this->_verified = ($output->status == 'COMPLETE');

        $this->saveSession();

        return $this->_verified;
    }

    /**
     * Fetch the challenge Image (QR code)
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getChallengeImage()
    {
        $output = $this->getRequest('identification/bankid/mobile/image');

        return (string)$output;
    }

    /**
     * Sign in
     *
     * The authentication verification request must be verified with verify() before use of login.
     *
     * @return bool         True on successful sign in
     * @throws Exception
     */
    public function login()
    {
        if (!$this->_verified)
            throw new Exception('The authentication process did not start in the right way for Mobile BankID.', 12);

        return true;
    }

    /**
     * For persistent sessions
     *
     * @return array List of attributes to be saved
     */
    public function __sleep()
    {
        $sleepAttr   = parent::__sleep();
        $sleepAttr[] = '_verified';
        return $sleepAttr;
    }
}