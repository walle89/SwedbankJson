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
     * UnAuth connection
     *
     * @return bool True on success.
     * @throws Exception
     */
    public function login()
    {
        $output = $this->getRequest('identification/device/');

        if ($output->status != 'OK')
            throw new Exception('Connection error check authentication key or try again later.', 10);

        return true;
    }
}