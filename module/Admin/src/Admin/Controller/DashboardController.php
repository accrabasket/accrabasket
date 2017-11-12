<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Admin\Model\common;
class DashboardController extends AbstractActionController {
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();        
    }

    public function countrylistAction() {
        $countryListResponse = $this->commonObj->curlhit('', 'getcountrylist');
        $countryList = json_decode($countryListResponse, true);
        if($countryList['status']){
            $this->view->countryList = $countryList['data'];
        }
        return $this->view;
    }

    public function statelistAction() {
        $stateListResponse = $this->commonObj->curlhit('', 'getstatelist');
        $stateList = json_decode($stateListResponse, true);
        if($stateList['status']){
            $this->view->stateList = $stateList['data'];
        }
        return $this->view;
    } 

    public function indexAction() {      
        return $this->view;
    }

    public function pricesaveAction() {
        $request = $this->getRequest()->getPost();
        $params = array();
        $params["monthly_service"] = $request["monthly_service"];
        $params["phone_number_charge"] = $request["phone_number"];
        $params["sms_pack_price"] =$request["sms_pack_price"];
        $params["nbr_of_sms_in_pack"] = $request["nbr_of_sms_in_pack"];
        $params["free_sms"] = $request["free_sms"];
        $inputParams['parameters'] = json_encode($params);
        //print_r($inputParams);die;
        $savePrice = $this->commonObj->curlhit($inputParams, 'pricesave');
        $savePrice = json_decode($savePrice);
        print_r($savePrice);die;
        if($savePrice['status']){
            $this->view->priceList = $priceList['data'];
        }
        print_r($SavePrice);
        return $this->view;
      
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


    public function emailsetupAction() {
        return $this->view;
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

    public function managemerchantAction() {
        $request = array();
        $request['method'] = 'getMarchantList';
        $getMarchantList = $this->commonObj->curlhitApi($request);
        $getMarchantList = json_decode($getMarchantList,true);
        if(!empty($getMarchantList['data'])){
            $this->view->marchantList = $getMarchantList['data'];
        }
        return $this->view;
    }
    
    public function addcategoryAction() {
        $request = (array) $this->getRequest()->getQuery();
        if (!empty($request)) {
            $request['method'] = 'categoryList';
            $categoryList = $this->commonObj->curlhitApi($request);
            $categoryList = json_decode($categoryList, true);
            if (!empty($categoryList['data'])) {
                foreach ($categoryList['data'] as $key => $value) {
                    $data = $value;
                }
                $this->view->categoryList = $data;
            }
        }

        return $this->view;
    }

    public function savecategoryAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'addEditCategory';
        $saveCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($saveCategory, true);
        if($response['status'] == 'success'){
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveCategory;
        exit;
        
    }
    
    public function managecategoryAction() {
        $request = array();
        $request['method'] = 'categoryList';
        $getMarchantList = $this->commonObj->curlhitApi($request);
        $getMarchantList = json_decode($getMarchantList,true);
        if(!empty($getMarchantList['data'])){
            $this->view->categoryList = $getMarchantList['data'];
        }
        return $this->view;
    } 
    
    public function getCategoryListAction() {
        $request = array();
        $return = array();
        $request['method'] = 'categoryList';
        $getMarchantList = $this->commonObj->curlhitApi($request);
        echo $getMarchantList;exit;
    }
    
    public function editMerchantAction() {
        $request = (array) $this->getRequest()->getQuery();
        
        if (!empty($request)) {
            $request['method'] = 'getMarchantList';
            $getMarchantList = $this->commonObj->curlhitApi($request);
            $getMarchantList = json_decode($getMarchantList, true);
            if (!empty($getMarchantList['data'])) {
                $this->view->marchantData = $getMarchantList['data'];
            }
        }
        return $this->view;
    }

    public function locationAction() {
        $locationListData = array();
        $inputParams = array();
        $inputParams['pagination'] = 1;
        $locationList = $this->commonObj->getLocationList($inputParams);
        $locationListArr = json_decode($locationList, true);
        if($locationListArr['status'] == 'success') {
            $locationListData = $locationListArr['data'];
        }
        $this->view->locationListData = $locationListData;
        return $this->view;
    }
    
    public function locationListAction() {
        $inputParams = (array)$this->getRequest()->getPost();
        $locationList = $this->commonObj->getLocationList($inputParams);
        echo $locationList;
        exit;
    }
    
    public function addlocationAction() {
        $inputParams = (array)$this->getRequest()->getQuery();
        $this->view->params = $inputParams;
        return $this->view;
    }         
    public function savelocationAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'addEditLocation';
        $saveCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($saveCategory, true);
        if($response['status'] == 'success'){
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveCategory;
        exit;
    }

    public function deleteCategoryAction() {
            $request = (array)$this->getRequest()->getQuery();
            $request['method'] = 'deleteCategory';
            $deleteCategory = $this->commonObj->curlhitApi($request);
            $response = json_decode($deleteCategory, true);
            if($response['status'] == 'success'){
                $path = $GLOBALS['HTTP_SITE_ADMIN_URL'].'dashboard/managecategory';
                header('Location:'.$path);
            }
            exit;
        }    
}
