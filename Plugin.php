<?php namespace Lovata\YouVeGotMail;

use Lang;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package Lovata\YouVeGotMail
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{

    public function registerSettings() {
        return [
            'settings' => [
                'label'       => 'lovata.youvegotmail::lang.plugin.name',
                'icon'        => 'icon-envelope',
                'description' => 'lovata.youvegotmail::lang.settings.description',
                'class'       => 'Lovata\YouVeGotMail\Models\Settings',
                'order'       => 100
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'lovata.youvegotmail::mail.default'   => Lang::get('lovata.youvegotmail::mail.default_description'),
        ];
    }

    public function registerComponents()
    {
        return [
            '\Lovata\YouVeGotMail\Components\Subscriptions' => 'LVSubscriptions',
        ];
    }

}
