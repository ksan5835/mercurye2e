<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

  
class CustomerController extends Controller{
  
  
   /**
     * @SWG\Get(
     *     path="/v1/customer",
     *     tags={"customer"},
     *     operationId="index",
     *     summary="get all customer list",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
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
  
  
    public function index(){
  
        $Customer  = Customer::all();
  
        return response()->json($Customer);
  
    }
  

   /**
     * @SWG\Get(
     *     path="/v1/customer/{id}",
     *     tags={"customer"},
     *     operationId="getCustomer",
     *     summary="get customer by id",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},
	 	   @SWG\Parameter(
     *         description="ID of pet to return",
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
     *         description="Pet not found"
     *     ),
     *     security={
     *       {"api_key": {}},
     *       {"petstore_auth": {"write:pets", "read:pets"}}
     *     }
	 

     * )
     */
 
  
    public function getCustomer($id){
  
        $Customer  = Customer::find($id);
		
		if(!empty($Customer)){
			return $this->createSuccessResponse($Customer, 200);
		}
		
		return $this->createErrorResponse('The given id is not available. Need to register as new user.', 404);
    }
	
	
	
	
	public function getCustomerEmail($email){

			$userExists = Customer::where('email', $email)->count();

			if($userExists) {
				return $this->createSuccessResponse("Email ID is available.", 200);
			}else{
				return response()->json("No user available for this ID.Please register as new user");
			}
    }
	
	/* public function getCustomerEmailBooking($email, $bookdate){
		
		$email_explode = explode(',',$email);

			$userExists1 = Customer::where('email', $email_explode[0])->count();
			$userExists2 = Customer::where('email', $email_explode[1])->count();

			if($userExists1 && $userExists2) {
				return $this->createSuccessResponse("We have confirmed the booking.", 200);
			}else{
				if($userExists1){
					return $this->createErrorResponse($email_explode[1]." is not available.Please register as new user", 404);
				}else if($userExists2){
					return $this->createErrorResponse($email_explode[0]." is not available.Please register as new user", 404);
				}else{
					return $this->createErrorResponse("Both the user is not available.Please register as new user",404);
				}
				
			}
			
	
    } */
	
	//For one to one meating check
	public function getCustomerEmailBooking($email, $branch_id, $service_id, $bookdate, $timeinterval1, $timeinterval2, $timezone_id){
		
		DB::connection()->enableQueryLog();
		$email_explode = explode(',',$email);
		$branch_explode = explode(',',$branch_id);
		$service_explode = explode(',',$service_id);
		$bookdate_explode =  explode(',',$bookdate);
		$timeinterval_explode1 =  explode(',',$timeinterval1);
		$timeinterval_explode2 =  explode(',',$timeinterval2);
		
		$branch1_id = $branch_explode[0];
		$branch2_id = (!isset($branch_explode[1]))? "" : $branch_explode[1];
		
		$service1_id = $service_explode[0];
		$service2_id = (!isset($service_explode[1]))? "" : $service_explode[1];
				
		$start_datetime = date_create($bookdate_explode[0]);
		$start_date = date_format($start_datetime,"Y-m-d");		
		
		$start_time1 = $timeinterval_explode1[0];		
		$end_time1 = $timeinterval_explode1[1];
		
		$start_time2 = $timeinterval_explode2[0];		
		$end_time2 = $timeinterval_explode2[1];

		$user1_id = DB::table('provider')->where('email', $email_explode[0])->value('user_id');	
		$user2_id = DB::table('customer')->where('email', $email_explode[1])->value('user_id');
		if(!$user2_id){$user2_id = 0;}
		
		//$test = $this->test();die;
		
		if($user1_id) {
			
				$get_branch1 = $this->getProviderWithBranch($user1_id,$branch1_id);
			
			if($get_branch1){
				
					$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
				
				if($get_service1){
					
						
						$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
						$get_provider_timezone = DB::table('timezone')
												->leftJoin('provider_biz_detail', 'timezone.timezone_id', '=', 'provider_biz_detail.timezone_id')
												->select('timezone.gmt')
												->where('provider_biz_detail.provider_id', '=', $user1_id)
												->whereNOTNull('provider_biz_detail.provider_id')
												->value('timezone.gmt');
						$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');
						
						$userTimezone = new DateTimeZone($get_customer_timezone_vlaue);
						$vendorTimezone = new DateTimeZone($get_provider_timezone);
						$vendorStartTime = new DateTime($start_date.' '.$start_time1, $vendorTimezone);
						$offset = $userTimezone->getOffset($vendorStartTime);
						$vendor_starttime_slot = date('Y-m-d H:i:s', $vendorStartTime->format('U') + $offset);
						
						$vendorEndTime = new DateTime($start_date.' '.$end_time1, $vendorTimezone);
						$offset = $userTimezone->getOffset($vendorEndTime);
						$vendor_endtime_slot = date('Y-m-d H:i:s', $vendorEndTime->format('U') + $offset);
						
						$check_vendor_slot_available = $this->getProviderTimeSlots($user1_id,$vendor_starttime_slot,$vendor_endtime_slot);							 
						
						//$queries    = DB::getQueryLog();
						//$last_query = end($queries);

						//echo 'Query<pre>';
							//print_r($last_query);
						//exit;
						//print_r($results[0]->workinghours_id);
						//die;
						//$check_vendor_slot_available = 1;
						

						$vendor_book_date = date_create($vendor_starttime_slot);
						$vendor_aval_date = date_format($vendor_book_date,"Y-m-d");

						$vendor_start_time = date_create($vendor_starttime_slot);
						$vendor_aval_start_time = date_format($vendor_start_time,"H:i");

						$vendor_end_time = date_create($vendor_endtime_slot);
						$vendor_aval_end_time = date_format($vendor_end_time,"H:i");	

						$slot_available = $this->checkBookedSlots($user1_id,$vendor_aval_date,$vendor_aval_start_time,$vendor_aval_end_time,$get_provider_timezone_id);					

					if($get_provider_timezone_id){
					
						if($check_vendor_slot_available){
					
							if($slot_available){
				
								return $this->createErrorResponse($email_explode[1]." and ".$email_explode[0]." already booked with the time slot ".$start_date." ".$start_time." - ".$end_time , 404);
							}else{
						
								$input_array = array('customer_id' => $user2_id, 'vendor_id' => $user1_id, 'booking_date' => $vendor_aval_date, 'booking_start_time' => $vendor_aval_start_time, 'booking_end_time' => $vendor_aval_end_time, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details = $this->putConfirmationEntry($input_array);
					
								return response()->json($get_confirmation_details);
								
							}
							}else{
								return $this->createErrorResponse($email_explode[0]." is not available for your time slot.Please check another time slot.", 404);
								}
						}else{
							return $this->createErrorResponse($email_explode[0]." user time zone not available.", 404);
				
						}
						
				}else{
				
					return $this->createErrorResponse("The given service is not available in the branch.", 404);			
				}
			}else{
				
				return $this->createErrorResponse("The given branch is not available.", 404);			
			}	
		}else{
				
			return $this->createErrorResponse($email_explode[0]." is not available.Please register as new user", 404);			
		}	
	
    }
	
	public function getConfirmedBooking(Request $request){

			$getConfirmedBooking = DB::table('customer_booking_confirmation')->distinct()->get();

			if($getConfirmedBooking) {
				return response()->json($getConfirmedBooking,200);
			}else{
				return response()->json("No confirmed booking for this list.",403);
			}
    }
	
	public function createCustomer(Request $request){
  
        $Custom = Customer::create($request->all());
  
        return response()->json($Custom);
  
    }
	
	public function deleteCustomer($id){
        $Custom  = Customer::find($id);
        $Custom->delete();
 
        return response()->json('deleted');
    }
  
    public function updateCustomer(Request $request,$id){
        $Customer  = Customer::find($id);
        $Customer->first_name = $request->input('first_name');
        $Customer->last_name = $request->input('last_name');
        $Customer->email = $request->input('email');
		$Customer->mobile = $request->input('mobile');
        $Customer->save();
  
        return response()->json($Customer);
    }
	
	public function getProviderTimeSlots($provider_id,$start_time,$end_time){
		
		$check_vendor_slot_available = DB::select( DB::raw("SELECT workinghours_id FROM biz_staff_workinghours WHERE staff_id = '$provider_id' and date(start_time) <= date('$start_time') and date(end_time) >= date('$end_time')") );
		if($check_vendor_slot_available){
			$check_vendor_slot_available_id = $check_vendor_slot_available[0]->workinghours_id;
			}else{
				$check_vendor_slot_available_id = '';
				}
		
		return $check_vendor_slot_available_id;
		
	}
	
	public function checkBookedSlots($provider_id,$vendor_aval_date,$vendor_aval_start_time,$vendor_aval_end_time,$get_provider_timezone_id){
		
		$slot_available = DB::table('customer_booking_confirmation')
							 ->where('vendor_id', '=', $provider_id)
							 ->where('booking_date', '=', $vendor_aval_date)
							 ->where('booking_start_time', '=', $vendor_aval_start_time)
							 ->where('booking_end_time', '=', $vendor_aval_end_time)
							 ->where('booking_timezone_id', '=', $get_provider_timezone_id)
							 ->value('id');
		
		return $slot_available;
		
	}
	
	public function putConfirmationEntry($input_array){
		
		DB::table('customer_booking_confirmation')->insert([$input_array]);
						
		$get_confirmation_details = DB::table('customer_booking_confirmation')
										 ->where('customer_id', '=', $input_array['customer_id'])
										 ->where('vendor_id', '=', $input_array['vendor_id'])
										 ->where('booking_date', '=', $input_array['booking_date'])
										 ->where('booking_start_time', '=', $input_array['booking_start_time'])
										 ->where('booking_end_time', '=', $input_array['booking_end_time'])
										 ->where('booking_timezone_id', '=', $input_array['booking_timezone_id'])
										 ->get();
		
		return $get_confirmation_details;
		
	}
	
	public function getProviderWithBranch($provider_id, $branch_id){
		
		$branch_name = DB::table('provider_biz_branch')
                     ->select('branch_id')
                     ->where('provider_id', '=', $provider_id)
					 ->where('branch_id', '=', $branch_id)
                     ->value('branch_name');
		return $branch_name;
	} 
	
	
	public function getServiceWithBranch($service_id, $branch_id){
		
		$service_name = DB::table('provider_biz_service')
                     ->select('service_name')
                     ->where('service_id', '=', $service_id)
					 ->where('biz_id', '=', $branch_id)
                     ->value('service_name');
		return $service_name;
	} 
	
}
