<?php namespace Lovata\YouVeGotMail\Components;

use Cms\Classes\ComponentBase;
use Lovata\YouVeGotMail\Models\Category;
use Lovata\YouVeGotMail\Models\Subscriber;
use Lang;
use Input;

/**
 * Class Subscriptions
 * @package Lovata\YouVeGotMail\Components
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Subscriptions extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'lovata.youvegotmail::lang.component.subscription_name',
            'description' => 'lovata.youvegotmail::lang.component.subscription_desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'category' => [
                'title'       => 'lovata.youvegotmail::lang.fields.category',
                'type'        => 'dropdown',
                'default'     => '0',
            ]
        ];
    }

    /**
     * Get category list
     * @return array
     */
    public function getCategoryOptions()
    {
        $arResult = [
            0 => Lang::get('lovata.youvegotmail::lang.fields.category_default'),
        ];
        
        $arCategories = Category::all();
        if($arCategories->isEmpty()) {
            return $arResult;
        }
        
        /** @var Category $obCategory */
        foreach ($arCategories as $obCategory) {
            $arResult[$obCategory->id] = $obCategory->name;
        }
        
        return $arResult;
    }

    /**
     * Add user to subscribe
     * @return array|void
     */
    public function onSubscribe() {
        
        $sUserName = Input::get('name');
        $sUserSurname = Input::get('surname');
        $sEmail = Input::get('email');
        
        if(empty($sEmail)) {
            return true;
        }
        
        //Get category ID
        $iCategoryId =  $this->property('category');
        
        //Get subscribe user by email
        /** @var Subscriber $obUser */
        $obUser = Subscriber::with('categories')->getByEmail($sEmail)->first();
        if(empty($obUser)) {
            $obUser = Subscriber::create([
                'email' => $sEmail,
                'name' => $sUserName,
                'surname' => $sUserSurname,
            ]);
        }
        
        //Check user categories
        if(empty($iCategoryId)) {
            return true;
        }
        
        $arCategories = $obUser->categories;
        if(!$arCategories->isEmpty()) {
            /** @var Category $obCategory */
            foreach($arCategories as $obCategory) {
                if($obCategory->id == $iCategoryId) {
                    return true;
                }
            }
        }
        
        //Attache category
        $obUser->categories()->attache($iCategoryId);
        $obUser->save();
        
        return true;
    }
}