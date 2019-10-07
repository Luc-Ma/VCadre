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
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
        'strategies' => array('ViewJsonStrategy',),
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
        'controllers' => [ /*
            Controller\AuthController::class => [
                // this class is always allow
            ],
            Controller\MthController::class => [
                // * = allow  @ = auth user only
                ['actions' => ['index','view','dl'], 'allow' => '*'],
            ],
            Controller\StuffController::class => [
                // * = allow  @ = auth user only
                ['actions' => ['about', 'legal'], 'allow' => '*'],
            ],
            Controller\SearchController::class => [
                // * = allow  @ = auth user only
                ['actions' => ['search'], 'allow' => '*'],
            ],
            Controller\TricksController::class => [
                // * = allow  @ = auth user only
                ['actions' => ['index', 'add'], 'allow' => '@'],
            ],
            Controller\LevelController::class => [
                // * = allow  @ = auth user only
                ['actions' => ['new','editversion','editlvl','ajax','dashboard','newSMM','newSMW','newversion'], 'allow' => '@'],
            ], */
        ]
    ],
];
