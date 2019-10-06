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
            'swedbank'           => ['appID' => 'BYI1wWnQwVrNojkB', 'useragent' => 'SwedbankMOBPrivateIOS/7.15.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'sparbanken'         => ['appID' => 'TzTh3rgdH0KV4oFU', 'useragent' => 'SavingbankMOBPrivateIOS/7.15.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'swedbank_ung'       => ['appID' => 'MrOEktTqwxhwnwTn', 'useragent' => 'SwedbankMOBYouthIOS/2.25.0_(iOS;_13.1.2)_Apple/iPad6,3'],
            'sparbanken_ung'     => ['appID' => 'iHo8Tgxv9D8afPJR', 'useragent' => 'SavingbankMOBYouthIOS/2.25.0_(iOS;_13.1.2)_Apple/iPad6,3'],
            'swedbank_foretag'   => ['appID' => 'AG4EyMSV2QQilrhi', 'useragent' => 'SwedbankMOBCorporateIOS/3.1.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'sparbanken_foretag' => ['appID' => 'BLzjvxH1QCiJ4F9c', 'useragent' => 'SavingbankMOBCorporateIOS/3.1.0_(iOS;_13.1.3)_Apple/iPad6,3'],
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