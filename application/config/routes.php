<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'project';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Project Routes
$route['project'] = "project/index";
$route['project/create'] = "project/create";
$route['project/store'] = "project/store";
$route['project/edit/(:num)'] = "project/edit/$1";
$route['project/update/(:num)'] = "project/update/$1";
$route['project/delete/(:num)'] = "project/delete/$1";
$route['project/json_list/(:num)/(:num)'] = "project/json_list/$1/$2";

// Auth Routes
$route['login'] = 'auth/login_view';
$route['register'] = 'auth/register_view';
$route['logout'] = 'auth/logout';
$route['auth/login'] = 'auth/login';
$route['auth/register'] = 'auth/register';

$route['superadmin'] = 'superadmin/login_view'; 
$route['superadmin/login'] = 'superadmin/login'; 
$route['superadmin/dashboard'] = 'superadmin/index'; 
$route['superadmin/get_users'] = 'superadmin/get_users';
$route['superadmin/toggle_status/(:num)'] = 'superadmin/toggle_status/$1';
$route['superadmin/update_role/(:num)/(:any)'] = 'superadmin/change_role/$1/$2';
$route['superadmin/logout'] = 'superadmin/logout';

$route['project/json_list/(:num)/(:num)'] = 'project/json_list/$1/$2';

$route['admin'] = 'admin/login_view';
$route['admin/login'] = 'admin/login';
$route['admin/dashboard'] = 'admin/index';
$route['admin/get_users'] = 'admin/get_users';
$route['admin/create_user'] = 'admin/create_user';
$route['admin/logout'] = 'admin/logout';




?>


