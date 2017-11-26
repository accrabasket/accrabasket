<?php
namespace Merchant\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Merchant\Model\common;
class ProductController extends AbstractActionController {
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();     
        //$this->basketObj = new basketapi();     
    }
    public function indexAction() {
        return $this->view;
    }
    
    public function getproductlistAction() {
        $request = (array) $this->getRequest()->getPost();
        $request['method'] = 'getProductList';
        if(!empty($request['page'])){
            $request['pagination'] = 'pagination';
        }
        $productList = $this->commonObj->curlhitApi($request);
        echo $productList;
        exit();

    }
    public function inventryAction() {
        $request = (array) $this->getRequest()->getQuery();
        if (!empty($request)) {
            $request['method'] = 'getProductList';
            $productList = $this->commonObj->curlhitApi($request);
            $productList = json_decode($productList, true);
            if ($productList['status'] == 'success') {
                $this->view->productList = $productList['data'][$request['id']];
            }
        }
        $params = array();
        $params['method'] = 'storeList';
        $params['merchant_id'] = $this->session['user']['data'][0]['id'];
        $storeList = $this->commonObj->curlhitApi($params);
        $storeList = json_decode($storeList, true);
        if ($storeList['status'] == 'success') {
            $this->view->storeList = $storeList['data'];
        }
        
        return $this->view;
    }
    public function taxListAction() {
        $request = array();
        $request['method'] = 'taxlist';
        $taxList = $this->commonObj->curlhitApi($request);
        
        echo $taxList;
        exit;
    }
    
    public function getCategoryListAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'categoryList';
        $getMarchantList = $this->commonObj->curlhitApi($postParams);
        echo $getMarchantList;
        exit;
    }    
    public function saveproductAction() {
        $saveCategory = array();
        $request = (array)$this->getRequest()->getPost();
        
        if(!empty($request)){
            $params = array();
            $params['product_id'] = $request['product_id'];
            $params['attribute_id'] = $request['attribute_id'];
            $params['store_id'] = $request['store_id'];
            $params['price'] = $request['attribute_price'];
            $params['stock'] = $request['stock'];
            $params['merchant_id'] = $this->session['user']['data'][0]['id'];
        }
        
        $params['method'] = 'addEditInventry';
//        echo'<pre>';
//                print_r($params);die;
        $saveCategory = $this->commonObj->curlhitApi($params);
        $response = json_decode($saveCategory, true);
        if($response['status'] == 'success'){
            $path = $GLOBALS['HTTP_SITE_MERCHANT_URL'].'product/storein';
            header('Location:'.$path);
        }else{
            $path = $GLOBALS['HTTP_SITE_MERCHANT_URL'].'inventry';
            header('Location:'.$path);
        }
        exit;
        
    }
    
    public function storeinAction() {
        $request = (array) $this->getRequest()->getQuery();
        if (!empty($request)) {
            $request['method'] = 'getProductList';
            $productList = $this->commonObj->curlhitApi($request);
            $productList = json_decode($productList, true);
            if ($productList['status'] == 'success') {
                $this->view->productList = $productList['data'][$request['id']];
            }
        }
        $params = array();
        $params['method'] = 'stockList';
        $params['merchant_id'] = $this->session['user']['data'][0]['id'];
        $storeList = $this->commonObj->curlhitApi($params);
        $storeList = json_decode($storeList, true);
        if ($storeList['status'] == 'success') {
            $this->view->stockList = $storeList['data'];
        }
        
        return $this->view;
    }
}
