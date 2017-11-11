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
        return $this->view;
    } 
    public function saveproductAction() {
        $saveCategory = array();
        $request = (array)$this->getRequest()->getPost();
        if(!empty($request)){
            $attribute = array();
           for($i = 0; $i < $request['index'] ; $i++){
               $index = $i+1;
               $attribute[$i]['name'] = $request['attribute_name_'.$index];
               $attribute[$i]['unit'] = $request['attribute_unit_'.$index];
               $attribute[$i]['quantity'] = $request['attribute_quantity_'.$index];
           }
           $attributes['attribute'] = $attribute;
           $product['product_name'] = $request['product_name'];
           $product['category_id'] = $request['category_id'];
           $product['status'] = $request['r1'] == 'on'?1:0;
           $product['product_desc'] = $request['product_desc'];
        }
        $params = array();
        $params = array_merge($product,$attributes);
        $params['method'] = 'addEditProduct';
        
        $saveCategory = $this->commonObj->curlhitApi($params);
        $response = json_decode($saveCategory, true);
        if(isset($_FILES['product_img']))
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
