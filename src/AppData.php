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
            'swedbank'           => ['appID' => 'NoaCnRf1y4ghs1Vn', 'useragent' => 'SwedbankMOBPrivateIOS/4.9.0_(iOS;_9.3.3)_Apple/iPhone7,2'],
            'sparbanken'         => ['appID' => 'cYDoGe7NUW9cwLQk', 'useragent' => 'SavingbankMOBPrivateIOS/4.9.0_(iOS;_9.3.3)_Apple/iPhone7,2'],
            'swedbank_ung'       => ['appID' => '8biKCLEzfoBrgedE', 'useragent' => 'SwedbankMOBYouthIOS/2.4.0_(iOS;_9.3.3)_Apple/iPhone7,2'],
            'sparbanken_ung'     => ['appID' => 'mWDhpVQFTwUpGed4', 'useragent' => 'SavingbankMOBYouthIOS/2.4.0_(iOS;_9.3.3)_Apple/iPhone7,2'],
            'swedbank_foretag'   => ['appID' => 'pOieiyH457zln42O', 'useragent' => 'SwedbankMOBCorporateIOS/2.6.0_(iOS;_9.3.3)_Apple/iPhone7,2'],
            'sparbanken_foretag' => ['appID' => 'R1CQU8B98syEnEvR', 'useragent' => 'SavingbankMOBCorporateIOS/2.6.0_(iOS;_9.3.3)_Apple/iPhone7,2'],
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