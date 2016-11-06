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
            'swedbank'           => ['appID' => 'X71LuxH8n4XEqDMg', 'useragent' => 'SwedbankMOBPrivateIOS/4.9.3_(iOS;_10.1.1)_Apple/iPhone7,2'],
            'sparbanken'         => ['appID' => 'WLUDemwnwZvJdoJx', 'useragent' => 'SavingbankMOBPrivateIOS/4.9.3_(iOS;_10.1.1)_Apple/iPhone7,2'],
            'swedbank_ung'       => ['appID' => 'vEgFFWmbG1IbipQs', 'useragent' => 'SwedbankMOBYouthIOS/2.4.2_(iOS;_10.0.2)_Apple/iPhone7,2'],
            'sparbanken_ung'     => ['appID' => 'QmaEk0DY4w2I9xCb', 'useragent' => 'SavingbankMOBYouthIOS/2.4.2_(iOS;_10.0.2)_Apple/iPhone7,2'],
            'swedbank_foretag'   => ['appID' => 'uDHrXteUbPXkd9TG', 'useragent' => 'SwedbankMOBCorporateIOS/2.6.3_(iOS;_10.1.1)_Apple/iPhone7,2'],
            'sparbanken_foretag' => ['appID' => 'OG3t3JvoUbXA49ui', 'useragent' => 'SavingbankMOBCorporateIOS/2.6.3_(iOS;_10.1.1)_Apple/iPhone7,2'],
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