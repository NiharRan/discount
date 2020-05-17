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

// Role routes
$route['settings/roles'] = 'role/index';
$route['roles/active'] = 'role/allactiveroles';
$route['settings/roles/store'] = 'role/store';
$route['settings/roles/update'] = 'role/update';
$route['settings/roles/remove/(:num)'] = 'role/delete/$1';
$route['settings/roles/check-permission'] = 'role/has_permission_to_action_role';
$route['settings/roles/change-status'] = 'role/changestatus';

// restaurant routes
$route['restaurants'] = 'restaurant/index';
$route['restaurants/all'] = 'restaurant/allrestaurants';
$route['restaurants/all/active'] = 'restaurant/allrestaurants';
$route['restaurants/search'] = 'restaurant/searchRestaurantsForCategory';
$route['restaurants/create'] = 'restaurant/create';
$route['restaurants/(:any)'] = 'restaurant/edit';
$route['restaurant/edit/(:any)'] = 'restaurant/fetch_by_slug';
$route['restaurants/check-permission'] = 'restaurant/has_permission_to_action_restaurant';
$route['restaurant/change-status'] = 'restaurant/changeStatus';
$route['restaurant/change-feature-status'] = 'restaurant/changeFeatureStatus';
$route['settings/restaurant'] = 'restaurant/profile';
$route['settings/feature-restaurants'] = 'restaurant/featureRestaurants';


// offer routes
$route['offers'] = 'offer/index';
$route['offers/all'] = 'offer/alloffers';
$route['offers/create'] = 'offer/create';
$route['offers/check-permission'] = 'offer/has_permission_to_action_offer';
$route['offer/change-status'] = 'offer/changestatus';


// order routes
$route['orders'] = 'order/index';
$route['orders/all'] = 'order/allOrders';
$route['orders/change-status/(:num)'] = 'order/changeStatus/$1';
$route['orders/remove/(:num)'] = 'order/delete/$1';
$route['orders/print/(:num)'] = 'order/printSingleOrder/$1';

// Permission routes
$route['settings/permissions'] = 'access/index';
$route['settings/permissions/store'] = 'access/store';
$route['settings/permissions/update'] = 'access/update';
$route['settings/permissions/remove/(:num)'] = 'access/delete/$1';
$route['settings/permissions/change-status'] = 'access/changeStatus';
$route['auth/permissions'] = 'access/userActivePermissions';

// Tag routes
$route['settings/tags'] = 'tag/index';
$route['settings/tags/store'] = 'tag/store';
$route['settings/tags/update'] = 'tag/update';
$route['settings/tags/remove/(:num)'] = 'tag/delete/$1';
$route['settings/tags/change-status'] = 'tag/changeStatus';

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



//Menu Tag routes
$route['menu/menu-tags'] = 'menu/tag/index';
$route['menu/menu-tags/all'] = 'menu/tag/allMenuTags';
$route['menu/menu-tags/active'] = 'menu/tag/allActiveMenuTags';
$route['menu/menu-tags/store'] = 'menu/tag/store';
$route['menu/menu-tags/update'] = 'menu/tag/update';
$route['menu/menu-tags/remove/(:num)'] = 'menu/tag/delete/$1';
$route['menu/menu-tags/change-status'] = 'menu/tag/changeStatus';

//Menu Categories routes
$route['menu/categories'] = 'menu/category/index';
$route['menu/categories/all'] = 'menu/category/allCategories';
$route['menu/categories/active'] = 'menu/category/allActiveCategories';
$route['menu/categories/store'] = 'menu/category/store';
$route['menu/categories/update'] = 'menu/category/update';
$route['menu/categories/remove/(:num)'] = 'menu/category/delete/$1';
$route['menu/categories/change-status'] = 'menu/category/changeStatus';


//Menu Product Size routes
$route['menu/food-sizes'] = 'menu/size/index';
$route['menu/food-sizes/all'] = 'menu/size/allSizes';
$route['menu/food-sizes/active'] = 'menu/size/allActiveSizes';
$route['menu/food-sizes/store'] = 'menu/size/store';
$route['menu/food-sizes/update'] = 'menu/size/update';
$route['menu/food-sizes/remove/(:num)'] = 'menu/size/delete/$1';
$route['menu/food-sizes/change-status'] = 'menu/size/changeStatus';

//Menu Product routes
$route['menu/foods'] = 'menu/food/index';
$route['menu/foods/all'] = 'menu/food/allFoods';
$route['menu/foods/active'] = 'menu/food/allActiveFoods';
$route['menu/foods/store'] = 'menu/food/store';
$route['menu/foods/update'] = 'menu/food/update';
$route['menu/foods/remove/(:num)'] = 'menu/food/delete/$1';
$route['menu/foods/(:num)'] = 'menu/food/single';
$route['menu/foods/change-status'] = 'menu/food/changeStatus';


//Menu Product routes
$route['menu/food-prices'] = 'menu/price/index';
$route['menu/food-prices/all'] = 'menu/price/allFoodPrices';
$route['menu/food-prices/active'] = 'menu/price/allActiveFoodPrices';
$route['menu/food-prices/store'] = 'menu/price/store';
$route['menu/food-prices/update'] = 'menu/price/update';
$route['menu/food-prices/remove/(:num)'] = 'menu/price/delete/$1';
$route['menu/food-prices/change-status'] = 'menu/price/changeStatus';

//Menu Product routes
$route['menu/food-aditionals'] = 'menu/aditional/index';
$route['menu/food-aditionals/all'] = 'menu/aditional/allFoodAditionals';
$route['menu/food-aditionals/active'] = 'menu/aditional/allActiveFoodAditionals';
$route['menu/food-aditionals/store'] = 'menu/aditional/store';
$route['menu/food-aditionals/update'] = 'menu/aditional/update';
$route['menu/food-aditionals/remove/(:num)'] = 'menu/aditional/delete/$1';
$route['menu/food-aditionals/change-status'] = 'menu/aditional/changeStatus';


$route['menu/(:any)'] = 'menu/web/index';
$route['menu/(:any)/checkout'] = 'menu/web/checkout';
$route['customer'] = 'menu/web/storeCustomerInfo';
$route['order/create'] = 'menu/web/storeOrderInfo';
$route['order-item'] = 'menu/web/storeOrderItemInfo';

// web routes
$route['web/offer/(:any)'] = 'web/singeOffer';
$route['web/restaurant/(:any)'] = 'web/offersOfRestaurant';
$route['stores/(:any)'] = 'web/allRestaurants';
$route['web/category/(:any)'] = 'web/offersOfTag';
$route['web/offers/search/(:any)'] = 'web/searchOfferByRestaurantAndTag';
$route['web/restaurants'] = 'web/allRestaurants';