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
            'swedbank'           => ['appID' => 'd9s87IHQC3AjD5Nu', 'useragent' => 'SwedbankMOBPrivateIOS/4.9.6_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'sparbanken'         => ['appID' => 'mpAyddpZwjvkI9Gb', 'useragent' => 'SavingbankMOBPrivateIOS/4.9.6_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'swedbank_ung'       => ['appID' => 't9vuKZPEnCjqIafE', 'useragent' => 'SwedbankMOBYouthIOS/2.4.6_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'sparbanken_ung'     => ['appID' => 'YMCasM2buNKX7J5x', 'useragent' => 'SavingbankMOBYouthIOS/2.4.6_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'swedbank_foretag'   => ['appID' => 'GqZzlcr7FJ5Nf8KK', 'useragent' => 'SwedbankMOBCorporateIOS/2.6.6_(iOS;_10.2.1)_Apple/iPhone9,3'],
            'sparbanken_foretag' => ['appID' => 'kltvfKIII370Suwm', 'useragent' => 'SavingbankMOBCorporateIOS/2.6.6_(iOS;_10.2.1)_Apple/iPhone9,3'],
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
