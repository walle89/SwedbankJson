<?php
/**
 * Inställningar för olika app-varianter från Swedbank.
 *
 * @package Projectnamn
 * @author walle
 * Date: 2014-05-13
 * Time: 21:40
 */
namespace SwedbankJson;

/**
 * Class appdata
 */
class AppData
{
    private static $appData = [
        'swedbank'              => [ 'appID' => 'N4WCtBBiG7tiMN7S', 'useragent' => 'SwedbankMOBPrivateIOS/4.1.0_(iOS;_8.1.3)_Apple/iPhone7,2'       ],
        'sparbanken'            => [ 'appID' => 'DriERvIHUdhMMt3B', 'useragent' => 'SavingbankMOBPrivateIOS/4.1.0_(iOS;_8.1.3)_Apple/iPhone7,2'     ],
        'swedbank_ung'          => [ 'appID' => 'zEPcyHcQaam2WWfl', 'useragent' => 'SwedbankMOBYouthIOS/1.8.0_(iOS;_8.1.3)_Apple/iPhone7,2'         ],
        'sparbanken_ung'        => [ 'appID' => 'kAJyPnvmMyklygCH', 'useragent' => 'SavingbankMOBYouthIOS/1.8.0_(iOS;_8.1.3)_Apple/iPhone7,2'       ],
        'swedbank_foretag'      => [ 'appID' => 'a1mUP3geeiMSCSJ0', 'useragent' => 'SwedbankMOBCorporateIOS/1.7.0_(iOS;_8.1.3)_Apple/iPhone7,2'     ],
        'sparbanken_foretag'    => [ 'appID' => 'p0sLuHGYX8RTChET', 'useragent' => 'SavingbankMOBCorporateIOS/1.7.0_(iOS;_8.1.3)_Apple/iPhone7,2'   ],
    ];

    public static function bankAppId($bankApp)
    {
        if ($bankApp == 'swedbank_företag')
            throw new UserException('BankApp "swedbank_företag" är inte längre giltigt. Använd "swedbank_foretag"',1);

        elseif (!isset(self::$appData[ $bankApp ]))
            throw new UserException('BankApp existerar inte, använd något av följande: '.implode(', ', array_keys(self::$appData)),2);

        return self::$appData[ $bankApp ];
    }
}