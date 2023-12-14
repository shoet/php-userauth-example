<?php

use App\Controllers\Auth;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', [Auth::class, 'login']);
$routes->post('/signin', [Auth::class, 'signin']);
$routes->get('/register', [Auth::class, 'register']);
$routes->post('/signup', [Auth::class, 'signup']);
