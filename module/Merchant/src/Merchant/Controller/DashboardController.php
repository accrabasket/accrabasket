<?php
namespace Merchant\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Merchant\Model\common;

class DashboardController extends AbstractActionController {
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();     
    }

    public function storeAction() {
        
        $inputParams['pagination'] = 1;
        $locationList = $this->commonObj->getLocationList($inputParams);
        $locationListArr = json_decode($locationList, true);
        if ($locationListArr['status'] == 'success') {
            $locationListData = $locationListArr['data'];
        }
        $request = (array) $this->getRequest()->getQuery();
        if (!empty($request['id'])) {
            $request['method'] = 'storeList';
            $request['merchant_id'] = $this->session['user']['data'][0]['id'];
            $storeResponse = $this->commonObj->curlhitApi($request);
            if(!empty($storeResponse)){
               $storeResponse =  json_decode($storeResponse,TRUE);
               $this->view->storeList = $storeResponse['data'][$request['id']];
            }
            
        }
        $this->view->locationList = $locationListData;
        return $this->view;
    }

    public function savestoreAction() {
        $request = (array) $this->getRequest()->getPost();
        $request['method'] = 'addEditStore';
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        $saveStoreResponse = $this->commonObj->curlhitApi($request);
        $response = json_decode($saveStoreResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveStoreResponse;
        exit;
    }

    public function managestoreAction() { 
        $request = array();
        $request['method'] = 'storeList';
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        $storeResponse = $this->commonObj->curlhitApi($request);
        $response = json_decode($storeResponse, true);
        $this->view->storeList = $response['data'];
        return $this->view;
    }

    

    public function deleteStoreAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['method'] = 'deleteStore';
        $deleteCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($deleteCategory, true);
        if ($response['status'] == 'success') {
            $path = $GLOBALS['HTTP_SITE_MERCHANT_URL'] . 'managestore';
            header('Location:' . $path);
        }
        exit;
    }
    public function newcompanylistAction(){
        return $this->view;
    }

    public function companylistAction(){
        $request = $this->getRequest()->getQuery();
        $params = array();
        $params["status"] = isset($request["status"])?$request["status"]:'';        
        $newcompanylist = $this->commonObj->curlhit($params, 'getcompanylist');
        echo $newcompanylist;
        exit();
    }
    public function activateordeactivatecompanyAction(){
        $request = $this->getRequest()->getPost();
        $params = array();
        $params['company_id'] = $request["company_id"]; 
        $params['status'] = $request["status"];
        $params['activate_by'] = $this->session['user']->data[0]->id;
        $response = $this->commonObj->curlhit($params, 'activateordeactivatecompany');
        echo $response;
        exit();
    }    


    public function updateprofileAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['id'] = $this->session['user']['data'][0]['id'];
        $request['method'] = 'getMarchantList';
        $getMarchantList = $this->commonObj->curlhitApi($request);
        $getMarchantList = json_decode($getMarchantList, true);
        if (!empty($getMarchantList['data'])) {
            $this->view->marchantData = $getMarchantList['data'][0];
        }
        return $this->view;
    }
    
    public function saveMerchantAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'saveMerchant';
        $postParams['id'] = $this->session['user']['data'][0]['id'];
        $saveMerchantResponse = $this->commonObj->curlhitApi($postParams);
        $response = json_decode($saveMerchantResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveMerchantResponse;
        exit;        
    }
    
    public function emailsetuplistAction() {
        return $this->view;
    }
    public function saveemaildataAction(){
        $request = (array)$this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'saveemailtemplate');
        exit;
    }
    public function gettemplatelistAction(){
        echo $saveEmail = $this->commonObj->curlhit('', 'gettemplatelist');
        exit;
    }
    
    public function deleteEmailTemplateAction(){
        $request = (array)$this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'deleteEmailTemplate');
        exit;
    }
    public function editEmailTemplateAction(){
        $request = (array)$this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'editEmailTemplate');
        exit;
    }

    public function purchagesubscriptionAction(){
        return $this->view;
    }
    
    public function getallservicedata() {
        $request = (array)$this->getRequest()->getPost();
        $saveEmail = $this->commonObj->curlhit($request, 'getServicelist');
        print_r($saveEmail);
        exit;
    }
    public function packagelistAction() {
        return $this->view;
    } 
    public function cartAction() {
        return $this->view;
    } 
     public function checkoutAction() {
        return $this->view;
    } 
}
