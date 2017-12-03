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
        }
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
        for ($i = 0; $i < $totalNumOfProduct; $i++){
            if($i==0){
                
            }else{
                $counter = 0;
                $index = 1;
                $data = array();
                $featuredBulletsDetails = array();
                foreach($dataArr[0] as $column) {
                    $column = trim(strtolower($column));
                    switch($column) {
                        case 'product name':
                            $data['product_name'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'product id':
                            $data['product_id'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'category name':
                            $data['category_name'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'atribute id':
                            $data['attribute_id'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'atribute name':
                            $data['atribute_name'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'store name':
                            $data['store_name'] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'price':
                            $data['price'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                        case 'stock':
                            $data['stock'][] = !empty($dataArr[$i][$counter])?$dataArr[$i][$counter]:'';
                            break;
                           
                    }
                    $counter++;
                }
                $data['method'] = 'addInventryByCsv';
                $data['merchant_id'] = $this->session['user']['data'][0]['id'];
                $response[$data['product_name']] = json_decode($this->commonObj->curlhitApi($data));
            }
        }
        $this->flashMessenger()->addMessage('Inventry updated :'.  json_encode($response));    
        return $this->redirect()->toUrl($GLOBALS['HTTP_SITE_MERCHANT_URL'].'product');
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
