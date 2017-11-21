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
        $productList = $this->commonObj->curlhitApi($request);
        $productList = json_decode($productList, true);
        if($productList['status'] == 'success') {
            $this->view->productList = $productList['data'];
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
        move_uploaded_file($_FILES["product_csv"]["tmp_name"],'productimport.csv');
        $dataArr = array();
        if (($handle = fopen("productimport.csv", "r")) !== FALSE) {
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
                $data = array();
                foreach($dataArr[0] as $column) {
                    $column = trim($column);
                    switch($column) {
                        case 'product name':
                            $data['product_name'] = $dataArr[$i][$counter];
                            break;
                        case 'product desc':
                            $data['product_desc'] = $dataArr[$i][$counter];
                            break;
                        case 'category name':
                            $data['category_name'] = $dataArr[$i][$counter];
                            break;
                        case 'product image':
                            $data['product_image'][] = $dataArr[$i][$counter];
                            break;
                        case 'tax':
                            $data['tax'] = $dataArr[$i][$counter];
                            break;
                        case 'attribute name':
                            $data['attribute_name'][] = $dataArr[$i][$counter];
                            break;
                        case 'unit':
                            $data['unit'][] = $dataArr[$i][$counter];
                            break;
                        case 'quantity':
                            $data['quantity'][] = $dataArr[$i][$counter];
                            break;
                        case 'attribute image':
                            $data['attribute_image'][] = $dataArr[$i][$counter];
                            break;
                        case 'commission type':
                            $data['commission_type'][] = $dataArr[$i][$counter];                            
                            break;
                        case 'commission value':
                            $data['commission_value'][] = $dataArr[$i][$counter];                            
                            break;
                        case 'commition value':
                            $data['commission_value'][] = $dataArr[$i][$counter];                            
                            break;                        
                         
                        
                    }
                    $counter++;
                }
                $data['method'] = 'addProductByCsv';
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
                    $index = $i + 1;
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
                }
            }

           $attributes['attribute'] = $attribute;
           $product['product_name'] = $request['product_name'];
           $product['category_id'] = $request['category_id'];
           if(!empty($request['product_discount_value']) && !empty($request['product_discount_type'])){
              $product['product_discount_value'] = $request['product_discount_value'] ;
              $product['product_discount_type'] = $request['product_discount_type'] ; 
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
        $params = array();
        $params = array_merge($product,$attributes);
        $params['method'] = 'addEditProduct';
        
        $saveCategory = $this->commonObj->curlhitApi($params);
        $response = json_decode($saveCategory, true);
        if(!isset($_FILES['product_img']) && !empty($_FILES['product_img']))
        {
            $images_array=array();
             foreach($_FILES['product_img']['name'] as $key=>$val){
                $uploadfile = $_FILES['product_img']["tmp_name"][$key];
                $imagePath = $GLOBALS['PRODUCTIMAGEPATH'].'/'.$response['data']['product_id'].'/';;
                @mkdir($imagePath, '0777', true);
                $target_file = $imagePath.$_FILES['product_img']['name'][$key];
                if(move_uploaded_file($_FILES["product_img"]["tmp_name"][$key], "$imagePath".$_FILES["product_img"]["name"][$key])){
                    $images_array[] = $target_file;
                }
            }
        }
        if (!empty($response['data']['attribute'])) {
            foreach ($response['data']['attribute'] as $key => $value) {
                $index = $key+1;
                if (isset($_FILES['attribute_img_'.$index])) {
                    $images_array = array();
                    foreach ($_FILES['attribute_img_'.$index]['name'] as $key => $val) {
                        $uploadfile = $_FILES['attribute_img_'.$index]["tmp_name"][$key];
                        $imagePath = $GLOBALS['ATTRIBUTEIMAGEPATH'] . '/' . $value . '/';
                        @mkdir($imagePath, '0777', true);
                        $target_file = $imagePath . $_FILES['attribute_img_'.$index]['name'][$key];
                        if (move_uploaded_file($_FILES['attribute_img_'.$index]["tmp_name"][$key], "$imagePath" . $_FILES['attribute_img_'.$index]["name"][$key])) {
                            $images_array[] = $target_file;
                        }
                    }
                }
            }
        }


        if($response['status'] == 'success'){
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'].'product';
            header('Location:'.$path);
        }else{
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'].'product/addproduct';
            header('Location:'.$path);
        }
        exit;
        
    }
}
