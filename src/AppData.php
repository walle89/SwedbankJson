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
            'swedbank'           => ['appID' => 'tKiUJOc0fAdy9itb', 'useragent' => 'SwedbankMOBPrivateIOS/4.5.0_(iOS;_9.2.1)_Apple/iPhone7,2'],
            'sparbanken'         => ['appID' => 'ApXJOPzxuClYQ09o', 'useragent' => 'SavingbankMOBPrivateIOS/4.5.0_(iOS;_9.2.1)_Apple/iPhone7,2'],
            'swedbank_ung'       => ['appID' => 'SjH7oIgOqkGmqxUz', 'useragent' => 'SwedbankMOBYouthIOS/2.0.0_(iOS;_9.2.1)_Apple/iPhone7,2'],
            'sparbanken_ung'     => ['appID' => 'L9SJJQiYav1CvTtK', 'useragent' => 'SavingbankMOBYouthIOS/2.0.0_(iOS;_9.2.1)_Apple/iPhone7,2'],
            'swedbank_foretag'   => ['appID' => 'FXdVTYdzOGBvqe5l', 'useragent' => 'SwedbankMOBCorporateIOS/2.2.0_(iOS;_9.2.1)_Apple/iPhone7,2'],
            'sparbanken_foretag' => ['appID' => 'SeUNIvpcNHnNPwvK', 'useragent' => 'SavingbankMOBCorporateIOS/2.2.0_(iOS;_9.2.1)_Apple/iPhone7,2'],
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