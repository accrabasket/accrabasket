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
        $query = (array) $this->getRequest()->getQuery();
        $request['method'] = 'getProductList';
        if(!empty($request['page'])){
            $request['pagination'] = 'pagination';
        }else {
            //$request['page'] = 1;
            //$request['pagination'] = 'pagination';            
        }
        $request['all_product'] = 1;
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        $productList = $this->commonObj->curlhitApi($request);
        if(!empty($query['download_csv'])) {
            $this->downloadCsv($productList);
        }
        echo $productList;
        exit();
    }
    public function downloadCsv($data) {
    $filename = "product_list_" . date("Y-m-d") . ".csv";
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");
 
    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
 
    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");        
        $data = json_decode($data, true);
        //$csvData = '';
        if(!empty($data['data'])) {
            $data = $data['data'];
            $counter = 0;
            $csvData[$counter][]= 'product id';
            $csvData[$counter][]= 'product name';
            $csvData[$counter][]= 'category name';
            $csvData[$counter][]= 'atribute name';
            $csvData[$counter][]= 'atribute id';
            $csvData[$counter][]= 'quantity';
            $csvData[$counter][]= 'store name';
            $csvData[$counter][]= 'price';
            $csvData[$counter][]= 'stock';
            $counter ++;
            foreach($data as $row) {
                if(!empty($row['atribute'])) {
                    foreach ($row['atribute'] as $key => $value) {
                        $csvData[$counter][]= $row['id'];
                        $csvData[$counter][] = $row['product_name'];
                        $csvData[$counter][] = $row['category_name'];
                        $csvData[$counter][] = $value['name'];
                        $csvData[$counter][] = $value['id'];
                        $csvData[$counter][] = $value['quantity'].' '.$value['unit'];

                        $counter++;
                    }
                }
                
            }
        }
        $df = fopen("php://output", 'w');
        foreach ($csvData as $row) {
            fputcsv($df, $row);
        }
    fclose($df);
    die();          
        echo $csvData; exit();        
    }
    
    public function exportcsvAction() {
        return $this->view;
    }
    
    public function importinventryAction() {
        ini_set('max_execution_time', -1);
        $dataArr = array();
        if (($handle = fopen($_FILES["product_csv"]["tmp_name"], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $dataArr[] = $data;
            }
            fclose($handle);
        }
        $params = array();
        $totalNumOfProduct = count($dataArr);
        for ($i = 0; $i < $totalNumOfProduct; $i++) {
            if ($i == 0) {
                
            } else {
                $counter = 0;
                $index = 1;
                $data = array();
                $featuredBulletsDetails = array();
                foreach ($dataArr[0] as $column) {
                    $column = trim(strtolower($column));
                    switch ($column) {
                        case 'product name':
                            $data['product_name'] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'product id':
                            $data['product_id'] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'category name':
                            $data['category_name'] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'code':
                            $data['merchant_product_code'][] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'atribute id':
                            $data['attribute_id'][] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'atribute name':
                            $data['atribute_name'][] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'store name':
                            $data['store_name'] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'price':
                            $data['price'][] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'stock':
                            $data['stock'][] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'stoock':
                            $data['stock'][] = !empty($dataArr[$i][$counter]) ? $dataArr[$i][$counter] : '';
                            break;
                        case 'url':
                            if (!empty($dataArr[$i][$counter])) {
                                
                                $dataFromOtherSite = $this->getProductDetails($dataArr[$i][$counter], $data);
                            }
                            break;
                    }
                    $counter++;
                }
                if(!empty($dataFromOtherSite)) {
                    $exportAsCsv[] = $dataFromOtherSite;
                    $dataFromOtherSite = array();
                }else{
                $data['method'] = 'addInventryByCsv';
                $data['merchant_id'] = $this->session['user']['data'][0]['id'];
                $response[$data['product_name']] = json_decode($this->commonObj->curlhitApi($data));
                }
            }
        }
        if(!empty($exportAsCsv)) {
            echo "<pre>";print_r($exportAsCsv);die;
        }
        $this->flashMessenger()->addMessage('Inventry updated :' . json_encode($response));
        return $this->redirect()->toUrl($GLOBALS['HTTP_SITE_MERCHANT_URL'] . 'product');
    }

    public function getProductDetails($url, $data) {
        //error_reporting(E_ALL); ini_set('display_errors', 1);
     // Retrieve the DOM from a given URL
         $html = file_get_contents($url);
     // Find all "A" tags and print their HREFs
         //echo $html;
        $dom = new \DOMDocument(); 

        /*** load the html into the object ***/ 
        $dom->loadHTML($html); 
        $finder = new \Zend\Dom\DOMXPath($dom);
        /*** discard white space ***/ 
        $dom->preserveWhiteSpace = false;
        $arr = array();
        $classname="_35KyD6";
        $nodes = $finder->query("//*[contains(@class, '$classname')]");
        $arr['product_name'] = $nodes->item(0)->nodeValue;  

        $classname="_1vC4OE _3qQ9m1";
        $nodes = $finder->query("//*[contains(@class, '$classname')]");
        $arr['price'] = $nodes->item(0)->nodeValue; 

        $classname="VGWI6T _1iCvwn _9Z7kX3";
        $nodes = $finder->query("//*[contains(@class, '$classname')]");
        $arr['discount'] = $nodes->item(0)->nodeValue;  


        $classname="fUBI-_";
        $nodes = $finder->query("//*[contains(@class, '$classname')]");
        $arr['size'] = $nodes->item(0)->nodeValue;     
        return $arr;
    }
    
    public function inventryAction() {
        $request = (array) $this->getRequest()->getQuery();
        $this->view->inventryDetails = array();
        if (!empty($request)) {
            $request['method'] = 'getProductList';
            $request['all_product'] = 1;
            $request['merchant_id'] = $this->session['user']['data'][0]['id'];
            $productList = $this->commonObj->curlhitApi($request);
            $productList = json_decode($productList, true);
            if ($productList['status'] == 'success') {
                $this->view->productList = $productList['data'][$request['id']];
                $this->view->inventryDetails = $productList['inventry_detail'];
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
        $this->view->store_id = 0;
        if(empty($request['store_id']) && !empty($storeList['data'])) {
            $storeData = array_values($storeList['data']);            
            $this->view->store_id = $storeData[0]['id'];
        }else{
            $this->view->store_id = $request['store_id'];
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
            $request['all_product'] = 1;
            $productList = $this->commonObj->curlhitApi($request);
            $productList = json_decode($productList, true);
            if ($productList['status'] == 'success') {
                $this->view->productList = $productList['data'][$request['id']];
            }
        }
        $params = array();
        $params['page'] = 1;
        $params['pagination'] = 'pagination';
        $params['method'] = 'stockList';
        $params['merchant_id'] = $this->session['user']['data'][0]['id'];
        $storeList = $this->commonObj->curlhitApi($params);
        $storeList = json_decode($storeList, true);
        if ($storeList['status'] == 'success') {
            $this->view->stockList = $storeList['data'];
        }
        
        return $this->view;
    }
    
    function orderlistAction() {
       $request = (array) $this->getRequest()->getQuery();
        return $this->view;
    }
    
    function getOrderListAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'orderlist';
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        $request['pagination'] = 1;
        if(!empty($request['page'])) {
            $request['page'] = $request['page'];
        }
        $productList = $this->commonObj->curlhitApi($request,'customer');
        $productList = json_decode($productList, true);
        $productList['data'] = array_values($productList['data']);
        echo json_encode($productList);
        exit;
    }
    
    function stockInAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'stockList';
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        $request['pagination'] = 1;
        if(!empty($request['page'])) {
            $request['page'] = $request['page'];
        }
        $storeList = $this->commonObj->curlhitApi($request);
        $storeList = json_decode($storeList, true);
        echo json_encode($storeList);
        exit;
    }
    
    function changestatusAction() {
        $request = (array)$this->getRequest()->getPost();
        $request['method'] = 'updateOrderstatus';
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        
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
        $request['merchant_id'] = $this->session['user']['data'][0]['id'];
        $request['pagination'] = 1;
        if(!empty($request['page'])) {
            $request['page'] = $request['page'];
        }
        echo $productList = $this->commonObj->curlhitApi($request);
        exit;        
    }

    function storelistAction() {
        $params = array();
        $params['method'] = 'storeList';
        $params['merchant_id'] = $this->session['user']['data'][0]['id'];
        echo $storeList = $this->commonObj->curlhitApi($params);
        exit;        
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
}
