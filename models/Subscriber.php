<?php namespace Lovata\YouVeGotMail\Models;

use Model;
use Lang;
use Carbon\Carbon;
use October\Rain\Database\Collection;

/**
 * Class Subscriber
 * @package Lovata\YouVeGotMail\Models
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 * 
 * @property $id
 * @property $email
 * @property $name
 * @property $surname
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection $categories
 * 
 * @method static $this getByEmail(string $sEmail)
 */
class Subscriber extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table = 'lovata_youvegotmail_subscribers';

    /** Validation */
    public $rules = [
        'email' => 'required|unique:lovata_youvegotmail_subscribers'
    ];
    
    public $customMessages = [];
    public $attributeNames = [];
    
    protected $fillable = ['email'];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'categories' => ['Lovata\YouVeGotMail\Models\Category', 'table' => 'lovata_youvegotmail_category_subscriber']
    ];

    public function __construct(array $attributes = [])
    {

        //Set validation custom messages
        $this->customMessages = [
            'required' => Lang::get('lovata.youvegotmail::lang.validation.required'),
            'unique' => Lang::get('lovata.youvegotmail::lang.validation.unique'),
        ];

        //Set validation custom attributes name
        $this->attributeNames = [
            'email' => Lang::get('lovata.youvegotmail::lang.fields.email'),
        ];

        parent::__construct($attributes);
    }

    /**
     * Get by email
     * @param \Illuminate\Database\Eloquent\Builder $obQuery
     * @param $sData
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByEmail($obQuery, $sData)
    {
        if(!empty($sData)) {
            $obQuery->where('email', $sData);
        }
        
        return $obQuery;
    }
}