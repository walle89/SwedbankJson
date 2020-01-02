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
     * @param AppData $appData
     * @param bool    $debug Enable debugging
     *
     * @throws Exception
     */
    public function __construct(AppData $appData, $debug = false)
    {
        $this->setAppData($appData);
        $this->_debug = (bool)$debug;
        $this->setAuthorizationKey();
        $this->setBaseUri('https://unauth.api.swedbank.se/TDE_DAP_Portal_REST_WEB/api/');
    }

    /**
     * UnAuth connection
     *
     * @return bool True on success.
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login()
    {
        $output = $this->getRequest('identification/device/');

        if ($output->status != 'OK')
            throw new Exception('Connection error check authentication key or try again later.', 10);

        return true;
    }
}