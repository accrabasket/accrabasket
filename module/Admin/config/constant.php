<?php
$GLOBALS['HTTP_SITE_ADMIN_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/accrabasket/admin/';
$GLOBALS['SITE_APP_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/accrabasket/application/index';
$GLOBALS['SITE_COMPANY_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/accrabasket/company/';
$GLOBALS['PAGE_BEFORE_LOGIN'] = array('Admin\Controller\Index\login','Admin\Controller\Index\index');
$GLOBALS['SITE_PATH'] = $_SERVER['DOCUMENT_ROOT'];
define('NODE_API', 'http://localhost:3000/');
define('BASKET_API', 'http://localhost/basketapi/');