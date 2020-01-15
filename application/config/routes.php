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

$route['settings/resturants'] = 'resturant/index';
$route['settings/resturants/create'] = 'resturant/create';


$route['settings/tags'] = 'tag/index';
$route['settings/tags/store'] = 'tag/store';
$route['settings/tags/update'] = 'tag/update';
$route['settings/tags/remove/(:num)'] = 'tag/delete/$1';
