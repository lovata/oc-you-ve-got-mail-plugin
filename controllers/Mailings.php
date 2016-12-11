<?php namespace Lovata\YouVeGotMail\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Lovata\YouVeGotMail\Models\Category;
use Lovata\YouVeGotMail\Models\Mailing;
use Lovata\YouVeGotMail\Models\Settings;
use Lovata\YouVeGotMail\Models\Subscriber;
use October\Rain\Database\Collection;
use October\Rain\Support\Facades\Flash;
use System\Models\MailTemplate;

/**
 * Class Mailings
 * @package Lovata\YouVeGotMail\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Mailings extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend.Behaviors.RelationController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    protected $obRequest;

    public function __construct(Request $obRequest)
    {
        parent::__construct();
        BackendMenu::setContext('Lovata.YouVeGotMail', 'main-menu-item', 'side-menu-item2');
        $this->obRequest = $obRequest;
    }

    /**
     * Send mails
     * @return mixed
     */
    public function onFormMailSend() {

        $arElementsID = $this->obRequest->input('checked');

        if(empty($arElementsID) || !is_array($arElementsID)) {
            return $this->listRefresh();
        }

        //Get queue title
        $sQueueTitle = Settings::getValue('queue_title');
        if(empty($sQueueTitle)) {
            $sQueueTitle = 'mail';
        }
        
        foreach($arElementsID as $iElementID) {
            
            //Get mailing
            /** @var Mailing $obElement */
            $obElement = Mailing::find($iElementID);
            if(empty($obElement)) {
                continue;
            }
            
            //Get mail template code
            $obTemplate = $obElement->template;
            if(empty($obTemplate)) {
                continue;
            }
            
            //Get category
            /** @var Category $obCategory */
            $obCategory =$obElement->category;
            
            //Get user list
            if(empty($obCategory)) {
                /** @var Collection $arUsers */
                $arUsers = Subscriber::doesntHave('categories')->get();
            } else {
                $arUsers = $obCategory->subscribers;
            }
            
            if($arUsers->isEmpty()) {
                continue;
            }
            
            //Get mail data
            $arData = [
                'subject' => $obElement->title,
                'content' => $obElement->content,
            ];
            if(!$obElement->data_source == 'default') {
                
                $arDataSources = Settings::getValue('external_data_sources');
                foreach ($arDataSources as $arDetails) {
                    if($obElement->data_source == $arDetails['source_class'] && class_exists($arDetails['source_class']) && method_exists($arDetails['source_class'], $arDetails['data_method'])) {
                        $arAdditionData = $arDetails['source_class']::$arDetails['data_method']($obElement->data_source_id);
                        if(!empty($arAdditionData && is_array($arAdditionData))) {
                            $arData =  array_merge($arData, $arAdditionData);
                        }
                        
                    }
                }
            }
            
            //Send messages
            /** @var Subscriber $obUser */
            foreach ($arUsers as $obUser) {
                
                if(empty($obUser->email)) {
                    continue;
                }
                
                $arData['email'] = $obUser->email;
                $arData['name'] = $obUser->name;
                $arData['surname'] = $obUser->surname;

                $sMail = $obUser->email;
                
                Mail::queueOn($sQueueTitle, $obTemplate->code, $arData, function($message) use ($sMail, $arData) {
                    $message->to($sMail);
                    $message->subject($arData['subject']);
                });
            }
        }

        Flash::success(Lang::get('lovata.youvegotmail::lang.messages.send_success'));
        return $this->listRefresh();
    }

    /**
     * Ajax delete list
     * @return mixed
     */
    public function index_onDelete() {

        $arElementsID = $this->obRequest->input('checked');

        if(empty($arElementsID) || !is_array($arElementsID)) {
            return $this->listRefresh();
        }

        foreach($arElementsID as $iElementID) {
            if(!$obElement = Mailing::find($iElementID))
                continue;

            $obElement->delete();
        }

        Flash::success(Lang::get('lovata.youvegotmail::lang.message.delete_success'));
        return $this->listRefresh();
    }
}