<?php

declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use App\Middleware\TokenAuthMiddleware;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->setRouteClass(DashedRoute::class);

// ----- Web Routes -----
$routes->scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
    $routes->fallbacks();
});

// ----- API Routes -----
$routes->scope('/api', ['prefix' => 'Api'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    // Public (no auth)
    $routes->connect('/login',    ['controller' => 'Auth', 'action' => 'login']);
    $routes->connect('/register', ['controller' => 'Auth', 'action' => 'register']);

    // Apply TokenAuth middleware for protected routes
    $routes->registerMiddleware('tokenAuth', new TokenAuthMiddleware());
    $routes->applyMiddleware('tokenAuth');

    // Auth routes (protected)
    $routes->connect('/profile', ['controller' => 'Auth', 'action' => 'profile']);
    $routes->connect('/logout',  ['controller' => 'Auth', 'action' => 'logout']);

    // Explicit RESTful resources
    $routes->resources('clients', [
        'controller' => 'Client',
        'only' => ['index', 'view', 'add', 'edit', 'delete'],
        'id' => ['pattern' => '\d+', 'pass' => true],
    ]);

    $routes->resources('breeds', [
        'controller' => 'Breeds',
        'only' => ['index', 'view', 'add', 'edit', 'delete'],
        'id' => ['pattern' => '\d+', 'pass' => true],
    ]);

    $routes->resources('installations', [
        'controller' => 'Installation',
        'only' => ['index', 'view', 'add', 'edit', 'delete'],
        'id' => ['pattern' => '\d+', 'pass' => true],
    ]);

    $routes->resources('pets', [
        'controller' => 'Pet',
        'only' => ['index', 'view', 'add', 'edit', 'delete'],
        'id' => ['pattern' => '\d+', 'pass' => true],
    ]);

    $routes->resources('pet-owner', [
    'controller' => 'PetOwner', // ✅ Proper casing and string type
    'only' => ['index', 'view', 'add', 'edit', 'delete'],
    // 'id' => ['pattern' => '\d+', 'pass' => true],
    ]);


    $routes->resources('pet-records', [
        'controller' => 'PetRecord',
        'only' => ['index', 'view', 'add', 'edit', 'delete'],
        'id' => ['pattern' => '\d+', 'pass' => true],
    ]);

    // Fallbacks for other controllers (optional)
    $routes->fallbacks(DashedRoute::class);
});
