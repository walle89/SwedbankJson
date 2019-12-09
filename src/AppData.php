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
            'swedbank'           => ['appID' => '4cQupxZFo8kk7R7f', 'useragent' => 'SwedbankMOBPrivateIOS/7.17.0_(iOS;_13.1.3)_Apple/iPhone10,6'],
            'sparbanken'         => ['appID' => 'MlJMcXYNhIgPYomY', 'useragent' => 'SavingbankMOBPrivateIOS/7.17.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'swedbank_ung'       => ['appID' => 'HnWVnvxpjYc2DM7g', 'useragent' => 'SwedbankMOBYouthIOS/2.26.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'sparbanken_ung'     => ['appID' => 'BXTU4hqHicC7j0Yq', 'useragent' => 'SavingbankMOBYouthIOS/2.26.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'swedbank_foretag'   => ['appID' => 'NUZd4oJYtLYXIx1k', 'useragent' => 'SwedbankMOBCorporateIOS/3.3.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'sparbanken_foretag' => ['appID' => 'mCVd2gryis7ziyS0', 'useragent' => 'SavingbankMOBCorporateIOS/3.3.0_(iOS;_13.1.3)_Apple/iPad6,3'],
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