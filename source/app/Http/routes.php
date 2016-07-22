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

$app->get('api/v1/customer/emailbook/{email}/{branch_id}/{service_id}/{bookdate}/{timeinterval1}/{timeinterval2}/{timezone_id}','CustomerController@getCustomerEmailBooking');

$app->get('api/v1/customer/booking/list','CustomerController@getConfirmedBooking');

$app->post('api/v1/customer','CustomerController@createCustomer');
 
$app->put('api/v1/customer/{id}','CustomerController@updateCustomer');
 
$app->delete('api/v1/customer/{id}','CustomerController@deleteCustomer'); 

//Branch API

$app->get('api/v1/branch','BranchController@index');
 
$app->get('api/v1/branch/{branch_id}','BranchController@getBranch');

$app->get('api/v1/branch/email/{branch_email}','BranchController@getBranchEmail');

$app->get('api/v1/branch/{branch_id}/service','BranchController@getBranchServiceList');

$app->get('api/v1/branch/{branch_id}/service/{service_id}','BranchController@getBranchService');

$app->delete('api/v1/branch/{id}','BranchController@deleteBranch'); 

// Staff API

$app->get('api/v1/staff/slot/{staff_email}/{availdate}','BranchController@getStaffSlotEmail');

//Provider API
$app->get('api/v1/provider','ProviderController@index');

$app->get('api/v1/provider/{provider_id}','ProviderController@getProvider');

$app->get('api/v1/provider/email/{provider_email}','ProviderController@getProviderEmail');


//$app->get('api/v1/staff/slot/{staff_id}/{availdate}/{timezone_id}','BranchController@getStaffSlotByID');
