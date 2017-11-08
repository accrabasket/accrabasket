<?php
namespace Admin\Model;

class basketapi{
    public function __construct() {
        $this->cObj = new curl();
    }    
    public function curlhit($params=null, $method, $controller='companycontroller', $module='application') {
        $queryStr = '';
        if(!empty($params)){
            $queryStr = http_build_query($params);
        }
        $url = BASKET_API.$module.'/'.$controller.'/'.$method.'?'.$queryStr;
        //echo $url;die;
       return $this->cObj->callCurl($url);
    }
    
    public function curlhitApi($params=null, $method, $controller='application') {
        $queryStr = '';
        if(!empty($params)){
            $queryStr = json_encode($params);
//            $queryStr = http_build_query($params);
//            $queryStr = json_encode($queryStr);
        }
        $url = BASKET_API.$controller.'?parameters='.$queryStr;
        return $this->cObj->callCurl($url);
    }
}