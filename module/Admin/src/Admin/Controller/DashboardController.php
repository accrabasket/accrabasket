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
        $this->view = new ViewModel();
        $this->session = new Container('User');
        $this->commonObj = new common();
    }

//    public function countrylistAction() {
//        $countryListResponse = $this->commonObj->curlhit('', 'getcountrylist');
//        $countryList = json_decode($countryListResponse, true);
//        if ($countryList['status']) {
//            $this->view->countryList = $countryList['data'];
//        }
//        return $this->view;
//    }

    public function statelistAction() {
        $stateListResponse = $this->commonObj->curlhit('', 'getstatelist');
        $stateList = json_decode($stateListResponse, true);
        if ($stateList['status']) {
            $this->view->stateList = $stateList['data'];
        }
        return $this->view;
    }

    public function indexAction() {
        return $this->view;
    }

    public function dashboardAction(){
        $request = (array)$this->getRequest()->getPost();
        $params = array();
        $params["start_date"] = date('Y-m-d', strtotime($request['startDate']));
        $params["end_date"] = date('Y-m-d', strtotime($request['endDate']));
        $params["report"] = $request['report'];
        $params["method"] = 'getcustomersaledetail';
        $customerSaleResponse = $this->commonObj->curlhitApi($params,'customer');        
        $customerSaleArr = json_decode($customerSaleResponse, true);
        $params["method"] = 'getmerchantproductdetail';
        $merchantProductResponse = $this->commonObj->curlhitApi($params);        
        $merchantProductArr = json_decode($merchantProductResponse, true);
        $data = array('customerData'=>$customerSaleArr, 'merchantData'=>$merchantProductArr);
        
        echo json_encode($data);
        exit;
    }
    
    public function getTotalDashboardDetailAction() {
        $response = array();
        $params = array();
        $params["method"] = 'gettotalcustomer';
        $customerResponse = $this->commonObj->curlhitApi($params, 'customer');           
        $customerResponse = json_decode($customerResponse, true);
        $response['totalActiveCustomer'] = 0;
        if($customerResponse['status']=='success'){
            $response['totalActiveCustomer'] = $customerResponse['data']['totalNumberOfCustomer'];
        }        
        $params = array();
        $params["method"] = 'gettotalproductandmerchant';
        $productAndMerchantResponse = $this->commonObj->curlhitApi($params);
        $productAndMerchantResponse = json_decode($productAndMerchantResponse, true);
        
        $response['totalNumberOfMerchant'] = 0;
        $response['totalNumberOfProduct'] = 0;
        if($productAndMerchantResponse['status']=='success'){
            $response['totalNumberOfMerchant'] = $productAndMerchantResponse['data']['totalNumberOfMerchant'];
            $response['totalNumberOfProduct'] = $productAndMerchantResponse['data']['totalNumberOfProduct'];
        }
        
        echo json_encode($response);
        exit;        
    }
    
    public function newcompanylistAction() {
        return $this->view;
    }

    public function companylistAction() {
        $request = $this->getRequest()->getQuery();
        $params = array();
        $params["status"] = isset($request["status"]) ? $request["status"] : '';
        $newcompanylist = $this->commonObj->curlhit($params, 'getcompanylist');
        echo $newcompanylist;
        exit();
    }

    public function activateordeactivatecompanyAction() {
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

    public function saveemaildataAction() {
        $request = (array) $this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'saveemailtemplate');
        exit;
    }

    public function gettemplatelistAction() {
        echo $saveEmail = $this->commonObj->curlhit('', 'gettemplatelist');
        exit;
    }

    public function deleteEmailTemplateAction() {
        $request = (array) $this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'deleteEmailTemplate');
        exit;
    }

    public function editEmailTemplateAction() {
        $request = (array) $this->getRequest()->getPost();
        echo $saveEmail = $this->commonObj->curlhit($request, 'editEmailTemplate');
        exit;
    }

    public function managemerchantAction() {
        $request = array();
        $request['method'] = 'getMarchantList';
        $getMarchantList = $this->commonObj->curlhitApi($request);
        $getMarchantList = json_decode($getMarchantList, true);
        if (!empty($getMarchantList['data'])) {
            $this->view->marchantList = $getMarchantList['data'];
            $this->view->marchantListImg = $getMarchantList['images'];
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
        $request = (array) $this->getRequest()->getPost();
        $request['method'] = 'addEditCategory';
        $saveCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($saveCategory, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveCategory;
        exit;
    }

    public function managecategoryAction() {
        $request = array();
        $request['method'] = 'categoryList';
        $getMarchantList = $this->commonObj->curlhitApi($request);
        $getMarchantList = json_decode($getMarchantList, true);
        if (!empty($getMarchantList['data'])) {
            $this->view->categoryList = $getMarchantList['data'];
        }
        return $this->view;
    }

    public function getCategoryListAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'categoryList';
        $getMarchantList = $this->commonObj->curlhitApi($postParams);
        echo $getMarchantList;
        exit;
    }

    public function editMerchantAction() {
        $request = (array) $this->getRequest()->getQuery();
        
        if (!empty($request)) {
            $request['method'] = 'getMarchantList';
            $getMarchantList = $this->commonObj->curlhitApi($request);
            $getMarchantList = json_decode($getMarchantList, true);
            if (!empty($getMarchantList['data'])) {
                $this->view->marchantData = $getMarchantList['data'][0];
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
        if ($locationListArr['status'] == 'success') {
            $locationListData = $locationListArr['data'];
        }
        $this->view->locationListData = $locationListData;
        return $this->view;
    }

    public function locationListAction() {
        $inputParams = (array) $this->getRequest()->getPost();
        $locationList = $this->commonObj->getLocationList($inputParams);
        echo $locationList;
        exit;
    }

    public function addlocationAction() {
        $inputParams = (array) $this->getRequest()->getQuery();
        $this->view->params = $inputParams;
        return $this->view;
    }

    public function savelocationAction() {
        $request = (array) $this->getRequest()->getPost();
        $request['method'] = 'addEditLocation';
        $saveLocationResponse = $this->commonObj->curlhitApi($request);
        $response = json_decode($saveLocationResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveLocationResponse;
        exit;
    }

    public function deleteCategoryAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['method'] = 'deleteCategory';
        $deleteCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($deleteCategory, true);
        if ($response['status'] == 'success') {
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'dashboard/managecategory';
            header('Location:' . $path);
        }
        exit;
    }
    public function saveMerchantAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'saveMerchant';
        $saveMerchantResponse = $this->commonObj->curlhitApi($postParams);
        $response = json_decode($saveMerchantResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveMerchantResponse;
        exit;        
    }
    
    public function addtaxAction() {
        $request = (array) $this->getRequest()->getQuery();
        if(!empty($request)) {
            $request['method'] = 'taxlist';
            $getTaxList = $this->commonObj->curlhitApi($request);
            $getTaxList = json_decode($getTaxList, true);
            if (!empty($getTaxList['data'])) {
                $this->view->taxList = $getTaxList['data'][0];
            }
        }
        return $this->view;
    }

    public function savetaxAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'addedittax';
        $saveTaxResponse = $this->commonObj->curlhitApi($postParams);
        $response = json_decode($saveTaxResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveTaxResponse;
        exit;        
    }
    
    public function managetaxAction() {
        $request = array();
        $request['method'] = 'taxlist';
        $getTaxList = $this->commonObj->curlhitApi($request);
        $getTaxList = json_decode($getTaxList, true);
        if (!empty($getTaxList['data'])) {
            $this->view->taxList = $getTaxList['data'];
        }
        return $this->view;
    }
    
    public function deletetaxAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['method'] = 'deletetax';
        $deleteCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($deleteCategory, true);
        if ($response['status'] == 'success') {
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'dashboard/managetax';
            header('Location:' . $path);
        }
        exit;
    }
    
    public function addcityAction() {
        $request = (array) $this->getRequest()->getQuery();
        if(!empty($request)) {
            $request['method'] = 'cityList';
            $getCityList = $this->commonObj->curlhitApi($request);
            $getCityList = json_decode($getCityList, true);
            if (!empty($getCityList['data'])) {
                $this->view->cityList = $getCityList['data'][$request['id']];
            }
        }
//        print_r($getCityList['data']);die;
        return $this->view;
    }

    public function savecityAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'addeditcity';
        $saveCityResponse = $this->commonObj->curlhitApi($postParams);
        $response = json_decode($saveCityResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveCityResponse;
        exit;        
    }
    
    public function managecityAction() {
        $request = array();
        $request['method'] = 'cityList';
        $getCityList = $this->commonObj->curlhitApi($request);
        $getCityList = json_decode($getCityList, true);
        if (!empty($getCityList['data'])) {
            $this->view->cityList = $getCityList['data'];
        }
        return $this->view;
    }
    
    public function deletecityAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['method'] = 'deletecity';
        $deleteCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($deleteCategory, true);
        if ($response['status'] == 'success') {
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'dashboard/managecity';
            header('Location:' . $path);
        }
        exit;
    }
    
    public function addtimeslotAction() {
        $request = (array) $this->getRequest()->getQuery();
        if(!empty($request)) {
            $request['method'] = 'deliveryTimeSlotList';
            $getTimeslotList = $this->commonObj->curlhitApi($request);
            $getTimeslotList = json_decode($getTimeslotList, true);
            if (!empty($getTimeslotList['data'])) {
                $this->view->timeslotList = $getTimeslotList['data'][$request['id']];
            }
        }
//        print_r($getCityList['data']);die;
        return $this->view;
    }

    public function savetimeslotAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'addedittimeslot';
        $saveCityResponse = $this->commonObj->curlhitApi($postParams);
        $response = json_decode($saveCityResponse, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveCityResponse;
        exit;        
    }
    
    public function managetimeslotAction() {
        $request = array();
        $request['method'] = 'deliveryTimeSlotList';
        $getTimeslotList = $this->commonObj->curlhitApi($request);
        $getTimeslotList = json_decode($getTimeslotList, true);
        if (!empty($getTimeslotList['data'])) {
            $this->view->timeslotList = $getTimeslotList['data'];
        }
        return $this->view;
    }
    
    public function deletetimeslotAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['method'] = 'deletetimeslot';
        $deleteTimeslot = $this->commonObj->curlhitApi($request);
        $response = json_decode($deleteTimeslot, true);
        if ($response['status'] == 'success') {
            $path = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'dashboard/managetimeslot';
            header('Location:' . $path);
        }
        exit;
    }
    
    public function countryListAction() {
        $inputParams = (array) $this->getRequest()->getPost();
        $inputParams['method'] = 'countryList';
        $countryList = $this->commonObj->curlhitApi($inputParams);
        echo $countryList;
        exit;
    }
    
    public function cityListAction() {
        $inputParams = (array) $this->getRequest()->getPost();
        $inputParams['method'] = 'cityList';
        $cityList = $this->commonObj->curlhitApi($inputParams);
        echo $cityList;
        exit;
    }
    
    public function managesettingAction() {
        $request = array();
        $request['method'] = 'settinglist';
        $getSettingList = $this->commonObj->curlhitApi($request);
        $getSettingList = json_decode($getSettingList, true);
        if (!empty($getSettingList['data'])) {
            $this->view->settingList = $getSettingList['data'];
        }
        return $this->view;
    }

    public function savesettingAction() {
        $inputParams = (array) $this->getRequest()->getPost();
        $inputParams['method'] = 'saveSetting';
        $response = $this->commonObj->curlhitApi($inputParams);
        echo $response;
        exit;
    }
    
    public function addbannerAction() {
        $data = array();
        $request = (array) $this->getRequest()->getQuery();
        $this->view->bannerData = array();
        if(!empty($request['id'])) {
            $data['id'] = $request['id'];
            $data['method'] = 'banner';
            $banner = $this->commonObj->curlhitApi($data);
            $getBannerData = json_decode($banner, true);
            if (!empty($getBannerData['data'][0])) {
                $this->view->bannerData = $getBannerData['data'][0];
            }            
        }
        return $this->view;
    }
    
    public function savebannerAction() {
        $request = (array) $this->getRequest()->getPost();
        $request['method'] = 'addEditBanner';
        $request['status'] = isset($request['status'])?$request['status']:1;
//        print_r($request);die;
        $saveCategory = $this->commonObj->curlhitApi($request);
        $response = json_decode($saveCategory, true);
        if ($response['status'] == 'success') {
            $this->flashMessenger()->addMessage($response['msg']);
        }
        echo $saveCategory;
        exit;
    }
    /*public function updatebannerAction() {
        $request = (array) $this->getRequest()->getQuery();
        $request['method'] = 'addEditBanner';
        $savebanner = $this->commonObj->curlhitApi($request);
        $response = json_decode($savebanner, true);
        $path = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'dashboard/managebanner';
        header('Location:' . $path);

        exit;
    }*/
    public function managebannerAction() {
        $postParams['method'] = 'banner';
        $postParams['status'] = 1;
        $banner = $this->commonObj->curlhitApi($postParams);
        $getBannerList = json_decode($banner, true);
        if (!empty($getBannerList['data'])) {
            $this->view->bannerList = $getBannerList['data'];
            $this->view->bannerListImg = $getBannerList['imageRootPath'];
        }
        return $this->view;
    }
    
    public function manageledgerAction() {
        return $this->view;
    }
    
    public function getledgerAction() {
        $request = (array) $this->getRequest()->getPost();
        $data = array();
        $data['method'] = 'ledgersummery';
        $data['merchant_id'] = $request['merchant_id'];
        $data['start_date'] = $request['startDate'].' 00:00:00';
        $data['end_date'] = $request['endDate'].' 23:59:59';
        echo$savebanner = $this->commonObj->curlhitApi($data,'customer');
        exit;
    }
    public function paytomerchantAction() {
        $request = (array) $this->getRequest()->getPost();
        $request['method'] = 'paytomerchant';
        echo$savebanner = $this->commonObj->curlhitApi($request,'customer');
        exit;
    }
    
    public function downloadledgerAction() {
        $request = (array) $this->getRequest()->getQuery();
        $data = array();
        $data['method'] = 'ledgersummery';
        $data['merchant_id'] = $request['merchant_id'];
        $data['start_date'] = $request['start_date'].' 00:00:00';
        $data['end_date'] = $request['end_date'].' 23:59:59';
        
        $legerdata = $this->commonObj->curlhitApi($data,'customer');
        
        $this->downloadCsv($legerdata);
    }
    
    public function downloadCsv($data) {
    $filename = "ledger" . date("Y-m-d") . ".csv";
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
            $csvData[$counter][]= 'Order id';
            $csvData[$counter][]= 'Total Amount';
            $csvData[$counter][]= 'Merchant Amount';
            $csvData[$counter][]= 'Commission Amount';
            $csvData[$counter][]= 'Type';
            $csvData[$counter][]= 'Transection Date';
            $counter ++;
            foreach ($data as $key=>$row) {
                if($key !='total_summery'){
                        $csvData[$counter][] = $row['order_id'] == 0?'Cash':$row['order_id'];
                        $csvData[$counter][] = $row['total_amount'];
                        $csvData[$counter][] = $row['merchant_amount'];
                        $csvData[$counter][] = $row['commission_amount'];
                        $csvData[$counter][] = $row['type'];
                        $csvData[$counter][] = $row['created_date'];
                }  else {
                    $csvData[$counter][] = 'Total Summery';
                    $csvData[$counter][] = $row['total_revenue'];
                    $csvData[$counter][] = $row['total_merchant_amount'];
                    $csvData[$counter][] = $row['total_commission'];
                    $csvData[$counter][] = '---';
                    $csvData[$counter][] = $row['created_date'];
                }
                $counter++;
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
    public function getNotificationAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'getnotification';
        $postParams['user_type'] = 'admin';
        $postParams['pagination'] = 1;
        $notificationList = $this->commonObj->curlhitApi($postParams, 'customer');
        echo $notificationList;
        
        exit;        
    }
    
    public function notificationAction() {
        return $this->view;
    }
    public function updateNotificationAction() {
        $postParams = (array) $this->getRequest()->getPost();
        $postParams['method'] = 'updatenotification';
        $postParams['user_type'] = 'admin';
        $response = $this->commonObj->curlhitApi($postParams, 'customer');
        echo $response;
        
        exit;        
    }    

}
