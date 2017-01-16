<?php namespace Lovata\YouVeGotMail\Models;

use Carbon\Carbon;
use Model;
use Lang;
use October\Rain\Database\Collection;

/**
 * Class Category
 * @package Lovata\YouVeGotMail\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection $subscribers
 * @property Collection $mailings
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    public $table = 'lovata_youvegotmail_categories';
    
    /** Validation  */
    public $rules = [
        'name' => 'required',
    ];
    
    public $customMessages = [];
    public $attributeNames = [];
    
    protected $slugs = ['slug' => 'title'];

    public $belongsToMany = [
        'subscribers' => ['Lovata\YouVeGotMail\Models\Subscriber', 'table' => 'lovata_youvegotmail_link']
    ];

    public $hasMany = [
        'mailings' => 'Lovata\YouVeGotMail\Models\Mailing'
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
        ];
        
        parent::__construct($attributes);
    }
}