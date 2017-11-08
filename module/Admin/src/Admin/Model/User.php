<?php

namespace Admin\Model;

class User {
    public function __construct() {
        $this->cObj = new Curl();
    }
    
    public function userAuthenticate($params, $method, $controller='usercontroller') {
        $queryStr = http_build_query($params);
        $url = NODE_API.$controller.'/'.$method.'?'.$queryStr;
        return $this->cObj->callCurl($url);
    }
    
}
