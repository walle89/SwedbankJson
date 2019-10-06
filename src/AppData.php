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
            'swedbank'           => ['appID' => 'vWrf1Qdu0Go7QOO4', 'useragent' => 'SwedbankMOBPrivateIOS/7.14.2_(iOS;_13.1.2)_Apple/iPhone10,6'],
            'sparbanken'         => ['appID' => 'IMMyKo7pgZ7wz2bP', 'useragent' => 'SavingbankMOBPrivateAndroid/7.14.0 (Android; 6.0.1) BullittGroupLimited/S60'],
            'swedbank_ung'       => ['appID' => 'MrOEktTqwxhwnwTn', 'useragent' => 'SwedbankMOBYouthIOS/2.25.0_(iOS;_13.1.2)_Apple/iPad6,3'],
            'sparbanken_ung'     => ['appID' => 'iHo8Tgxv9D8afPJR', 'useragent' => 'SavingbankMOBYouthIOS/2.25.0_(iOS;_13.1.2)_Apple/iPad6,3'],
            'swedbank_foretag'   => ['appID' => 'tNIGKz1nl1TC5OIo', 'useragent' => 'SwedbankMOBCorporateIOS/3.0.1_(iOS;_13.1.2)_Apple/iPad6,3'],
            'sparbanken_foretag' => ['appID' => 'Jb4Xatij2817OUBz', 'useragent' => 'SavingbankMOBCorporateIOS/3.0.1_(iOS;_13.1.2)_Apple/iPad6,3'],
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