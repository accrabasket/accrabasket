<?php
namespace Merchant\Model;
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
}