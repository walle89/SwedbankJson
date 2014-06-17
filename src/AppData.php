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
    private static $appData = array(
        'swedbank'              => array('appID' => 'mPXoKcA3jsKP0cAQ', 'useragent' => 'SwedbankMOBPrivateIOS/3.8.0_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'sparbanken'            => array('appID' => 'q5JD05YQBoaMuzR2', 'useragent' => 'SavingbankMOBPrivateIOS/3.8.0_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'swedbank_ung'          => array('appID' => 'ZvBWfugmcLdNo2r2', 'useragent' => 'SwedbankMOBYouthIOS/1.5.0_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'sparbanken_ung'        => array('appID' => 'VNxDH9rfpWrCDzsL', 'useragent' => 'SavingbankMOBYouthIOS/1.5.0_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'swedbank_foretag'      => array('appID' => '1e7ZaX5kq3Dk4mCK', 'useragent' => 'SwedbankMOBCorporateIOS/1.4.1_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'sparbanken_foretag'    => array('appID' => 'tuLer1xBdoQChAEM', 'useragent' => 'SavingbankMOBCorporateIOS/1.4.1_(iOS;_7.1.1)_Apple/iPhone5,2'),
    );

    public static function bankAppId($bankID)
    {
        if($bankID == 'swedbank_företag')
            throw new UserException('Bankid "swedbank_företag" är inte längre giltigt. Använd "swedbank_foretag"',1);
        elseif(!array_key_exists($bankID, self::$appData))
            throw new UserException('BankID existerar inte, använd något av följande: '.implode(', ', array_keys(self::$appData)));

        return self::$appData[$bankID];
    }
}