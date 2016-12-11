<?php namespace Lovata\YouVeGotMail\Models;

use Model;
use Lang;
use Carbon\Carbon;
use October\Rain\Database\Collection;
use System\Models\MailTemplate;

/**
 * Class Mailing
 * @package Lovata\YouVeGotMail\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $content
 * @property int $category_id
 * @property int $template_id
 * @property string $data_source
 * @property int $data_source_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Category $category
 * @property MailTemplate $template
 */
class Mailing extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table = 'lovata_youvegotmail_mailings';
    
    protected $chosenDataSourceValue= '';

    /** Validation */
    public $rules = [
        'name' => 'required',
        'title' => 'required',
        'template_id' => 'required'
    ];

    public $customMessages = [];
    public $attributeNames = [];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'category' => 'Lovata\YouVeGotMail\Models\Category',
        'template' => 'System\Models\MailTemplate',
    ];

    public function __construct(array $attributes = [])
    {

        //Set validation custom messages
        $this->customMessages = [
            'required' => Lang::get('lovata.youvegotmail::lang.validation.required'),
        ];

        //Set validation custom attributes name
        $this->attributeNames = [
            'name' => Lang::get('lovata.youvegotmail::lang.fields.name'),
            'title' => Lang::get('lovata.youvegotmail::lang.fields.title'),
            'template_id' => Lang::get('lovata.youvegotmail::lang.fields.template'),
        ];

        parent::__construct($attributes);
    }

    /**
     * Get source list
     * @return array
     */
    public function getDataSourceOptions()
    {
        $arResult = [
            'default' => Lang::get('lovata.youvegotmail::lang.fields.handprint'),
        ];
        
        $arDataSources = Settings::getValue('external_data_sources');
        if(empty($arDataSources)){
            return $arResult;
        }

        foreach ($arDataSources as $arDetails) {
            if(!empty($arDetails['source_class']) && class_exists($arDetails['source_class'])) {
                $arResult[$arDetails['source_class']] = $arDetails['name'];
            }
        }
        
        return $arResult;
    }

    /**
     * Get elements list
     * @return array
     */
    public function getDataSourceIdOptions()
    {
        $arDataSources = Settings::getValue('external_data_sources');
        
        $arResult = [];
        if(empty($arDataSources)){
            return $arResult;
        }
        
        foreach ($arDataSources as $arDetails) {
            if($this->chosenDataSourceValue == $arDetails['source_class'] && class_exists($arDetails['source_class']) && method_exists($arDetails['source_class'], $arDetails['source__method'])) {
                return $arDetails['source_class']::$arDetails['source_method']();
            }
        }
        
        return $arResult;
    }

    /**
     * Get template list
     * @return array
     */
    public function getTemplateIdOptions()
    {
        /** @var Collection $arMailTemplates */
        $arMailTemplates = MailTemplate::where('code', 'LIKE', 'lovata.youvegotmail%')->get();
        if($arMailTemplates->isEmpty()) {
            return [];
        }
        
        $arResult = [];
        
        /** @var MailTemplate $obMailTemplate */
        foreach ($arMailTemplates as $obMailTemplate) {
            $arResult[$obMailTemplate->id] = $obMailTemplate->code;
        }
        
        return $arResult;
    }
}