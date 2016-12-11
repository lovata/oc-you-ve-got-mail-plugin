<?php namespace Lovata\YouVeGotMail\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Lang;
use Flash;
use Illuminate\Http\Request;
use Lovata\YouVeGotMail\Models\Subscriber;

/**
 * Class Subscribers
 * @package Lovata\YouVeGotMail\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Subscribers extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend.Behaviors.RelationController',];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    /** @var Request */
    protected $obRequest;

    public function __construct(Request $obRequest)
    {
        parent::__construct();
        BackendMenu::setContext('Lovata.YouVeGotMail', 'main-menu-item', 'side-menu-item3');
        $this->obRequest = $obRequest;
    }
    /**
     * Ajax удаление элементов
     * @return mixed
     */
    public function index_onDelete() {

        $arElementsID = $this->obRequest->input('checked');

        if(empty($arElementsID) || !is_array($arElementsID)) {
            return $this->listRefresh();
        }

        foreach($arElementsID as $iElementID) {
            if(!$obElement = Subscriber::find($iElementID))
                continue;

            $obElement->delete();
        }

        Flash::success(Lang::get('lovata.youvegotmail::lang.message.delete_success'));
        return $this->listRefresh();
    }

}