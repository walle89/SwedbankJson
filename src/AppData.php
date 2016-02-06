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

use SwedbankJson\Exception\UserException;

/**
 * Class appdata
 */
class AppData
{
    private static $appData = [
        'swedbank'              => [ 'appID' => 'IyTj3PGu2IRIpxRE', 'useragent' => 'SwedbankMOBPrivateIOS/4.2.0_(iOS;_8.3)_Apple/iPhone7,2'       ],
        'sparbanken'            => [ 'appID' => 'HhfKI2Wy9INbpWv2', 'useragent' => 'SavingbankMOBPrivateIOS/4.2.0_(iOS;_8.3)_Apple/iPhone7,2'     ],
        'swedbank_ung'          => [ 'appID' => 'g0ckLfoBFtXm9hBr', 'useragent' => 'SwedbankMOBYouthIOS/1.9.0_(iOS;_8.3)_Apple/iPhone7,2'         ],
        'sparbanken_ung'        => [ 'appID' => 's5dE55nxAm3vjYeq', 'useragent' => 'SavingbankMOBYouthIOS/1.9.0_(iOS;_8.3)_Apple/iPhone7,2'       ],
        'swedbank_foretag'      => [ 'appID' => 'wGd045W9Oa313XjQ', 'useragent' => 'SwedbankMOBCorporateIOS/1.9.0_(iOS;_8.3)_Apple/iPhone7,2'     ],
        'sparbanken_foretag'    => [ 'appID' => 's0pgDBKeByxVVQhD', 'useragent' => 'SavingbankMOBCorporateIOS/1.9.0_(iOS;_8.3)_Apple/iPhone7,2'   ],
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