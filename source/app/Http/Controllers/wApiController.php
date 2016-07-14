<?php

namespace App\Http\Controllers;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers
 *
 * @SWG\Swagger(
 *     basePath="/lumene2e/api",
 *     host="localhost",
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
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
     *         type="string"     *         
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
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
     *     path="/v1/branch/{id}",
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
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
     *         type="string"   
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
	 *         type="string"  
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Pet")
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
 
class ApiController extends Controller
{
}