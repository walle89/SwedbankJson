<?php
/**
 * Inställningar för
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
        'swedbank'          => array('appID' => 'fjUHYbQjupOQspeG', 'useragent' => 'SwedbankMOBPrivateIOS/3.7.1_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'sparbanken'        => array('appID' => 'WXessEsk4s1ZE4GO', 'useragent' => 'SavingbankMOBPrivateIOS/3.7.1_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'swedbank_ung'      => array('appID' => 'XlqIpRxVTTgHuFnj', 'useragent' => 'SwedbankMOBYouthIOS/1.4.0_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'sparbanken_ung'    => array('appID' => 'XOn0HeGwPmELslnO', 'useragent' => 'SavingbankMOBYouthIOS/1.4.0_(iOS;_7.1.1)_Apple/iPhone5,2'),
        'swedbank_företag'  => array('appID' => '1e7ZaX5kq3Dk4mCK', 'useragent' => 'SwedbankMOBCorporateIOS/1.4.1_(iOS;_7.1.1)_Apple/iPhone5,2'),
    );

    public static function bankAppId($bankID)
    {
        return self::$appData[$bankID];
    }
}
