<?php
/**
 * Inställningar öfr
 *
 * @package Projectnamn
 * @author walle
 * Date: 2014-05-13
 * Time: 21:40
 */
namespace walle89\SwedbankJson;

/**
 * Class appdata
 */
class AppData
{
    private static $appData = array(
        'swedbank'          => array('appID' => '8ha8SU1FOoA91y8W', 'useragent' => 'SwedbankMOBPrivateIOS/3.7.0_(iOS;_7.1)_Apple/iPhone5,2'),
        'sparbanken'        => array('appID' => 'sHyQguyrmHzdpVqz', 'useragent' => 'SavingbankMOBPrivateIOS/3.7.0_(iOS;_7.1)_Apple/iPhone5,2'),
        'swedbank_ung'      => array('appID' => 'XlqIpRxVTTgHuFnj', 'useragent' => 'SwedbankMOBYouthIOS/1.4.0_(iOS;_7.1)_Apple/iPhone5,2'),
        'sparbanken_ung'    => array('appID' => 'XOn0HeGwPmELslnO', 'useragent' => 'SavingbankMOBYouthIOS/1.4.0_(iOS;_7.1)_Apple/iPhone5,2'),
        'swedbank_företag'  => array('appID' => 'VCTjPQs88i3z1UOP', 'useragent' => 'SwedbankMOBCorporateIOS/1.4.0_(iOS;_7.1)_Apple/iPhone5,2'),  
    );

    public static function bankAppId($bankID)
    {
        return self::$appData[$bankID];
    }
}
 