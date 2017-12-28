<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             //'Admin\Controller\Index' => 'Admin\Controller\IndexController',
             'Admin\Controller\Dashboard' => 'Admin\Controller\DashboardController',
             'Admin\Controller\Common' => 'Admin\Controller\CommonController',
             'Admin\Controller\Product' => 'Admin\Controller\ProductController',
             'Admin\Controller\Rider' => 'Admin\Controller\RiderController',
         ),
     ),
 'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),                        
         ),
     
     ),
	 
     'view_manager' => array(
         'template_path_stack' => array(
             __DIR__ . '/../../../public/view',
         ),
        'template_map' => array(
            'layout/Admin/Dashboard'           => __DIR__ . '/../../../public/view/layout/admin.phtml',
            'layout/Admin/Product'           => __DIR__ . '/../../../public/view/layout/admin.phtml',
            'layout/Admin/Rider'           => __DIR__ . '/../../../public/view/layout/admin.phtml',
        ),         
     ),
 );