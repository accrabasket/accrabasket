<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Session\Container;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
                ), 100);
    }

    function boforeDispatch(MvcEvent $event) {
        include 'config/constant.php';
        $response = $event->getResponse();
        $config = $event->getApplication()->getServiceManager()->get('Config');
        $controller = $event->getRouteMatch()->getParam('controller');
        $module_array = explode("\\", $controller);
        $viewModel = $event->getViewModel();
        //echo $config['view_manager']['template_map']['layout/'.$module_array[2]];die;
        if(isset($config['view_manager']['template_map']['layout/'.$module_array[0].'/'.$module_array[2]]) && file_exists($config['view_manager']['template_map']['layout/'.$module_array[0].'/'.$module_array[2]])){
            $viewModel->setTemplate('layout/'.$module_array[0].'/'.$module_array[2]);        
        }
        if ($module_array[0] == 'Admin' || $module_array[0]=='Merchant') {
            $action = $event->getRouteMatch()->getParam('action');
            $requestedResourse = $controller . "\\" . $action;
            $session = new Container('User');            
            if ($session->offsetExists('user')) {
                $GLOBALS['user'] = $session->user;
                if (in_array($requestedResourse, $GLOBALS['PAGE_BEFORE_LOGIN'])) {
                    $url = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'dashboard';
                    $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                    $response->setStatusCode(302);
                    $response->sendHeaders();
                }else{
                    if($module_array[0]=='Merchant'){
                        if(!in_array(2, $session->userDetail['userRoleList'][$session->userDetail['data'][0]['id']])){
                           echo "You are not allowed to access module";die;
                        } 
                    }
                    if($module_array[0]=='Admin'){
                       if(!in_array(1, $session->userDetail['userRoleList'][$session->userDetail['data'][0]['id']]) && $controller !='Admin\Controller\Common'){
                           echo "You are not allowed to access module";die;
                       } 
                    }     
                }
            } else {
                if ($requestedResourse != 'Admin\Controller\Index\index' && !in_array($requestedResourse, $GLOBALS['PAGE_BEFORE_LOGIN'])) {
                    $url = $GLOBALS['HTTP_SITE_ADMIN_URL'] . 'index/login';
                    $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                    $response->setStatusCode(302);
                }
                $response->sendHeaders();
            }
        }
    }
/*    public function init(ModuleManager $manager)
    {
        $events = $manager->getEventManager();     
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            //$controller->layout('layout/admin');
            $controllerPathArr = explode("\\", get_class($controller));
            $controller->layout('layout/'.$controllerPathArr[2]);
        }, 100);
    }
 * 
 */
    public function getAutoloaderConfig() {
        //echo __DIR__ . '/autoload_classmap.php';die;
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig() {
        return array(
            /*'factories' => array(
                'User\Model\UserTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new \User\Model\UserTable($dbAdapter);
                    return $table;
                },
            ),*/
            'invokables' => array(
                'test_helper' => '\Admin\Helper\testHelper',
            ),                        
        );

    }
    public function getControllerConfig(){
        return array(
          'factories' =>array(
            'Admin\Controller\Index'=> function($sm){
                $table = new \Admin\Model\User();
                $indexObj = new \Admin\Controller\IndexController($table);
                return $indexObj;
            }
          ),       
        );
    }


    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'test_helper' => new \Admin\Helper\testHelper,
            ),
        );

    }	
}
