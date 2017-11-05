<?php
namespace Admin\Model;

class common{
    public function __construct() {
        $this->cObj = new curl();
    }    
    public function curlhit($params=null, $method, $controller='companycontroller') {
        $queryStr = '';
        if(!empty($params)){
            $queryStr = http_build_query($params);
        }
        $url = NODE_API.$controller.'/'.$method.'?'.$queryStr;
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