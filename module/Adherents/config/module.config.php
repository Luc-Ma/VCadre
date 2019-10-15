<?php
namespace Adherents;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'auth' => [
                'type'   => Segment::class,
                'options' => [
                    'route' => '/auth[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                            'controller' => Controller\AuthController::class,
                            'action'     => 'login',
                    ],
                ],
            ],
            'adherents' => [
                'type'   => Segment::class,
                'options' => [
                    'route' => '/adherents[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                            'controller' => Controller\AdherentsController::class,
                            'action'     => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type'   => Segment::class,
                'options' => [
                    'route' => '/admin[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                            'controller' => Controller\AdminController::class,
                            'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AdherentsController::class => Controller\Factory\AdherentsControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\AdminManager::class => Service\Factory\AdminManagerFactory::class,
            Service\LogManager::class => Service\Factory\LogManagerFactory::class,
            Service\AdherentsManager::class => Service\Factory\AdherentsManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
        'strategies' => array('ViewJsonStrategy',),
    ],
    'view_helpers' => [
        'factories' => [
            Helper\AdminHelper::class => Helper\Factory\AdminHelperFactory::class,
            Helper\MinicvFormHelper::class => Helper\Factory\MinicvFormHelperFactory::class,
        ],
        'aliases' => [
            'AdminRender' => Helper\AdminHelper::class,
            'FormHelper' => Helper\MinicvFormHelper::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed
            // action for not logged in users. In permissive mode, if an action is not listed
            // under the 'access_filter' key, access to it is permitted to anyone (even for
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [

        ]
    ],
    'Adherents' => [
        'options' => [
            'competence' => 3,
            'competenceBis' => 3,
            'secteur' => 3,
            'savoiretre' => 3,
        ]
    ],
];
