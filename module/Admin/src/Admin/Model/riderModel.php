<?php
namespace Admin\Model;

class riderModel{
    public function __construct() {
        $this->commonModel = new common();
    }   
    public function getRiderList($inputParams = array()) {
        $params = array();
        if(!empty($inputParams['name'])) {
            $params['name'] = $inputParams['name'];
        }
        if(!empty($inputParams['email'])) {
            $params['email'] = $inputParams['email'];
        }        
        if(!empty($inputParams['location_id'])) {
            $params['location_id'] = $inputParams['location_id'];
        }                
        if(!empty($inputParams['id'])) {
            $params['id'] = $inputParams['id'];
        }        
        if(!empty($inputParams['status'])) {
            $params['status'] = $inputParams['status'];
        }        
        if(!empty($inputParams['pagination'])) {
            $params['pagination'] = $inputParams['pagination'];
            $params['page'] = isset($inputParams['page'])?$inputParams['page']:1;
        }      
        $params['method'] = 'getRiderList';
        //print_r($params);die;
        return $this->commonModel->curlhitApi($params);
    }    
}