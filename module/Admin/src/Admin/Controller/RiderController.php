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
use Admin\Model\riderModel;
class RiderController extends AbstractActionController {
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();     
        $this->riderModel = new riderModel();     
    }
    public function indexAction() {
        return $this->view;
    }
    public function riderListAction(){
        $inputParams = (array)$this->getRequest()->getPost();
        $riderList = $this->riderModel->getRiderList($inputParams);
        echo $riderList;
        exit;        
    }
    public function addriderAction() {
        $inputParams = (array)$this->getRequest()->getQuery();        
        $locationListResponse = $this->commonObj->getLocationList();
        $locationListArr = json_decode($locationListResponse, true);
        if($locationListArr['status'] == 'success') {
            $this->view->locationList = $locationListArr['data']; 
        }
        $this->view->params = $inputParams;
        return $this->view;
    } 
    
    function fetchRidersByStoreIdAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'getRidersByStoreId';
        echo $productList = $this->commonObj->curlhitApi($request);
        exit;
    }    
    
    function assignOrderAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'assignordertorider';
        
        echo $response = $this->commonObj->curlhitApi($request, 'application/customer');
        $response = json_decode($response, true);
        if($response['status'] == 'success'){
            $this->flashMessenger()->addMessage($response['msg']);
        }         
        exit;
    }    
    
    public function saveriderAction() {
        $saveCategory = array();
        $postParams = (array)$this->getRequest()->getPost();
        if(!empty($postParams['password']) && !empty($postParams['confirm_password'])) {
            $postParams['password'] = md5($postParams['password']);
            $postParams['confirm_password'] = md5($postParams['confirm_password']);
        }else{
            unset($postParams['password']);
        }
        $postParams['method'] = 'addEditRider';
        
        $saveRiderResponse = $this->commonObj->curlhitApi($postParams);
        $response = json_decode($saveRiderResponse, true);
        if($response['status'] == 'success'){
            $this->flashMessenger()->addMessage($response['msg']);
        }        
        echo $saveRiderResponse;
        exit;
        
    }
}
