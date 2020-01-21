<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'deshboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Authentication routes
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';

// Admin routes
$route['/'] = 'deshboard/index';

$route['restaurants'] = 'restaurant/index';
$route['restaurants/create'] = 'restaurant/create';
$route['settings/restaurant'] = 'restaurant/profile';


$route['settings/tags'] = 'tag/index';
$route['settings/tags/store'] = 'tag/store';
$route['settings/tags/update'] = 'tag/update';
$route['settings/tags/remove/(:num)'] = 'tag/delete/$1';

// user routes
$route['users'] = 'user/index';
$route['users/types'] = 'user/usertypes';
$route['users/create'] = 'user/create';
$route['users/store'] = 'user/store';
$route['users/update'] = 'user/update';
$route['users/remove/(:num)'] = 'user/delete/$1';