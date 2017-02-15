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
            'swedbank'           => ['appID' => 'Meen0DRdooDtd3y2', 'useragent' => 'SwedbankMOBPrivateIOS/4.9.5_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'sparbanken'         => ['appID' => 'f7flKefi5gKbG1BG', 'useragent' => 'SavingbankMOBPrivateIOS/4.9.5_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'swedbank_ung'       => ['appID' => '1WBuAPysQW12LIAX', 'useragent' => 'SwedbankMOBYouthIOS/2.4.5_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'sparbanken_ung'     => ['appID' => 'P4gwbFmLWE71hrSv', 'useragent' => 'SavingbankMOBYouthIOS/2.4.5_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'swedbank_foretag'   => ['appID' => 'ZA4nyT1jAY3b3aDK', 'useragent' => 'SwedbankMOBCorporateIOS/2.6.5_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'sparbanken_foretag' => ['appID' => '1AJGhENPquTSriHe', 'useragent' => 'SavingbankMOBCorporateIOS/2.6.5_(iOS;_10.2.1)_Apple/iPhone9,3'],
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
