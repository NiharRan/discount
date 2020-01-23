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
$route['restaurants/all'] = 'restaurant/allrestaurants';
$route['restaurants/create'] = 'restaurant/create';
$route['restaurants/(:any)'] = 'restaurant/edit';
$route['restaurant/edit/(:any)'] = 'restaurant/fetch_by_slug';
$route['restaurants/check-permission'] = 'restaurant/has_permission_to_edit_restaurant';
$route['restaurants/change-status'] = 'restaurant/changestatus';
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