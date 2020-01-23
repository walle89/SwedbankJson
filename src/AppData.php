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
            'swedbank'           => ['appID' => 'R2S7gO3t2SgvJXUu', 'useragent' => 'SwedbankMOBPrivateIOS/7.18.1_(iOS;_13.3)_Apple/iPhone10,6'],
            'sparbanken'         => ['appID' => 'grWCLgrLH2bskrCF', 'useragent' => 'SavingbankMOBPrivateIOS/7.18.1_(iOS;_13.3)_Apple/iPhone10,6'],
            'swedbank_ung'       => ['appID' => 'HnWVnvxpjYc2DM7g', 'useragent' => 'SwedbankMOBYouthIOS/2.26.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'sparbanken_ung'     => ['appID' => 'BXTU4hqHicC7j0Yq', 'useragent' => 'SavingbankMOBYouthIOS/2.26.0_(iOS;_13.1.3)_Apple/iPad6,3'],
            'swedbank_foretag'   => ['appID' => '9H5GZZAW2DrlLIDH', 'useragent' => 'SwedbankMOBCorporateIOS/3.4.0_(iOS;_13.3)_Apple/iPhone10,6'],
            'sparbanken_foretag' => ['appID' => 'Y84LXZMn5xjPabXP', 'useragent' => 'SavingbankMOBCorporateIOS/3.4.0_(iOS;_13.3)_Apple/iPhone10,6'],
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