<?php

namespace App\Http\Controllers;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers
 *
 * @SWG\Swagger(
 *     basePath="/ae2e-io/e2eapidemo/public/api",
 *     host="dev104.mercuryminds.com",
 *     schemes={"http"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="Appointment e2e",
 *         @SWG\Contact(name="Mercuryminds", url="https://www.mercuryminds.com"),
 *     ),
 *     @SWG\Definition(
 *         definition="Error",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     )
 * )
 */
 
 /**
     * @SWG\Get(
     *     path="/v1/branch/{branch_id}",
     *     tags={"branch"},
     *     operationId="getBranch",
     *     summary="get branch by id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="ID of branch to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"

     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="branch not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/branch/email/{branch_email}",
     *     tags={"branch"},
     *     operationId="getBranchEmail",
     *     summary="get branch by email",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="branch information to return",
     *         in="path",
     *         name="branch_email",
     *         required=true,
     *         type="string",
     *         format="email"    	 
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid email supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="branch not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/branch/{branch_id}/service",
     *     tags={"branch"},
     *     operationId="getBranchEmail",
     *     summary="getBranchServiceList by id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="branch service list to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service list not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/branch/{branch_id}/service/{service_id}",
     *     tags={"branch"},
     *     operationId="getBranchService",
     *     summary="get branch servie by branch id , service id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="branch service list to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="service list to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Delete(
     *     path="/v1/branch/delete/{id}",
     *     tags={"branch"},
     *     operationId="deleteBranch",
     *     summary="delete branch by id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="delete branch by id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service list not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/staff/slot/{staff_email}/{availdate}",
     *     tags={"Staff"},
     *     operationId="getStaffSlotEmail",
     *     summary="get staff availability by email and date time",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="staff details to return",
     *         in="path",
     *         name="staff_email",
     *         required=true,
     *         type="string",
     *         format="email"  	 
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service list not found"
     *     )	 

     * )
     */
	 
	  /**
     * @SWG\Get(
     *     path="/v1/provider",
     *     tags={"Provider"},
     *     operationId="index",
     *     summary="get all providers list",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 ,
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service list not found"
     *     )	 

     * )
     */
	 
	  /**
     * @SWG\Get(
     *     path="/v1/provider/{provider_id}",
     *     tags={"Provider"},
     *     operationId="getProvider",
     *     summary="get provider by id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider details to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service list not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/provider/email/{provider_email}",
     *     tags={"Provider"},
     *     operationId="getProviderEmail",
     *     summary="get provider by email",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider details to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,   
	 *         type="string",
     *         format="email"  	 
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service list not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/customer/email/{email}",
     *     tags={"customer"},
     *     operationId="getCustomerEmail",
     *     summary="get customer by email",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="customer details to return",
     *         in="path",
     *         name="email",
     *         required=true,
     *         type="string",
	 *         format="email"  
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid email supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *     ),
     *     security={
     *       {"api_key": {}},
     *       {"petstore_auth": {"write:pets", "read:pets"}}
     *     }
	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/customer/emailbook/{email}/{branch_id}/{service_id}/{staff_id}/{bookdate}/{timeinterval1}/{timeinterval2}/{timezone_id}/{type}",
     *     tags={"customer"},
     *     operationId="getCustomerEmailBooking",
     *     summary="getCustomerEmailBooking",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="customer details to return",
     *         in="path",
     *         name="email",
     *         required=true,
     *         type="string",
     *         format="email"  
     *     ),
	 	   @SWG\Parameter(
     *         description="branch_id details to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer"
     *     ),
	 	   @SWG\Parameter(
     *         description="service_id details to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer"
     *     ),
	 	   @SWG\Parameter(
     *         description="staff_id details to return",
     *         in="path",
     *         name="staff_id",
     *         required=true,
     *         type="integer"
     *     ),
	 	   @SWG\Parameter(
     *         description="bookdate details to return",
     *         in="path",
     *         name="bookdate",
     *         required=true,
     *         type="string",
	 *         format="date"    
     *     ),
	 	   @SWG\Parameter(
     *         description="timeinterval1 details to return",
     *         in="path",
     *         name="timeinterval1",
     *         required=true,
     *         type="string"   
     *     ),
	 	   @SWG\Parameter(
     *         description="timeinterval2 details to return",
     *         in="path",
     *         name="timeinterval2",
     *         required=true,
     *         type="string"   
     *     ),
	 	   @SWG\Parameter(
     *         description="timezone_id details to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer"   
     *     ),
	 	   @SWG\Parameter(
     *         description="type details to return",
     *         in="path",
     *         name="type",
     *         required=true,
     *         type="integer"   
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid email supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *     ),
     *     security={
     *       {"api_key": {}},
     *       {"petstore_auth": {"write:pets", "read:pets"}}
     *     }
	 

     * )
     */
	 
	 
	 /**
     * @SWG\Get(
     *     path="/v1/customer/booking/list",
     *     tags={"customer"},
     *     operationId="getConfirmedBooking",
     *     summary="get confirmed booking list",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	  *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid data supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="booking list not found"
     *     )

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/branchlist/{service_id}",
     *     tags={"branch"},
     *     operationId="getServiceBranchList ",
     *     summary="get branch list by service id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="service_id service list to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),	 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/provider/{provider_id}/availdate/{availdate}/timezoneid/{timezone_id}",
     *     tags={"Provider"},
     *     operationId="getProviderTimeSlots",
     *     summary="get provider Time slotes",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_id service list to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="availdate service list to return",
     *         in="path",
     *         name="availdate",
     *         required=true,
     *         type="string",
     *         format="date"      
     *     ),	
	 	   @SWG\Parameter(
     *         description="timezone_id service list to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ), 		 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 
	 /**
     * @SWG\Get(
     *     path="/v1/branch/{branch_id}/availdate/{availdate}/timezoneid/{timezone_id}",
     *     tags={"branch"},
     *     operationId="getBranchTimeSlots",
     *     summary="getBranchTimeSlots",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="branch_id service list to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="availdate service list to return",
     *         in="path",
     *         name="availdate",
     *         required=true,
     *         type="string",
     *         format="date"      
     *     ),	
	 	   @SWG\Parameter(
     *         description="timezone_id service list to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ), 		 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/provider/{provider_id}/bookdate/{bookdate}",
     *     tags={"Provider"},
     *     operationId="getProviderAvaliableTimeSlots",
     *     summary="getProviderAvaliableTimeSlots",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_id service list to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="bookdate service list to return",
     *         in="path",
     *         name="bookdate",
     *         required=true,
     *         type="string",
     *         format="date"      
     *     ),	 		 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	  /**
     * @SWG\Get(
     *     path="/v1/timezone/date/{tz_date}/time/{tz_time}/customertz/{customer_tz}/providertz/{provider_tz}",
     *     tags={"customer"},
     *     operationId="getTimeSlotWithTimezone",
     *     summary="getTimeSlotWithTimezone",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="tz_date service list to return",
     *         in="path",
     *         name="tz_date",
     *         required=true,
     *         type="string",
     *         format="date"      
     *     ),
	 	   @SWG\Parameter(
     *         description="tz_time service list to return",
     *         in="path",
     *         name="tz_time",
     *         required=true,
     *         type="string"     
     *     ),	
	 	   @SWG\Parameter(
     *         description="customer_tz service list to return",
     *         in="path",
     *         name="customer_tz",
     *         required=true,
     *         type="string"     
     *     ), 	
	 	   @SWG\Parameter(
     *         description="provider_tz service list to return",
     *         in="path",
     *         name="provider_tz",
     *         required=true,
     *         type="string"     
     *     ),	 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 
	 /**
     * @SWG\Get(
     *     path="/v1/branch/{branch_id}/bookdate/{bookdate}",
     *     tags={"branch"},
     *     operationId="getBranchAvaliableTimeSlots",
     *     summary="getBranchAvaliableTimeSlots",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="branch_id service list to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="bookdate service list to return",
     *         in="path",
     *         name="bookdate",
     *         required=true,
     *         type="string",
     *         format="date"      
     *     ),		 	 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 /**
     * @SWG\Get(
     *     path="/v1/provider/{provider_id}/branch/{branch_id}",
     *     tags={"Provider"},
     *     operationId="getProviderWithBranch",
     *     summary="getProviderWithBranch",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_id service list to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="branch_id service list to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),	 		 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	 
	 /**
     * @SWG\Get(
     *     path="/v1/service/{service_id}/branch/{branch_id}",
     *     tags={"customer"},
     *     operationId="getServiceWithBranch",
     *     summary="getServiceWithBranch",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="service_id list to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="branch_id to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),		 	 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */
	 
	  /**
     * @SWG\Get(
     *     path="/v1/service/{service_id}/staff/{staff_id}",
     *     tags={"customer"},
     *     operationId="getStaffWithService",
     *     summary="getStaffWithService",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="service_id list to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer",
     *         format="int64"      
     *     ),
	 	   @SWG\Parameter(
     *         description="staff_id to return",
     *         in="path",
     *         name="staff_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),		 	 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	 
	 
	  /**
     * @SWG\Get(
     *     path="/v1/matrix1/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{start_date}/{start_time1}/{end_time1}/{timezone_id}",
     *     tags={"customer"},
     *     operationId="getMatrix1_Result",
     *     summary="getMatrix1_Result",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_email list to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,
     *         type="string"    
     *     ),
	 	   @SWG\Parameter(
     *         description="user_email to return",
     *         in="path",
     *         name="user_email",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="provider_id to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),
	  	   @SWG\Parameter(
     *         description="user_id to return",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	 	   @SWG\Parameter(
     *         description="branch1_id to return",
     *         in="path",
     *         name="branch1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service1_id to return",
     *         in="path",
     *         name="service1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	  	   @SWG\Parameter(
     *         description="start_date to return",
     *         in="path",
     *         name="start_date",
     *         required=true,
     *         type="string",
	 *         format="date"   
     *     ),
	   	   @SWG\Parameter(
     *         description="start_time1 to return",
     *         in="path",
     *         name="start_time1",
     *         required=true,
     *         type="string"
	 *		   format="time"
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time1 to return",
     *         in="path",
     *         name="end_time1",
     *         required=true,
     *         type="string"
     *     ),
	  	   @SWG\Parameter(
     *         description="timezone_id to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),		 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	
	 
	  /**
     * @SWG\Get(
     *     path="/v1/matrix3/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{start_date}/{start_time1}/{end_time1}/{service2_id}/{start_time2}/{end_time2}/{timezone_id}",
     *     tags={"customer"},
     *     operationId="getMatrix3_Result",
     *     summary="getMatrix3_Result",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_email list to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,
     *         type="string"    
     *     ),
	 	   @SWG\Parameter(
     *         description="user_email to return",
     *         in="path",
     *         name="user_email",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="provider_id to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),
	  	   @SWG\Parameter(
     *         description="user_id to return",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	 	   @SWG\Parameter(
     *         description="branch1_id to return",
     *         in="path",
     *         name="branch1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service1_id to return",
     *         in="path",
     *         name="service1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	  	   @SWG\Parameter(
     *         description="start_date to return",
     *         in="path",
     *         name="start_date",
     *         required=true,
     *         type="string",
	 *         format="date"   
     *     ),
	   	   @SWG\Parameter(
     *         description="start_time1 to return",
     *         in="path",
     *         name="start_time1",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time1 to return",
     *         in="path",
     *         name="end_time1",
     *         required=true,
     *         type="string"
     *     ),
	  	   @SWG\Parameter(
     *         description="service2_id to return",
     *         in="path",
     *         name="service2_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="start_time2 to return",
     *         in="path",
     *         name="start_time2",
     *         required=true,
     *         type="string"  
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time2 to return",
     *         in="path",
     *         name="end_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="timezone_id to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	
	 
	 
	  /**
     * @SWG\Get(
     *     path="/v1/matrix5/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{start_date}/{start_time1}/{end_time1}/{branch2_id}/{service2_id}/{start_time2}/{end_time2}/{timezone_id}/{type}",
     *     tags={"customer"},
     *     operationId="getMatrix5_Result",
     *     summary="getMatrix5_Result",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_email list to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,
     *         type="string"    
     *     ),
	 	   @SWG\Parameter(
     *         description="user_email to return",
     *         in="path",
     *         name="user_email",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="provider_id to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),
	  	   @SWG\Parameter(
     *         description="user_id to return",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	 	   @SWG\Parameter(
     *         description="branch1_id to return",
     *         in="path",
     *         name="branch1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service1_id to return",
     *         in="path",
     *         name="service1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	  	   @SWG\Parameter(
     *         description="start_date to return",
     *         in="path",
     *         name="start_date",
     *         required=true,
     *         type="string",
	 *         format="date"   
     *     ),
	   	   @SWG\Parameter(
     *         description="start_time1 to return",
     *         in="path",
     *         name="start_time1",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time1 to return",
     *         in="path",
     *         name="end_time1",
     *         required=true,
     *         type="string"
     *     ),
	  	   @SWG\Parameter(
     *         description="branch2_id to return",
     *         in="path",
     *         name="branch_id1",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service2_id to return",
     *         in="path",
     *         name="service2_id",
     *         required=true,
     *         type="string"  
     *     ),	
	  	   @SWG\Parameter(
     *         description="start_time2 to return",
     *         in="path",
     *         name="start_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="end_time2 to return",
     *         in="path",
     *         name="end_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="timezone_id to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="type to return",
     *         in="path",
     *         name="type",
     *         required=true,
     *         type="string"  
     *     ), 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	
	 
	 
	 /**
     * @SWG\Get(
     *     path="/v1/matrix5/{provider_email}/{email}/{provider_id}/{user_id}/{branch_id}/{service_id}/{bookdate}/{start_time1}/{end_time1}/{branch_id1}/{service_id1}/{start_time2}/{end_time2}/{timezone_id}/{type}",
     *     tags={"customer"},
     *     operationId="getMatrix5_Result",
     *     summary="getMatrix5_Result",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_email list to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,
     *         type="string"    
     *     ),
	 	   @SWG\Parameter(
     *         description="email to return",
     *         in="path",
     *         name="email",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="provider_id to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),
	  	   @SWG\Parameter(
     *         description="user_id to return",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	 	   @SWG\Parameter(
     *         description="branch_id to return",
     *         in="path",
     *         name="branch_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service_id to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	  	   @SWG\Parameter(
     *         description="bookdate to return",
     *         in="path",
     *         name="bookdate",
     *         required=true,
     *         type="string",
	 *         format="date"   
     *     ),
	   	   @SWG\Parameter(
     *         description="start_time1 to return",
     *         in="path",
     *         name="start_time1",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time1 to return",
     *         in="path",
     *         name="end_time1",
     *         required=true,
     *         type="string"
     *     ),
	  	   @SWG\Parameter(
     *         description="branch_id1 to return",
     *         in="path",
     *         name="branch_id1",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service_id1 to return",
     *         in="path",
     *         name="service_id1",
     *         required=true,
     *         type="string"  
     *     ),	
	  	   @SWG\Parameter(
     *         description="start_time2 to return",
     *         in="path",
     *         name="start_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="end_time2 to return",
     *         in="path",
     *         name="end_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="timezone_id to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="type to return",
     *         in="path",
     *         name="type",
     *         required=true,
     *         type="string"  
     *     ), 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	
	 
	  /**
     * @SWG\Get(
     *     path="/v1/matrix7/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{start_time1}/{end_time1}/{branch2_id}/{service2_id}/{staff2_id}/{start_time2}/{end_time2}/{timezone_id}/{type}",
     *     tags={"customer"},
     *     operationId="getMatrix7_Result",
     *     summary="getMatrix7_Result",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_email list to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,
     *         type="string"    
     *     ),
	 	   @SWG\Parameter(
     *         description="user_email to return",
     *         in="path",
     *         name="user_email",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="provider_id to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),
	  	   @SWG\Parameter(
     *         description="user_id to return",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	 	   @SWG\Parameter(
     *         description="branch1_id to return",
     *         in="path",
     *         name="branch1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service1_id to return",
     *         in="path",
     *         name="service_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	   	   @SWG\Parameter(
     *         description="staff1_id to return",
     *         in="path",
     *         name="staff1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	  	   @SWG\Parameter(
     *         description="start_date to return",
     *         in="path",
     *         name="start_date",
     *         required=true,
     *         type="string",
	 *         format="date"   
     *     ),
	   	   @SWG\Parameter(
     *         description="start_time1 to return",
     *         in="path",
     *         name="start_time1",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time1 to return",
     *         in="path",
     *         name="end_time1",
     *         required=true,
     *         type="string"
     *     ),
	  	   @SWG\Parameter(
     *         description="branch2_id to return",
     *         in="path",
     *         name="branch2_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service2_id to return",
     *         in="path",
     *         name="service2_id",
     *         required=true,
     *         type="string"  
     *     ),	
	  	   @SWG\Parameter(
     *         description="staff2_id to return",
     *         in="path",
     *         name="staff2_id",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="start_time2 to return",
     *         in="path",
     *         name="start_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="end_time2 to return",
     *         in="path",
     *         name="end_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="timezone_id to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="type to return",
     *         in="path",
     *         name="type",
     *         required=true,
     *         type="string"  
     *     ), 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	
	 
	 /**
     * @SWG\Get(
     *     path="/v1/matrix9/{provider_email}/{user_email}/{provider_id}/{user_id}/{branch1_id}/{service1_id}/{staff1_id}/{start_date}/{start_time1}/{end_time1}/{branch2_id}/{service2_id}/{staff2_id}/{start_time2}/{end_time2}/{timezone_id}/{type}",
     *     tags={"customer"},
     *     operationId="getMatrix9_Result",
     *     summary="getMatrix9_Result",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="provider_email list to return",
     *         in="path",
     *         name="provider_email",
     *         required=true,
     *         type="string"    
     *     ),
	 	   @SWG\Parameter(
     *         description="user_email to return",
     *         in="path",
     *         name="user_email",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="provider_id to return",
     *         in="path",
     *         name="provider_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),
	  	   @SWG\Parameter(
     *         description="user_id to return",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	 	   @SWG\Parameter(
     *         description="branch1_id to return",
     *         in="path",
     *         name="branch1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service1_id to return",
     *         in="path",
     *         name="service1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	   	   @SWG\Parameter(
     *         description="staff1_id to return",
     *         in="path",
     *         name="staff1_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ), 
	  	   @SWG\Parameter(
     *         description="start_date to return",
     *         in="path",
     *         name="start_date",
     *         required=true,
     *         type="string",
	 *         format="date"   
     *     ),
	   	   @SWG\Parameter(
     *         description="start_time1 to return",
     *         in="path",
     *         name="start_time1",
     *         required=true,
     *         type="string"
     *     ),	
	  	   @SWG\Parameter(
     *         description="end_time1 to return",
     *         in="path",
     *         name="end_time1",
     *         required=true,
     *         type="string"
     *     ),
	  	   @SWG\Parameter(
     *         description="branch2_id to return",
     *         in="path",
     *         name="branch2_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="service2_id to return",
     *         in="path",
     *         name="service2_id",
     *         required=true,
     *         type="string"  
     *     ),	
	  	   @SWG\Parameter(
     *         description="staff2_id to return",
     *         in="path",
     *         name="staff2_id",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="start_time2 to return",
     *         in="path",
     *         name="start_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="end_time2 to return",
     *         in="path",
     *         name="end_time2",
     *         required=true,
     *         type="string"  
     *     ),
	  	   @SWG\Parameter(
     *         description="timezone_id to return",
     *         in="path",
     *         name="timezone_id",
     *         required=true,
     *         type="integer",
	 *         format="int64"   
     *     ),	
	  	   @SWG\Parameter(
     *         description="type to return",
     *         in="path",
     *         name="type",
     *         required=true,
     *         type="string"  
     *     ), 	   
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Service not found"
     *     )	 

     * )
     */	
 
class ApiController extends Controller
{
}