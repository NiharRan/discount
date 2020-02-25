<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'deshboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Authentication routes
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';

// deshbaord routes
$route['/'] = 'deshboard/index';

// admin routes
$route['users/(:any)'] = 'admin/profile';
$route['users/profile/update'] = 'admin/update_profile';
$route['users/profile/(:any)'] = 'admin/profile_info';

// restaurant routes
$route['restaurants'] = 'restaurant/index';
$route['restaurants/all'] = 'restaurant/allrestaurants';
$route['restaurants/create'] = 'restaurant/create';
$route['restaurants/(:any)'] = 'restaurant/edit';
$route['restaurant/edit/(:any)'] = 'restaurant/fetch_by_slug';
$route['restaurants/check-permission'] = 'restaurant/has_permission_to_action_restaurant';
$route['restaurant/change-status'] = 'restaurant/changestatus';
$route['settings/restaurant'] = 'restaurant/profile';


// offer routes
$route['offers'] = 'offer/index';
$route['offers/all'] = 'offer/alloffers';
$route['offers/create'] = 'offer/create';
$route['offers/check-permission'] = 'offer/has_permission_to_action_offer';
$route['offer/change-status'] = 'offer/changestatus';

// Tag routes
$route['settings/tags'] = 'tag/index';
$route['settings/tags/store'] = 'tag/store';
$route['settings/tags/update'] = 'tag/update';
$route['settings/tags/remove/(:num)'] = 'tag/delete/$1';
$route['settings/tags/change-status'] = 'tag/changestatus';

// Template routes
$route['settings/templates'] = 'template/index';
$route['templates/active'] = 'template/allactivetemplates';
$route['settings/templates/store'] = 'template/store';
$route['settings/templates/update'] = 'template/update';
$route['settings/templates/remove/(:num)'] = 'template/delete/$1';
$route['settings/templates/check-permission'] = 'template/has_permission_to_action_template';
$route['settings/templates/change-status'] = 'template/changestatus';

// user routes
$route['users'] = 'user/index';
$route['user/all'] = 'user/allusers';
$route['user/types'] = 'user/usertypes';
$route['user/create'] = 'user/create';
$route['user/store'] = 'user/store';
$route['user/update'] = 'user/update';
$route['user/remove/(:num)'] = 'user/delete/$1';
$route['user/check-permission'] = 'user/has_permission_to_action_user';
$route['user/change-status'] = 'user/changestatus';





// web routes
$route['web/offer/(:any)'] = 'web/singeOffer';
$route['web/restaurant/(:any)'] = 'web/offersOfRestaurant';
$route['web/category/(:any)'] = 'web/offersOfTag';