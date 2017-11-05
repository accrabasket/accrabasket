<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Admin\Model\common;
class IndexController extends AbstractActionController {
    protected $authservice;
    public $session;
    public function __construct($user) {
        $this->session = new Container('User');
        $this->userObj = $user;
    }
    
    public function indexAction() {
        
        $request = $this->getRequest();
        $view = new ViewModel();        
        if ($request->isPost()) {
            $params = array();
            $inputParams = $request->getPost();
            $params['password'] = md5($inputParams['password']);
            $params['username'] = $inputParams['username'];
            $method = 'loginuser';
            $response = json_decode($this->userObj->userAuthenticate($params, $method));
            if ($response->status == 'success') {
                $this->session->offsetSet('user', $response);
                $this->session['userDetail'] = $response;
                return $this->redirect()->toUrl($GLOBALS['HTTP_SITE_ADMIN_URL'].'dashboard');
            } else {
                $this->flashMessenger()->addMessage(array('error' => 'invalid credentials.'));
            }              
        }
        return $this->redirect()->toUrl($GLOBALS['HTTP_SITE_ADMIN_URL'].'index/login');
    }
    public function loginAction() {
        $viewModel = new ViewModel();
        return $viewModel;
    }
/*    public function logoutAction()
    {
        $this->session->offsetUnset('user');
        unset($this->session['userDetail']);
        $this->redirect()->toUrl($GLOBALS['HTTP_SITE_ADMIN_URL'].'index/login');
    }    */
}
