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
            'swedbank'           => ['appID' => 'R7PA74SED1LkJcjT', 'useragent' => 'SwedbankMOBPrivateIOS/4.9.4_(iOS;_10.2)_Apple/iPhone9,3'],
            'sparbanken'         => ['appID' => 'kjKoiXCGhZfkG9rR', 'useragent' => 'SavingbankMOBPrivateIOS/4.9.4_(iOS;_10.2)_Apple/iPhone9,3'],
            'swedbank_ung'       => ['appID' => 'rnwjZ2HxcgWRRzfA', 'useragent' => 'SwedbankMOBYouthIOS/2.4.4_(iOS;_10.2)_Apple/iPhone9,3'],
            'sparbanken_ung'     => ['appID' => '4W0SGwEd7ojoq5EF', 'useragent' => 'SavingbankMOBYouthIOS/2.4.4_(iOS;_10.2)_Apple/iPhone9,3'],
            'swedbank_foretag'   => ['appID' => 'nUIGXI44CNWfAnJl', 'useragent' => 'SwedbankMOBCorporateIOS/2.6.4_(iOS;_10.2)_Apple/iPhone9,3'],
            'sparbanken_foretag' => ['appID' => 'KAswmoyM4CbPC3AN', 'useragent' => 'SavingbankMOBCorporateIOS/2.6.4_(iOS;_10.2)_Apple/iPhone9,3'],
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