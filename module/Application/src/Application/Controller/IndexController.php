<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Admin\Model\common;
class IndexController extends AbstractActionController
{
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();
    }
    public function indexAction()
    {
        return $this->view;
    }
    
    public function signupAction(){
        //$countryListResponse = $this->commonObj->curlhit('', 'getcountrylist');
        //$countryList = json_decode($countryListResponse, true);  
        //$this->view->countryList = $countryList;
        return $this->view;
    }
    
    public function statelistAction() {
        $request = $this->getRequest();
        $params = array();
        $inputParams = $request->getPost();   
        if(isset($inputParams['country_id'])) {
            $params['country_id'] = $inputParams['country_id'];
        }
        $stateListResponse = $this->commonObj->curlhit($params, 'getstatelist');
        echo $stateListResponse;die;
    }
    public function addmerchantAction() {
        $request = $this->getRequest()->getPost();
        $params = array();
        $params['company_name'] = $request['name'];
        $params['password'] = $request['password'];
        $params['confirm_password'] = $request['confirm_password'];
        $params['password'] = md5($params['password']);
        $params['confirm_password'] = md5($params['confirm_password']);      
        $params['ic_number'] = $request['ic_number'];
        $params['address'] = $request['address'];
        $params['email'] = $request['email_id'];
        $params['phone_number'] = $request['phone_number'];
        $params['bank_name'] = $request['bank_name'];
        $params['bank_account_number'] = $request['bank_account_number'];
        $inputParams['parameters'] = json_encode($params);
        $response = $this->commonObj->curlhit($inputParams, 'addcompany');
        $response = json_decode($response, true);
        print_r($response);die;
        if($response['status'] == true){
            $this->flashMessenger()->addMessage('Thank you for your registration, We will contact you soon!');
            return $this->redirect()->toRoute('admin', array('controller'=>'index', 'action'=>'login'));
        }
        echo json_encode($response);die;
    }    
    public function updatecompanyAction() {
        $request = $this->getRequest()->getQuery();
        $params = array();
        $params['user']['first_name'] = $request['name'];
        $params['user']['password'] = md5($request['password']);
        $params['company']['activation_code'] = $request['activation_code'];
        $inputParams['parameters'] = json_encode($params);
        $response = $this->commonObj->curlhit($inputParams, 'updatecompany');
        $response = json_decode($response, true);
        if($response['status'] == true){
            $this->flashMessenger()->addMessage('Thank you for your registration, We will contact you soon!');
            return $this->redirect()->toRoute('admin');
        }
        echo json_encode($response);die;
    }    
    public function activateAction()
    {
        $request = $this->getRequest()->getQuery();
        $params = array();
        if(isset($request['code']) && !empty($request['code'])){
            $params['activation_code'] = $request['code'];
            $params['status'] = 1;
            $companyDetailResponse = $this->commonObj->curlhit($params, 'getcompanylist', 'companycontroller');        
            $companyDetail = json_decode($companyDetailResponse, true);
            if($companyDetail['status']){
                $this->view->companyDetail = $companyDetail['data'][0];
            }
        }
        return $this->view;
    }    
    public function aboutusAction()
    {
        return new ViewModel();
    }
     public function servicesAction()
    {
        return new ViewModel();
    }
     public function pricingAction()
    {
        return new ViewModel();
    }
    public function faqAction()
    {
        return new ViewModel();
    }
    public function signinAction()
    {
        return new ViewModel();
    }
    public function forgetpasswordAction()
    {
        return new ViewModel();
    }
    public function contactusAction()
    {
        return new ViewModel();
    }
    public function pagenotfoundAction()
    {
        return new ViewModel();
    }    
}
