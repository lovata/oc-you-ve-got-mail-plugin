<?php namespace Lovata\YouVeGotMail\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\Backend;
use BackendMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Lovata\YouVeGotMail\Models\Category;
use October\Rain\Support\Facades\Flash;

/**
 * Class Categories
 * @package Lovata\YouVeGotMail\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Categories extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend.Behaviors.RelationController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    
    /** @var Request Request */
    protected $obRequest;

    public function __construct(Request $obRequest)
    {
        parent::__construct();
        BackendMenu::setContext('Lovata.YouVeGotMail', 'main-menu-item', 'side-menu-item');
        $this->obRequest = $obRequest;
    }

    /**
     * Ajax delete category list
     * @return mixed
     */
    public function index_onDelete() {

        $arElementsID = $this->obRequest->input('checked');

        if(empty($arElementsID) || !is_array($arElementsID)) {
            return $this->listRefresh();
        }

        foreach($arElementsID as $iElementID) {
            if(!$obElement = Category::find($iElementID)) {
                continue;
            }

            $obElement->delete();
        }

        Flash::success(Lang::get('lovata.youvegotmail::lang.message.delete_success'));
        return $this->listRefresh();
    }
}