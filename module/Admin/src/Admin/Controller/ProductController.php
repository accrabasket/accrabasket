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
use Admin\Model\basketapi;
class ProductController extends AbstractActionController {
    public function __construct() {
        $this->view =  new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();     
        //$this->basketObj = new basketapi();     
    }
    public function indexAction() {
        $request = array();
        $request['method'] = 'getProductList';
        $request['page'] = 1;
        $request['pagination'] = 'pagination';
        $productList = $this->commonObj->curlhitApi($request);
        $productList = json_decode($productList, true);
        if($productList['status'] == 'success') {
            $this->view->productList = $productList['data'];            
            $this->view->count = $productList['totalRecord'];
        }
        return $this->view;
    }
    public function addproductAction() {
        $request = (array) $this->getRequest()->getQuery();
        if (!empty($request)) {
            $request['method'] = 'getProductList';
            $productList = $this->commonObj->curlhitApi($request);
            $productList = json_decode($productList, true);
            if ($productList['status'] == 'success') {
                $this->view->productList = $productList['data'][$request['id']];
                $this->view->productImage = $productList['productimage'];
                $this->view->attributeImage = $productList['attributeimage'];
                $this->view->imageRootPath = $productList['imageRootPath'];
            }
        }
//        print_r($this->view->productList);die;
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
    public function importcsvAction() {
        return $this->view;
    }
    
    public function importproductAction() {
        ini_set('max_execution_time', -1);
        //move_uploaded_file($_FILES["product_csv"]["tmp_name"],'productimport.csv');
        $dataArr = array();
        if (($handle = fopen($_FILES["product_csv"]["tmp_name"], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataArr[] = $data;
            }
            fclose($handle);
        }
        $params = array();
        //$params['data'] = $dataArr;
        $totalNumOfProduct = count($dataArr);
        for ($i = 0; $i < $totalNumOfProduct; $i++){
            if($i==0){
                
            }else{
                $counter = 0;
                $index = 1;
                $data = array();
                $featuredBulletsDetails = array();
                $attributeDetails = array();
                foreach($dataArr[0] as $column) {
                    $column = trim(strtolower($column));
                    switch($column) {
                        case 'product name':
                            $data['product_name'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'product desc':
                            $data['product_desc'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'category name':
                            $data['category_name'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'product image':
                            $data['product_image'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'nutrition':
                            $data['nutrition'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;                        
                        case 'nutrition_image':
                            $data['nutrition_image'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;                        
                        case 'tax':
                            $data['tax'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'attribute name':
                            $data['attribute_name'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;                        
                        case 'unit':
                            $data['unit'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'quantity':
                            $data['quantity'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'attribute image':
                            $data['attribute_image'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'commission type':
                            $data['commission_type'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';                            
                            break;
                        case 'commission value':
                            $data['commission_value'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';                            
                            break;
                        case 'commition value':
                            $data['commission_value'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';                            
                            break;
                        case 'brand name':
                            $data['brand_name'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;                          
                        case 'product attribute':
                            $attributeName = '';
                            if(!empty($dataArr[$i][$counter])) {
                                $attributeName = $dataArr[$i][$counter];
                            }
                            break;            
                        case 'product attribute value':
                            
                            if(!empty($dataArr[$i][$counter]) && !empty($attributeName)) {
                                $attributeDetails[$attributeName] = $dataArr[$i][$counter]; 
                            }
                            break;                            
                        case 'feature bullets':
                            if(!empty($dataArr[$i][$counter])) {
                                $featuredBulletsDetails[] = $dataArr[$i][$counter]; 
                            }
                            break;                          
                                 
                    }
                    $counter++;
                }
                $data['method'] = 'addProductByCsv';
                $data['custom_info'] = $attributeDetails;
                $data['bullet_desc'] = $featuredBulletsDetails;
                $response[$data['product_name']] = json_decode($this->commonObj->curlhitApi($data));
            }
        }
        $this->flashMessenger()->addMessage('product Added :'.  json_encode($response));    
        return $this->redirect()->toUrl($GLOBALS['HTTP_SITE_ADMIN_URL'].'product');
    }
    
    public function saveproductAction() {
        $saveCategory = array();
        $request = (array)$this->getRequest()->getPost();
        
        if(!empty($request)){
            $attribute = array();
            if (!empty($request['attribute_name'])) {
                for ($i = 0; $i < count($request['attribute_name']); $i++) {
                    $index = $i;
                    $attribute[$i]['name'] = $request['attribute_name'][$i];
                    $attribute[$i]['unit'] = $request['attribute_unit'][$i];
                    $attribute[$i]['quantity'] = $request['attribute_quantity'][$i];
                    if (!empty($request['attribute_commission_value'][$i])) {
                        $attribute[$i]['commission_value'] = $request['attribute_commission_value'][$i];
                        $attribute[$i]['commission_type'] = $request['attribute_commission_type'][$i];
                    }
                    if(!empty($request['attribute_discount_value'][$i]) && !empty($request['attribute_discount_type'][$i])){
                        $attribute[$i]['attribute_discount_type'] = $request['attribute_discount_type'][$i] ;
                        $attribute[$i]['attribute_discount_value'] = $request['attribute_discount_value'][$i] ; 
                     }
                    if (!empty($request['attribute_id'][$i])) {
                        $attribute[$i]['id'] = $request['attribute_id'][$i];
                    }
                    if (!empty($_FILES['attribute_img_'.$index]['name'][0])) {
                        foreach ($_FILES['attribute_img_'.$index]['name'] as $key => $val) {
                            
                            if(file_exists($_FILES['attribute_img_'.$index]['tmp_name'][$key])) {
                                $base64 = 'data:image/' . $_FILES['attribute_img_'.$index]['type'][$key] . ';base64,' . base64_encode(file_get_contents($_FILES['attribute_img_'.$index]['tmp_name'][$key]));
                                $attribute[$i]['images'][] = $base64;
                            }
                        }
                    }                    
                }
            }
           $attributes['attribute'] = $attribute;
           $product['product_name'] = $request['product_name'];
           $product['brand_name'] = $request['brand_name'];
           $product['nutrition'] = $request['nutrition'];
           $product['category_id'] = $request['category_id'];
           $product['hotdeals'] = !empty($request['hotdeals'])?$request['hotdeals']:0;
           $product['offers'] = !empty($request['offers'])?$request['offers']:0;
           $product['new_arrival'] = !empty($request['new_arrival'])?$request['new_arrival']:0;
           if(!empty($request['product_discount_value']) && !empty($request['product_discount_type'])){
              $product['product_discount_value'] = $request['product_discount_value'] ;
              $product['product_discount_type'] = $request['product_discount_type'] ; 
           }
           $featureArr = array();
           if(!empty($request['custom_title'])){
               foreach ($request['custom_title'] as $key => $value) {
                   $featureArr[$value] = $request['custom_dis'][$key];
                }
                $product['custom_info'] = json_encode($featureArr) ;
           }
           $product['status'] = $request['status'] ;
           $product['product_desc'] = $request['product_desc'];
           if(!empty($request['tax_id'])){
               $product['tax_id'] = $request['tax_id'];
           }
           if(!empty($request['id'])){
               $product['id'] = $request['id'];
           }
        }
        if(!empty($_FILES['product_img']))
        {
            $images_array=array();
             foreach($_FILES['product_img']['name'] as $key=>$val){
                if(file_exists($_FILES['product_img']['tmp_name'][$key])) { 
                    $product['images'][] = $base64 = 'data:image/' . $_FILES['product_img']['type'][$key] . ';base64,' . base64_encode(file_get_contents($_FILES['product_img']['tmp_name'][$key]));
                }
            }
        }        
        if(!empty($_FILES['nutrition_image']))
        {
            if(file_exists($_FILES['nutrition_image']['tmp_name'])) { 
                $product['nutrition_img'][] = $base64 = 'data:image/' . $_FILES['nutrition_image']['type']. ';base64,' . base64_encode(file_get_contents($_FILES['nutrition_image']['tmp_name']));
            }
        }       
        $params = array();
        $params = array_merge($product,$attributes);
        $params['method'] = 'addEditProduct';    
        $saveCategory = $this->commonObj->curlhitApi($params);
        $response = json_decode($saveCategory, true);
        //print_r($response);die;
        if($response['status'] == 'success'){
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'].'product';
            header('Location:'.$path);
        }else{
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'].'product/addproduct';
            header('Location:'.$path);
        }
        exit;
        
    }

    function deleteProductAction() {
        $request = (array)$this->getRequest()->getQuery();
        if(!empty($request['product_id'])) {
            $request['method'] = 'deleteproduct';
            $deleteStatus = $this->commonObj->curlhitApi($request);
            $response = json_decode($deleteStatus, true);
            if($response['status'] == 'success'){
                $this->flashMessenger()->addMessage('product Deleted');  
            }else{
                $this->flashMessenger()->addMessage('product deletion failed');  
            }        
        }
        $path = $GLOBALS['HTTP_SITE_ADMIN_URL'].'product';
        header('Location:'.$path);        
        exit;
    }
    function getProductListAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'getProductList';
        if(!empty($request['page'])){
            $request['pagination'] = 'pagination';
        }
        echo $productList = $this->commonObj->curlhitApi($request);
        exit;
    }
    
    function orderlistAction() {
       $request = (array) $this->getRequest()->getQuery();
        return $this->view;
    }
    
    function orderdetailsAction() {
       $request = (array) $this->getRequest()->getQuery();
       if (!empty($request['order_id'])) {
            $request['method'] = 'orderlist';
            $productList = $this->commonObj->curlhitApi($request,'customer');
            $productList = json_decode($productList,true);
            if(!empty($productList['data'])){
                $this->view->productDetails = $productList;
            }
        }
       return $this->view;
    }
    
    function getOrderListAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'orderlist';
        $request['pagination'] = 1;
        if(!empty($request['page'])) {
            $request['page'] = $request['page'];
        }
        echo $productList = $this->commonObj->curlhitApi($request,'customer');
        exit;
    }
    
    function stockAction() {
       return $this->view; 
    }
    function stockListAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'stockList';
        $request['out_of_stock'] = 1;
        $request['pagination'] = 1;
        if(!empty($request['page'])) {
            $request['page'] = $request['page'];
        }
        echo $productList = $this->commonObj->curlhitApi($request,'application');
        exit;        
    }
    
    function merchantListAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'getMarchantList';
        echo $productList = $this->commonObj->curlhitApi($request);
        exit;        
    }
}
