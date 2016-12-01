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

$app->get('api/v1/customer','ServiceappointmentController@index');
 
$app->get('api/v1/customer/{id}','ServiceappointmentController@getcustomer');

$app->get('api/v1/customer/email/{email}','ServiceappointmentController@getCustomerEmail');

$app->get('api/v1/customer/emailbook/{email}/{branch_id}/{service_id}/{staff_id}/{bookdate}/{timeinterval1}/{timeinterval2}/{timezone_id}/{type}','ServiceappointmentController@getCustomerEmailBooking');

$app->get('api/v1/customer/booking/list','ServiceappointmentController@getConfirmedBooking');

$app->post('api/v1/customer','ServiceappointmentController@createCustomer');
 
$app->put('api/v1/customer/{id}','ServiceappointmentController@updateCustomer');

$app->get('api/v1/customer/provider/{provider_id}/branch/{branch_id}/staff/{staff_id}/vstart/{vstart_time}/vend/{vend_time}/timezoneid/{timezone_id}','BranchController@checkBookedSlots');

$app->get('api/v1/provider/{provider_id}/branch/{branch_id}','ServiceappointmentController@getProviderWithBranch');

$app->get('api/v1/timezone/date/{tz_date}/time/{tz_time}/customertz/{customer_tz}/providertz/{provider_tz}','ServiceappointmentController@getTimeSlotWithTimezone');

$app->get('api/v1/service/{service_id}/branch/{branch_id}','ServiceappointmentController@getServiceWithBranch');

$app->get('api/v1/service/{service_id}/staff/{staff_id}','ServiceappointmentController@getStaffWithService');

//$app->get('api/v1/branch/{branch_id}/availdate/{availdate}/timezoneid/{timezone_id}','ServiceappointmentController@getBranchTimeSlots');

$app->get('api/v1/branch/{branch_id}/availstartdate/{availstartdate}/availenddate/{availenddate}','ServiceappointmentController@getBranchTimeSlots');

$app->get('api/v1/branch/{branch_id}/bookdate/{bookdate}','ServiceappointmentController@getBranchAvaliableTimeSlots');

$app->get('api/v1/provider/{provider_id}/availdate/{availdate}/timezoneid/{timezone_id}','ServiceappointmentController@getProviderTimeSlots');

$app->get('api/v1/provider/{provider_id}/bookdate/{bookdate}','ServiceappointmentController@getProviderAvaliableTimeSlots');
 
$app->delete('api/v1/customer/{id}','ServiceappointmentController@deleteCustomer');  

//Matrix API

$app->get('api/v1/appointment/service/{participants}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{timezone_id}/{meetingtype_id}','ServiceappointmentController@getMatrix1_Result');

//$app->post('api/v1/appointment/service','ServiceappointmentController@getMatrix1_Result');

$app->get('api/v1/appointment/serviceRecurring/{participants}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{end_date}/{start_time}/{end_time}/{repetition_type}/{timezone_id}/{meetingtype_id}','ServiceappointmentController@getMatrix2_Result');

$app->get('api/v1/appointment/staff/{participants}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{timezone_id}/{meetingtype_id}','ServiceappointmentController@getMatrix3_Result');

//$app->post('api/v1/appointment/staff','ServiceappointmentController@getMatrix3_Result');

$app->get('api/v1/appointment/staffRecurring/{participants}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{end_date}/{start_time}/{end_time}/{repetition_type}/{timezone_id}/{meetingtype_id}','ServiceappointmentController@getMatrix4_Result');

$app->get('api/v1/matrix5/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{start_date}/{start_time1}/{end_time1}/{branch2_id}/{service2_id}/{start_time2}/{end_time2}/{timezone_id}/{type}','ServiceappointmentController@getMatrix5_Result');

$app->get('api/v1/matrix7/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{start_time1}/{end_time1}/{branch2_id}/{service2_id}/{staff2_id}/{start_time2}/{end_time2}/{timezone_id}/{type}','ServiceappointmentController@getMatrix7_Result');

$app->get('api/v1/matrix9/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{start_time1}/{end_time1}/{branch2_id}/{service2_id}/{staff2_id}/{start_time2}/{end_time2}/{timezone_id}/{type}','ServiceappointmentController@getMatrix9_Result');


//Branch API

$app->get('api/v1/branch','BranchController@index'); 
 
$app->get('api/v1/branch/{branch_id}','BranchController@getBranch'); 

$app->get('api/v1/branch/email/{branch_email}','BranchController@getBranchEmail');

$app->get('api/v1/branch/{branch_id}/service','BranchController@getBranchServiceList');

$app->get('api/v1/branchlist/{service_id}','BranchController@getServiceBranchList');

$app->get('api/v1/branch/{branch_id}/service/{service_id}','BranchController@getBranchService');

$app->get('api/v1/branch/delete/{branch_id}','BranchController@deleteBranch'); 

// Staff API

$app->get('api/v1/staff/slot/{staff_email}/{availdate}','BranchController@getStaffSlotEmail');

//Provider API
$app->get('api/v1/provider','ProviderController@index');

$app->get('api/v1/provider/{provider_id}','ProviderController@getProvider');

$app->get('api/v1/provider/email/{provider_email}','ProviderController@getProviderEmail');

//$app->get('api/v1/staff/slot/{staff_id}/{availdate}/{timezone_id}','BranchController@getStaffSlotByID');




