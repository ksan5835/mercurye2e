<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->post('oauth/access_token', function() {
    return response()->json(Authorizer::issueAccessToken());
});

$app->get('/', function() use ($app) {
    return "Lumen RESTful API By CoderExample arun";
});
 
//Customer API

$app->get('api/v1/customer','CustomerController@index');
 
$app->get('api/v1/customer/{id}','CustomerController@getcustomer');

$app->get('api/v1/customer/email/{email}','CustomerController@getCustomerEmail');

$app->get('api/v1/customer/emailbook/{email}/{bookdate}/{timeinterval}/{timezone}','CustomerController@getCustomerEmailBooking');

$app->post('api/v1/customer','CustomerController@createCustomer');
 
$app->put('api/v1/customer/{id}','CustomerController@updateCustomer');
 
$app->delete('api/v1/customer/{id}','CustomerController@deleteCustomer'); 

//Branch API

$app->get('api/v1/branch','BranchController@index');
 
$app->get('api/v1/branch/{branch_id}','BranchController@getBranch');

$app->get('api/v1/branch/email/{branch_email}','BranchController@getBranchEmail');
 
$app->delete('api/v1/branch/{id}','BranchController@deleteBranch'); 