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
        'swedbank'              => [ 'appID' => '9UysL1FEuwfdW22c', 'useragent' => 'SwedbankMOBPrivateIOS/4.0.1_(iOS;_8.1.3)_Apple/iPhone7,2'       ],
        'sparbanken'            => [ 'appID' => 'zTnYOYPukajOiHqO', 'useragent' => 'SavingbankMOBPrivateIOS/4.0.1_(iOS;_8.1.3)_Apple/iPhone7,2'     ],
        'swedbank_ung'          => [ 'appID' => '8wpF0OvIIg17BExX', 'useragent' => 'SwedbankMOBYouthIOS/1.7.1_(iOS;_8.1.3)_Apple/iPhone7,2'         ],
        'sparbanken_ung'        => [ 'appID' => 'ngaEms9rAXYtUifm', 'useragent' => 'SavingbankMOBYouthIOS/1.7.1_(iOS;_8.1.3)_Apple/iPhone7,2'       ],
        'swedbank_foretag'      => [ 'appID' => '4WXxfxvWDY5kd0eg', 'useragent' => 'SwedbankMOBCorporateIOS/1.6.0_(iOS;_8.1.3)_Apple/iPhone7,2'     ],
        'sparbanken_foretag'    => [ 'appID' => 'qaSBwIdFFqRo48WD', 'useragent' => 'SavingbankMOBCorporateIOS/1.6.0_(iOS;_8.1.3)_Apple/iPhone7,2'   ],
    ];

    public static function bankAppId($bankID)
    {
        if ($bankID == 'swedbank_företag')
            throw new UserException('Bankid "swedbank_företag" är inte längre giltigt. Använd "swedbank_foretag"',1);

        elseif (!isset(self::$appData[ $bankID ]))
            throw new UserException('BankID existerar inte, använd något av följande: '.implode(', ', array_keys(self::$appData)),2);

        return self::$appData[ $bankID ];
    }
}