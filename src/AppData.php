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
        'swedbank'              => [ 'appID' => 'i1Pc8spRArqu9KAh', 'useragent' => 'SwedbankMOBPrivateIOS/4.0.0_(iOS;_8.1.1)_Apple/iPhone5,2'       ],
        'sparbanken'            => [ 'appID' => 'NOhNNqhzTXXSoOdQ', 'useragent' => 'SavingbankMOBPrivateIOS/4.0.0_(iOS;_8.1.1)_Apple/iPhone5,2'     ],
        'swedbank_ung'          => [ 'appID' => 'CXTbGZnvWjL4vVBr', 'useragent' => 'SwedbankMOBYouthIOS/1.7.0_(iOS;_8.1.1)_Apple/iPhone5,2'         ],
        'sparbanken_ung'        => [ 'appID' => 'l8TOWVEHNtS1dCvd', 'useragent' => 'SavingbankMOBYouthIOS/1.7.0_(iOS;_8.1.1)_Apple/iPhone5,2'       ],
        'swedbank_foretag'      => [ 'appID' => '4WXxfxvWDY5kd0eg', 'useragent' => 'SwedbankMOBCorporateIOS/1.6.0_(iOS;_8.1.1)_Apple/iPhone5,2'     ],
        'sparbanken_foretag'    => [ 'appID' => 'qaSBwIdFFqRo48WD', 'useragent' => 'SavingbankMOBCorporateIOS/1.6.0_(iOS;_8.1.1)_Apple/iPhone5,2'   ],
    ];

    public static function bankAppId($bankID)
    {
        if ($bankID == 'swedbank_företag')
            throw new UserException('Bankid "swedbank_företag" är inte längre giltigt. Använd "swedbank_foretag"',1);

        elseif (!isset(self::$appData[ $bankID ]))
            throw new UserException('BankID existerar inte, använd något av följande: '.implode(', ', array_keys(self::$appData)));

        return self::$appData[ $bankID ];
    }
}