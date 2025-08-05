<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use App\Middleware\TokenAuthMiddleware;

/**
 * @var \Cake\Routing\RouteBuilder $routes
 */

$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $routes) {
    // 👇 This sets the default route (homepage)
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    // Fallback routes for all controllers
    $routes->fallbacks();
});

$routes->scope('/api', ['prefix' => 'Api'], function (RouteBuilder $builder) {
    $builder->setExtensions(['json']);

    //  Public routes (no token middleware)
    $builder->connect('/login', [
        'controller' => 'Auth',
        'action'     => 'login',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/register', [
        'controller' => 'Auth',
        'action'     => 'register',
        '_namespace' => 'App\Controller\Api',
    ]);
});

$routes->scope('/api', ['prefix' => 'Api'], function (RouteBuilder $builder) {
    $builder->setExtensions(['json']);

    $builder->registerMiddleware('tokenAuth', new TokenAuthMiddleware());
    $builder->applyMiddleware('tokenAuth');

    $builder->connect('/profile', [
        'controller' => 'Auth',
        'action'     => 'profile',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/logout', [
        'controller' => 'Auth',
        'action'     => 'logout',
        '_namespace' => 'App\Controller\Api',
    ]);

    // Client routes
    $builder->connect('/clients', [
        'controller' => 'Client',
        'action' => 'index',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/clients/view/:id', [
        'controller' => 'Client',
        'action' => 'view',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    $builder->connect('/clients/add', [
        'controller' => 'Client',
        'action' => 'add',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/clients/edit/:id', [
        'controller' => 'Client',
        'action' => 'edit',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id'])->setPatterns(['id' => '\d+']);

    $builder->connect('/clients/delete/:id', [
        'controller' => 'Client',
        'action' => 'delete',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    // Breed routes
    $builder->connect('/breeds', [
        'controller' => 'Breeds',
        'action' => 'index',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/breeds/view/:id', [
        'controller' => 'Breeds',
        'action' => 'view',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    $builder->connect('/breeds/add', [
        'controller' => 'Breeds',
        'action' => 'add',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/breeds/edit/:id', [
        'controller' => 'Breeds',
        'action' => 'edit',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id'])->setPatterns(['id' => '\d+']);

    $builder->connect('/breeds/delete/:id', [
        'controller' => 'Breeds',
        'action' => 'delete',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    //Installation
    $builder->connect('/installations', [
        'controller' => 'Installation',
        'action' => 'index',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/installations/view/:id', [
        'controller' => 'Installation',
        'action' => 'view',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    $builder->connect('/installations/add', [
        'controller' => 'Installation',
        'action' => 'add',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/installations/edit/:id', [
        'controller' => 'Installation',
        'action' => 'edit',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id'])->setPatterns(['id' => '\d+']);

    $builder->connect('/installations/delete/:id', [
        'controller' => 'Installation',
        'action' => 'delete',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    //Pet routes
    $builder->connect('/pets', [
        'controller' => 'Pet',
        'action' => 'index',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/pets/view/:id', [
        'controller' => 'Pet',
        'action' => 'view',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    $builder->connect('/pets/add', [
        'controller' => 'Pet',
        'action' => 'add',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/pets/edit/:id', [
        'controller' => 'Pet',
        'action' => 'edit',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id'])->setPatterns(['id' => '\d+']);

    $builder->connect('/pets/delete/:id', [
        'controller' => 'Pet',
        'action' => 'delete',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    // PetOwner routes
    $builder->connect('/pet-owners', [
        'controller' => 'PetOwner',
        'action' => 'index',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/pet-owners/view/:id', [
        'controller' => 'PetOwner',
        'action' => 'view',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    $builder->connect('/pet-owners/add', [
        'controller' => 'PetOwner',
        'action' => 'add',
        '_namespace' => 'App\Controller\Api',
    ]);
    $builder->connect('/pet-owners/edit/:id', [
        'controller' => 'PetOwner',
        'action' => 'edit',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id'])->setPatterns(['id' => '\d+']);

    $builder->connect('/pet-owners/delete/:id', [
        'controller' => 'PetOwner',
        'action' => 'delete',
        '_namespace' => 'App\Controller\Api',
    ])->setPass(['id']);

    // Fallback routes for all controllers
    
    $builder->fallbacks(DashedRoute::class);
});