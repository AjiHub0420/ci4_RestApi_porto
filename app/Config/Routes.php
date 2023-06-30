<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->post('nugapi/users/regist','UserController::create');
$routes->post('nugapi/users/login','Login::index');
$routes->get('nugapi/users','UserController::index',['filter'=>'admin']);
$routes->get('nugapi/users/(:num)','UserController::show/$1',['filter'=>'nonadmin']);
$routes->get('nugapi/add-admin/(:num)','UserController::AddAdmin/$1',['filter'=>'admin']);
$routes->delete('nugapi/users/(:num)','UserController::delete/$1',['filter'=>'admin']);
$routes->post('nugapi/users/update/(:num)','UserController::update/$1',['filter'=>'nonadmin']);
$routes->post('nugapi/users/upload/(:num)','UserController::upload/$1',['filter'=>'nonadmin']);

$routes->get('nugapi/categories','CategoryController::index');
$routes->post('nugapi/categories','CategoryController::create',['filter'=>'nonadmin']);
$routes->put('nugapi/categories/(:num)','CategoryController::update/$1',['filter'=>'admin']);
$routes->delete('nugapi/categories/(:num)','CategoryController::delete/$1',['filter'=>'admin']);


$routes->get('nugapi/blog','BlogController::index');
$routes->post('nugapi/blog/update/(:num)','BlogController::update/$1',['filter'=>'nonadmin']);
$routes->post('nugapi/blog/upload/(:num)','BlogController::upload/$1',['filter'=>'nonadmin']);
$routes->get('nugapi/blog/(:any)','BlogController::show/$1');
$routes->get('nugapi/myblog','BlogController::showmyblog',['filter'=>'nonadmin']);
$routes->delete('nugapi/myblog/(:num)','BlogController::delete/$1',['filter'=>'nonadmin']);
$routes->post('nugapi/blog','BlogController::create',['filter'=>'nonadmin']);




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
