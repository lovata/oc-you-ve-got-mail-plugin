<?php namespace Lovata\YouVeGotMail\Models;

use Cache;
use Model;

/**
 * Class Settings
 * @package Lovata\YouVeGotMail\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Settings extends Model
{

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'youvegotmail_settings';

    public $settingsFields = 'fields.yaml';

    const CACHE_TAG = 'youvegotmail_settings';
    const CACHE_TIME = 10080;
    
    /**
     * Get setting value from cache
     * @param string $sCode
     * @return null|string
     */
    public static function getValue($sCode) {

        if(empty($sCode)) {
            return '';
        }
        
        $sKey = self::CACHE_TAG.'_'.$sCode;

        //Get value from cache
        $sResult = Cache::get($sKey);
        if(!empty($sResult)) {
            return $sResult;
        }

        //Get value
        $sResult = self::get($sCode);

        //Set cache data
        Cache::put($sKey, $sResult, self::CACHE_TIME);

        return $sResult;
    }

    public function afterSave() {
        
        //Clear cache data
        $arValue = $this->value;
        foreach($arValue as $sKey => $sValue) {
            Cache::forget(self::CACHE_TAG.'_'.$sKey);
        }
    }
}