<?php
namespace SwedbankJson;

use SwedbankJson\Exception\UserException;

/**
 * Class AppData
 * @package SwedbankJson
 */
class AppData
{
    /** @var array Bank type with appID and user-agent */
    private static $appData = [
            'swedbank'           => ['appID' => 'LLoO1Oj8ZFNrZCWN', 'useragent' => 'SwedbankMOBPrivateIOS/7.11.0_(iOS;_12.2)_Apple/iPhone10,6'],
            'sparbanken'         => ['appID' => '1MyVgAIFeSTVzhZY', 'useragent' => 'SavingbankMOBPrivateIOS/7.11.0_(iOS;_12.2)_Apple/iPad6,3'],
            'swedbank_ung'       => ['appID' => 'lRMzV94mnwJBdBC0', 'useragent' => 'SwedbankMOBYouthIOS/2.22.0_(iOS;_12.2)_Apple/iPad6,3'],
            'sparbanken_ung'     => ['appID' => '8y3TclNs9Ymil2BC', 'useragent' => 'SavingbankMOBYouthIOS/2.22.0_(iOS;_12.2)_Apple/iPad6,3'],
            'swedbank_foretag'   => ['appID' => 'Bg9Ej7jrVgMjCuxi', 'useragent' => 'SwedbankMOBCorporateIOS/2.24.0_(iOS;_12.2)_Apple/iPad6,3'],
            'sparbanken_foretag' => ['appID' => 'bYULjoIQU4r58lzV', 'useragent' => 'SavingbankMOBCorporateIOS/2.24.0_(iOS;_12.2)_Apple/iPad6,3'],
        ];

    /**
     * Bank type settings
     *
     * @param string $bankApp Bank type
     *
     * @return array
     */
    public static function bankAppId($bankApp)
    {
        if ($bankApp == 'swedbank_företag')
            throw new UserException('Bank type "swedbank_företag" is no longer valid. Please "swedbank_foretag" instead.', 1);

        elseif (!isset(self::$appData[$bankApp]))
            throw new UserException('Bank type does not exists, use one of the following: '.implode(', ', array_keys(self::$appData)), 2);

        return self::$appData[$bankApp];
    }
}