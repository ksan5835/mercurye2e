<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
use DateInterval;
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
		
		$Customer_result = array('status' => 'true','message' =>'The List of the customers.','content'=>array('invitations'=>$Customer));
		return json_encode($Customer_result);
          
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
  
		$Customer  = DB::table('customer')->where('user_id', $id)->count();	
		
		if(!empty($Customer)){
			$customerDatas = DB::table('customer')
                     ->select(DB::raw('*'))
                     ->where('user_id', '=', $id)
                     ->get();
					 
			$customerDatas_result = array('status' => 'true','message' =>'The given customer detail.','content'=>array('invitations'=>$customerDatas));
			return json_encode($customerDatas_result);
		}
		
		$customerDatas_result = array('status' => 'false','message' =>'The given id is not available. Need to register as new user.','content'=>null);
		return json_encode($customerDatas_result);
    }
	
	
	
	
	public function getCustomerEmail($email){

			$userExists = Customer::where('email', urldecode($email))->count();

			if($userExists) {
				
				$userExists_result = array('status' => 'true','message' =>'Email ID is available.','content'=>null);
				return json_encode($userExists_result);
			}else{
				$userExists_result = array('status' => 'false','message' =>'No user available for this ID.Please register as new user.','content'=>null);
				return json_encode($userExists_result);
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
	public function getCustomerEmailBooking($email, $branch_id, $service_id, $staff_id, $bookdate, $timeinterval1, $timeinterval2, $timezone_id, $type){
		
		DB::connection()->enableQueryLog();
		$email_explode = explode(',',urldecode($email));
		$branch_explode = explode(',',$branch_id);
		$service_explode = explode(',',$service_id);
		$staff_explode = explode(',',$staff_id);
		$bookdate_explode =  explode(',',$bookdate);
		$timeinterval_explode1 =  explode(',',$timeinterval1);
		$timeinterval_explode2 =  explode(',',$timeinterval2);
		
		$branch1_id = $branch_explode[0];
		$branch2_id = (!isset($branch_explode[1]))? "" : $branch_explode[1];
		
		$service1_id = $service_explode[0];
		$service2_id = (!isset($service_explode[1]))? "" : $service_explode[1];
		
		$staff1_id = (!isset($staff_explode[0]))? "" : $staff_explode[0];
		$staff2_id = (!isset($staff_explode[1]))? "" : $staff_explode[1];
				
		$start_datetime = date_create($bookdate_explode[0]);
		$start_date = date_format($start_datetime,"Y-m-d");		
		
		$start_time1 = $timeinterval_explode1[0];		
		$end_time1 = $timeinterval_explode1[1];
		
		$start_time2 = $timeinterval_explode2[0];		
		$end_time2 = $timeinterval_explode2[1];
		
		$provider_email = $email_explode[0];		
		$user_email = $email_explode[1];

		$provider_id = DB::table('provider')->where('email', $email_explode[0])->value('user_id');	
		$user_id = DB::table('customer')->where('email', $email_explode[1])->value('user_id');
		if(!$user_id){$user_id = 0;}
		
		
		
		//$test = $this->test();die;
		
		if($provider_id) {
			
				
				//Matrix 1
				if($provider_id && $branch1_id && $service2_id == "" ){
					echo "Matrix 1";
					$get_matrix1 = $this->getMatrix1_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $timezone_id);
					return response()->json($get_matrix1);
				}
				//Matrix 3
				else if($provider_id && $branch1_id && $service1_id && $service2_id && $branch2_id == ""){
					echo "Matrix 3";
					$get_matrix3 = $this->getMatrix3_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $service2_id, $start_time2, $end_time2, $timezone_id );
					return response()->json($get_matrix3);
				}
				//Matrix 5
				else if($provider_id && $branch1_id && $branch2_id && $staff2_id == ""){ 
					echo "Matrix 5";
					$get_matrix5 = $this->getmatrix5_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $branch2_id, $service2_id, $start_time2, $end_time2, $timezone_id, $type );
					return response()->json($get_matrix5);
				}
				//Matrix 7
				else if($provider_id && $branch1_id && $branch2_id && $staff1_id && $type == 0){ 
					echo "Matrix 7";
					$get_matrix7 = $this->getmatrix7_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $staff1_id, $start_date, $start_time1, $end_time1, $branch2_id, $service2_id, $staff2_id, $start_time2, $end_time2, $timezone_id, $type );
					return response()->json($get_matrix7);
				}
				//Matrix 9
				else if($provider_id && $branch1_id && $branch2_id && $staff1_id && $type != 0){ 
					echo "Matrix 9";
					$get_matrix9 = $this->getmatrix7_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $staff1_id, $start_date, $start_time1, $end_time1, $branch2_id, $service2_id, $staff2_id, $start_time2, $end_time2, $timezone_id, $type );
					return response()->json($get_matrix9);
				}
				else{
					echo "test";
				}

		}else{
			
			$provider_id_result = array('status' => 'false','message' =>'The Provider is not available.Please register as new user.','content'=>null);
			return json_encode($provider_id_result);
				
		}	
	
    }
	
	public function getConfirmedBooking(Request $request){

			$getConfirmedBooking = DB::table('customer_booking_confirmation')->distinct()->get();

			if($getConfirmedBooking) {
				$getConfirmedBooking_result = array('status' => 'true','message' =>'The Booking details.','content'=>array('invitations'=>$getConfirmedBooking));
				return json_encode($getConfirmedBooking_result);
			}else{
				$getConfirmedBooking_result = array('status' => 'false','message' =>'No confirmed booking for this list.','content'=>null);
				return json_encode($getConfirmedBooking_result);
			}
    }
	
	public function createCustomer(Request $request){
  
        $Custom = Customer::create($request->all());
		
		$Custom_result = array('status' => 'true','message' =>'The customer created.','content'=>array('invitations'=>$Custom));
		return json_encode($Custom_result);
   
    }
	
	public function deleteCustomer($id){
        $Custom  = Customer::find($id);
        $Custom->delete();
		
		$Custom_result = array('status' => 'true','message' =>'deleted','content'=>null);
		return json_encode($Custom_result);
    }
  
    public function updateCustomer(Request $request,$id){
        $Customer  = Customer::find($id);
        $Customer->first_name = $request->input('first_name');
        $Customer->last_name = $request->input('last_name');
        $Customer->email = $request->input('email');
		$Customer->mobile = $request->input('mobile');
        $Customer->save();
  
		$Customer_result = array('status' => 'true','message' =>'The customer Updated successfully.','content'=>array('invitations'=>$Customer));
		return json_encode($Customer_result);
    }
	
	public function getStaffTimeSlots($staff_id,$start_time,$end_time){
		
		$check_vendor_slot_available = DB::select( DB::raw("SELECT workinghours_id FROM biz_staff_workinghours WHERE staff_id = '$staff_id' and date(start_time) >= date('$start_time') and date(end_time) <= date('$end_time')") );
		if($check_vendor_slot_available){
			$check_vendor_slot_available_id = $check_vendor_slot_available[0]->workinghours_id;
		}else{
			$check_vendor_slot_available_id = '';
			}

		return $check_vendor_slot_available_id;
		
	}
	
	public function getMinimumBookTime($provider_id,$start_time){
		
		$Minimum_booktime = DB::table('setting_provider')
                     ->select('BR_Min_Book_Time')
                     ->where('provider_id', '=', $provider_id)
                     ->value('BR_Min_Book_Time');
					 
		 $mimimun_hours = date('Y-m-d H:i:s', strtotime('-'.$Minimum_booktime.' hours'));
		 $Minimum_book_time = ($mimimun_hours < $start_time) ? 1 : 0;

		return $Minimum_book_time;
		
	}
	
	public function getMinimumBookDate($provider_id,$start_date){
		
		$Minimum_bookdate = DB::table('setting_provider')
                     ->select('BR_Min_Book_Date')
                     ->where('provider_id', '=', $provider_id)
                     ->value('BR_Min_Book_Date');
					 
		$mimimun_date = date('Y-m-d', strtotime('-'.$Minimum_bookdate.' day'));
		$start_date = date('Y-m-d', strtotime($start_date));
		$Minimum_book_date = ($mimimun_date < $start_date)? 1 : 0; 
		

		return $Minimum_book_date;
		
	}
		
	public function getBranchTimeSlots($branch_id,$start_time,$end_time){
		
		$check_branch_slot_available = DB::select( DB::raw("SELECT workinghours_id FROM biz_branch_workinghours WHERE branch_id = '$branch_id' and date(start_time) <= date('$start_time') and date(end_time) >= date('$end_time')") );
		if($check_branch_slot_available){
			$check_branch_slot_available_id = $check_branch_slot_available[0]->workinghours_id;
		}else{
			$check_branch_slot_available_id = '';
			}
		
		return $check_branch_slot_available_id;
		
	}
	
	public function checkStaffBookedSlots($staff_id,$bookdate,$precision_start_time_slot){
		
		$vendor_book_date = date_create($bookdate);
		$bookdate = date_format($vendor_book_date,"Y-m-d");

		//$precision_start_time_slot = str_replace('.',':',$precision_start_time_slot).':00';
	
		$slot_available = DB::select( DB::raw("SELECT id FROM customer_booking_confirmation WHERE staff_id = '$staff_id' and date(booking_date) = date('$bookdate') and booking_start_time = '$precision_start_time_slot'") );

							 
		if($slot_available){
			$slot_available_id = $slot_available[0]->id;
		}else{
			$slot_available_id = '';
			}
		
		return $slot_available_id;
	}
	
	public function getProviderAvaliableTimeSlots($staff_id,$bookdate,$service_id){
		
		$start_datetime = date_create($bookdate);
		$start_date = date_format($start_datetime,"Y-m-d");	
		
		$get_service_slot_available = DB::select( DB::raw("SELECT duration,precision_time,breaks_time FROM provider_biz_service WHERE service_id = '$service_id'" ));
		
		if($get_service_slot_available[0]->precision_time){
			$precision_time_slot = explode(",",$get_service_slot_available[0]->precision_time);
			for($i=0; $i < count($precision_time_slot); $i++){
				
				$check_book_time_slot = $this->checkStaffBookedSlots($staff_id,$bookdate,$precision_time_slot[$i]);
				
				if($check_book_time_slot){
					$time_slot [] = str_replace('.',':',$precision_time_slot[$i]).':00'.' '.'(Busy)';
				}else{
					$time_slot [] = str_replace('.',':',$precision_time_slot[$i]).':00'.' '.'(Available)';
				}
			}
			
		}else{
		
		$check_vendor_slot_available = DB::select( DB::raw("SELECT start_time,end_time FROM biz_staff_workinghours WHERE staff_id = ".$staff_id." and date(end_time) = date('".$start_date."') ") );
						
			if(!empty($check_vendor_slot_available)){	
				
				$startTime = new DateTime($check_vendor_slot_available[0]->start_time);
				$endTime = new DateTime($check_vendor_slot_available[0]->end_time );
				$interval = ($get_service_slot_available[0]->duration + $get_service_slot_available[0]->breaks_time);
				$i=1;
				while($startTime <= $endTime) {
					$staff_time_slot[] = $startTime->format('H:i:s') . ' ';
					$startTime->add(new DateInterval('PT'.$interval.'M'));
					$i++;
				}
				
				for($i=0; $i < count($staff_time_slot); $i++){
				
				$check_book_time_slot = $this->checkStaffBookedSlots($staff_id,$bookdate,$staff_time_slot[$i]);
				
				if($check_book_time_slot){
					$time_slot [] = $staff_time_slot[$i].' '.'(Busy)';
				}else{
					$time_slot [] = $staff_time_slot[$i].' '.'(Available)';
				}
			}
				
			}
		}
		return $time_slot;
	}
	
	public function getBranchAvaliableTimeSlots($branch_id,$bookdate){
		
		$start_datetime = date_create($bookdate);
		$start_date = date_format($start_datetime,"Y-m-d");	
		
		$check_branch_slot_available = DB::select( DB::raw("SELECT start_time,end_time FROM biz_branch_workinghours WHERE branch_id = '$branch_id' and date(start_time) = date('$start_date') ") );
						
			if(!empty($check_branch_slot_available)){	
				
				$startTime = new DateTime($check_branch_slot_available[0]->start_time);
				$endTime = new DateTime($check_branch_slot_available[0]->end_time );
				$i=1;
				while($startTime <= $endTime) {
					$time_slot['slot'.$i] = $startTime->format('H:i:s') . ' ';
					$startTime->add(new DateInterval('PT60M'));
					$i++;
				}
		return $time_slot;
			}
			
		
	}
	
	public function checkBookedSlots($provider_id,$branch_id,$staff_id,$vendor_starttime_slot,$vendor_endtime_slot,$get_provider_timezone_id){
		
		
		$vendor_book_date = date_create($vendor_starttime_slot);
		$vendor_aval_date = date_format($vendor_book_date,"Y-m-d");

		$vendor_start_time = date_create($vendor_starttime_slot);
		$vendor_aval_start_time = date_format($vendor_start_time,"H:i");

		$vendor_end_time = date_create($vendor_endtime_slot);
		$vendor_aval_end_time = date_format($vendor_end_time,"H:i");
		
		$slot_available = DB::select( DB::raw("SELECT id FROM customer_booking_confirmation WHERE provider_id = '$provider_id' and branch_id = '$branch_id' and staff_id = '$staff_id' and booking_timezone_id = '$get_provider_timezone_id' and booking_date = '$vendor_aval_date' and booking_start_time >= '$vendor_aval_start_time' and booking_end_time <= '$vendor_aval_end_time'") );

							 
		if($slot_available){
			$slot_available_id = $slot_available[0]->id;
		}else{
			$slot_available_id = '';
			}
		
		return $slot_available_id;
		
	}
	
	public function putConfirmationEntry($input_array_old){
		
		$vendor_book_date = date_create($input_array_old['booking_date']);
		$vendor_aval_date = date_format($vendor_book_date,"Y-m-d");

		$vendor_start_time = date_create($input_array_old['booking_start_time']);
		$vendor_aval_start_time = date_format($vendor_start_time,"H:i");

		$vendor_end_time = date_create($input_array_old['booking_end_time']);
		$vendor_aval_end_time = date_format($vendor_end_time,"H:i");
		
		
		$array1 = $input_array_old;
		$array2 = array("booking_date"=>$vendor_aval_date,"booking_start_time"=>$vendor_aval_start_time,"booking_end_time"=>$vendor_aval_end_time);
		$input_array = array_replace($array1,$array2);

		
		DB::table('customer_booking_confirmation')->insert([$input_array]);
						
		$get_confirmation_details = DB::table('customer_booking_confirmation')
										 ->where('customer_id', '=', $input_array['customer_id'])
										 ->where('provider_id', '=', $input_array['provider_id'])
										 ->orwhere('branch_id', '=', $input_array['branch_id'])
										 ->orwhere('staff_id', '=', $input_array['staff_id'])
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
		
		$service_branch_id = DB::table('biz_service_branch')
                     ->select('service_branch_id')
                     ->where('service_id', '=', $service_id)
					 ->where('branch_id', '=', $branch_id)
                     ->value('service_branch_id');
		return $service_branch_id;
	} 
	
	
	public function getStaffWithServiceid($service_id){
		
		$service_staff_id = DB::table('biz_service_staff')
                     ->select('staff_id')
                     ->where('service_id', '=', $service_id)
					 ->value('staff_id');
		return $service_staff_id;
	} 
	
	public function getStaffWithService($service_id, $staff_id){
		
		/* $service_staff_id = DB::table('biz_service_staff')
                     ->select('service_staff_id')
                     ->where('service_id', '=', $service_id)
					 ->where('staff_id', '=', $staff_id)
                     ->value('service_staff_id'); */
					 
		$service_staff_name = DB::table('provider_biz_staff')
								->leftJoin('biz_service_staff', 'provider_biz_staff.staff_id', '=', 'biz_service_staff.staff_id')
								->select('provider_biz_staff.staff_email')
								->where('biz_service_staff.staff_id', '=', $staff_id)
								->value('provider_biz_staff.staff_email');
		return $service_staff_name;
	} 
	
	public function getGmtWithProviderid($provider_id){
		
		$get_provider_timezone = DB::table('timezone')
									->leftJoin('provider_biz_detail', 'timezone.timezone_id', '=', 'provider_biz_detail.timezone_id')
									->select('timezone.gmt')
									->where('provider_biz_detail.provider_id', '=', $provider_id)
									->whereNOTNull('provider_biz_detail.provider_id')
									->value('timezone.gmt');
		return $get_provider_timezone;
	} 
	
	public function getTimeSlotWithTimezone($date, $time, $customer_timezone_vlaue, $provider_timezone_value){
		
		$userTimezone = new DateTimeZone($customer_timezone_vlaue);
		$vendorTimezone = new DateTimeZone($provider_timezone_value);
		
		$StartTime = new DateTime($date.' '.$time, $vendorTimezone);
		$offset = $userTimezone->getOffset($StartTime);
		$return_timezone_date = date('Y-m-d H:i:s', $StartTime->format('U') + $offset);
		
		return $return_timezone_date;
	} 
	
	
	public function getMatrix1_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $timezone_id, $frequency){
		
		$provider_email = urldecode($provider_email);
		$user_email = urldecode($user_email);
		$start_time1 = urldecode($start_time1);
		$end_time1 = urldecode($end_time1);
		
		//$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
		//$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
		$get_service_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
		$get_staff1 = $this->getStaffWithServiceid($service1_id);
		$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
		$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
		$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');
		$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
		$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');
		$vendor_starttime_slot = $this->getTimeSlotWithTimezone($start_date, $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
		$vendor_endtime_slot = $this->getTimeSlotWithTimezone($start_date, $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
		$check_minimum_bookdate = $this->getMinimumBookDate($provider_id,$start_date);
		$check_minimum_booktime = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot);
		$check_branch_slot_available = $this->getStaffTimeSlots($get_staff1,$vendor_starttime_slot,$vendor_endtime_slot);							 
		$slot_available = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot,$vendor_endtime_slot,$get_provider_timezone_id);
		
		
//if(!$slot_available){
	
	//if($get_branch1){
				
		//if($get_service1){
		
			if($get_service_no_of_booking != 0){
	
				if($get_customer_timezone_vlaue == "(GMT+05:30)"){			
						
					if($check_minimum_bookdate == 1){			
		
						if($check_minimum_booktime == 1){							

							if($get_provider_timezone_id){
					
								if($check_branch_slot_available){

										$branch_aval_slots = $this->getProviderAvaliableTimeSlots($get_staff1,$vendor_starttime_slot,$service1_id);
										$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff1,  'booking_date' => $vendor_starttime_slot, 'booking_start_time' => $vendor_starttime_slot, 'booking_end_time' => $vendor_endtime_slot, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
										$get_confirmation_details = $this->putConfirmationEntry($input_array);
										$matrix1_Result=  array('status'=>array('invitations'=>$branch_aval_slots ));
									
									
								}else{
										$matrix1_Result = array('status' => '(Busy)');
								}
								
							}else{
								$matrix1_Result = array('status' => '(Busy)');
					
							}
							
						}else{
					
							$matrix1_Result = array('status' => '(Busy)');			
						}
						
					}else{
				
						$matrix1_Result = array('status' => '(Busy)');			
					}
					
				}else{
			
					$matrix1_Result = array('status' => '(Busy)');			
				}
				
			}else{
			
				$matrix1_Result = array('status' => '(Busy)');			
			}
		/* 				
		}else{
		
			$matrix1_Result = array('status' => '(Busy)');			
		}
	}else{
		
		
		$matrix1_Result = array('status' => '(Busy)');	}
	
}else{
	
	
	$matrix1_Result = array('status' => '(Busy)');			
} */
			
			return json_encode($matrix1_Result);
		
	}
	
	
	public function getMatrix2_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $timezone_id, $frequency){
		
		$provider_email = urldecode($provider_email);
		$user_email = urldecode($user_email);
		$start_time1 = urldecode($start_time1);
		$end_time1 = urldecode($end_time1);
		
		$start_date =  explode(',',$start_date);
		
		for($i=0; $i < count($start_date); $i++ )
		{
				
			$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
			if($get_branch1){
				
					$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
				
				if($get_service1){
					
					$get_service_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
					
				if($get_service_no_of_booking != 0){
					
					$get_staff1 = $this->getStaffWithServiceid($service1_id);
						
						$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
						
						if($get_customer_timezone_vlaue == "(GMT+05:30)"){
						
						$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
						$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');
						
						$vendor_starttime_slot = $this->getTimeSlotWithTimezone($start_date[$i], $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
						$vendor_endtime_slot = $this->getTimeSlotWithTimezone($start_date[$i], $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
		
						$check_minimum_bookdate = $this->getMinimumBookDate($provider_id,$start_date[$i]);
						
		if($check_minimum_bookdate == 1){
			
						$check_minimum_booktime = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot);
		
			if($check_minimum_booktime == 1){
						$check_branch_slot_available = $this->getStaffTimeSlots($get_staff1,$vendor_starttime_slot,$vendor_endtime_slot);
						
						$slot_available = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot,$vendor_endtime_slot,$get_provider_timezone_id);					

					if($get_provider_timezone_id){
					
						if($check_branch_slot_available){
					
							if($slot_available){
								$matrix2_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot ','content'=>null);
							}else{
								
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot);
								$matrix2_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking is Available.' , 'content'=>array('invitations'=>$branch_aval_slots));
						
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff1,  'booking_date' => $vendor_starttime_slot, 'booking_start_time' => $vendor_starttime_slot, 'booking_end_time' => $vendor_endtime_slot, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details = $this->putConfirmationEntry($input_array);
					
								$matrix2_Result[] =  array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.' , 'content'=>array('invitations'=>$get_confirmation_details));
								
							}
							}else{
								
								$matrix2_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.', 'content'=>null);
								}
						}else{
							$matrix2_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' time zone not available.','content'=>null);
				
						}
						
					}else{
						
							$matrix2_Result[] = array('status' => 'false','message' => 'The given service is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix2_Result[] = array('status' => 'false','message' => 'The given service is not available. The minimum booking Date exceed.','content'=>null);			
						}
					
					}else{
				
					$matrix2_Result[] = array('status' => 'false','message' => 'The International Users Not allowed.','content'=>null);			
				}
				
				}else{
				
					$matrix2_Result[] = array('status' => 'false','message' => 'The given service Participants FUll.','content'=>null);			
				}
						
				}else{
				
					$matrix2_Result[] = array('status' => 'false','message' => 'The given service is not available in the branch.','content'=>null);			
				}
			}else{
				
				
				$matrix2_Result[] = array('status' => 'false','message' => 'The given branch is not available.','content'=>null);			
			}
		}
			return json_encode($matrix2_Result);
		
	}
	
	public function getMatrix3_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $service2_id, $start_time2, $end_time2, $timezone_id,$frequency){
			
			$provider_email = urldecode($provider_email);
			$user_email = urldecode($user_email);
			$start_time1 = urldecode($start_time1);
			$end_time1 = urldecode($end_time1);
			$start_time2 = urldecode($start_time2);
			$end_time2 = urldecode($end_time2);
			
			$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
		
			if($get_branch1){
				
				$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
				$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
				$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');

				$vendor_starttime_slot1 = $this->getTimeSlotWithTimezone($start_date, $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
				$vendor_endtime_slot1 = $this->getTimeSlotWithTimezone($start_date, $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
				
				$vendor_starttime_slot2 = $this->getTimeSlotWithTimezone($start_date, $start_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
				$vendor_endtime_slot2 = $this->getTimeSlotWithTimezone($start_date, $end_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
				
				$get_staff1 = $this->getStaffWithServiceid($service1_id);
				$get_staff2 = $this->getStaffWithServiceid($service2_id);
				
				$check_branch_slot_available1 = $this->getStaffTimeSlots($get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1);	
				$check_branch_slot_available2 = $this->getStaffTimeSlots($get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2);				
						
				$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
				$get_service2 = $this->getServiceWithBranch($service2_id, $branch1_id);
				
				$get_service_no_of_booking1 = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
				$get_service_no_of_booking2 = DB::table('provider_biz_service')->where('service_id', $service2_id)->value('participants_allowed');
				
				$check_minimum_bookdate = $this->getMinimumBookDate($provider_id,$start_date);
				
				$check_minimum_booktime1 = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot1);
				$check_minimum_booktime2 = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot2);
				

				
			if($get_provider_timezone_id){
				
				if($get_customer_timezone_vlaue == "(GMT+05:30)"){
				
				if($get_service1 && $get_service2){
									
				if($get_service_no_of_booking1 != 0){
				
					if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime1 == 1){
						
					//For Service 1
					 $slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_branch_slot_available1){
					
							if($slot_available1){
				
								$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot1);
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array1 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff1, 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array1);
					
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
						}else{
						
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 Participants FUll.','content'=>null);
						}
						
						
					if($get_service_no_of_booking2 != 0){
						
						if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime2 == 1){
					//For Service 2	
					$slot_available2 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_branch_slot_available2){
					
							if($slot_available2){
				
								$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot2);
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array2 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff2, 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array2);
					
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 Participants FUll.','content'=>null);
						}
						
						
				}else if($get_service1 && $get_service2 == ""){
					
					//For Service 1 only booked	
					
					if($get_service_no_of_booking1 != 0){
						
						if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime1 == 1){
						
					 $slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_branch_slot_available1){
					
							if($slot_available1){
				
								$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot1);
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array1 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff1, 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array1);
					
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						
					}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 Participants FUll.','content'=>null);
						}
						
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 is not available in the branch.','content'=>null);

	
				}else if($get_service2 && $get_service1 == ""){
					
					//For Service 2 only booked	
					if($get_service_no_of_booking2 != 0){
						
						if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime2 == 1){
					
					$slot_available2 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_vendor_slot_available2){
					
							if($slot_available2){
				
								$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
						
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_endtime_slot2);
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array2 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff2, 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array2);
					
								$matrix3_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						}else{
							
							$matrix3_Result[] = array('status' => 'false','message' => 'The given service2 Participants FUll.','content'=>null);
						}
					
					$matrix3_Result[] = array('status' => 'false','message' => 'The given service1 is not available in the branch.','content'=>null);
				}
				
				else{
					$matrix3_Result[] = array('status' => 'false','message' => 'The given services are not available in the branch.','content'=>null);				
				}
			}
			else{
				$matrix3_Result[] = array('status' => 'false','message' => 'The International Users Not allowed.','content'=>null);	
				}
			
			}else{
				$matrix3_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' time zone not available.','content'=>null);
				}
				
			
			}else{
				
				
				$matrix3_Result[] = array('status' => 'false','message' => 'The given branch is not available.','content'=>null);			
			}
			
			return json_encode(@$matrix3_Result);
		
	}
	
	
	public function getMatrix4_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $service2_id, $start_time2, $end_time2, $timezone_id,$frequency){
			
			$provider_email = urldecode($provider_email);
			$user_email = urldecode($user_email);
			$start_time1 = urldecode($start_time1);
			$end_time1 = urldecode($end_time1);
			$start_time2 = urldecode($start_time2);
			$end_time2 = urldecode($end_time2);
			
			$start_date =  explode(',',$start_date);
		
		for($i=0; $i < count($start_date); $i++ )
		{
			
			$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
		
			if($get_branch1){
				
				$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
				$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
				$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');

				$vendor_starttime_slot1 = $this->getTimeSlotWithTimezone($start_date[$i], $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
				$vendor_endtime_slot1 = $this->getTimeSlotWithTimezone($start_date[$i], $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
				
				$vendor_starttime_slot2 = $this->getTimeSlotWithTimezone($start_date[$i], $start_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
				$vendor_endtime_slot2 = $this->getTimeSlotWithTimezone($start_date[$i], $end_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
				
				$get_staff1 = $this->getStaffWithServiceid($service1_id);
				$get_staff2 = $this->getStaffWithServiceid($service2_id);
				
				$check_branch_slot_available1 = $this->getStaffTimeSlots($get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1);	
				$check_branch_slot_available2 = $this->getStaffTimeSlots($get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2);				
						
				$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
				$get_service2 = $this->getServiceWithBranch($service2_id, $branch1_id);
				
				$get_service_no_of_booking1 = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
				$get_service_no_of_booking2 = DB::table('provider_biz_service')->where('service_id', $service2_id)->value('participants_allowed');
				
				$check_minimum_bookdate = $this->getMinimumBookDate($provider_id,$start_date[$i]);
				
				$check_minimum_booktime1 = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot1);
				$check_minimum_booktime2 = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot2);
				

				
			if($get_provider_timezone_id){
				
				if($get_customer_timezone_vlaue == "(GMT+05:30)"){
				
				if($get_service1 && $get_service2){
									
				if($get_service_no_of_booking1 != 0){
				
					if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime1 == 1){
						
					//For Service 1
					 $slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_branch_slot_available1){
					
							if($slot_available1){
				
								$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot1);
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array1 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff1, 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array1);
					
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
						}else{
						
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 Participants FUll.','content'=>null);
						}
						
						
					if($get_service_no_of_booking2 != 0){
						
						if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime2 == 1){
					//For Service 2	
					$slot_available2 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_branch_slot_available2){
					
							if($slot_available2){
				
								$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot2);
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array2 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff2, 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array2);
					
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 Participants FUll.','content'=>null);
						}
						
						
				}else if($get_service1 && $get_service2 == ""){
					
					//For Service 1 only booked	
					
					if($get_service_no_of_booking1 != 0){
						
						if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime1 == 1){
						
					 $slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_branch_slot_available1){
					
							if($slot_available1){
				
								$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_starttime_slot1);
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array1 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff1, 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array1);
					
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						
					}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 Participants FUll.','content'=>null);
						}
						
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 is not available in the branch.','content'=>null);

	
				}else if($get_service2 && $get_service1 == ""){
					
					//For Service 2 only booked	
					if($get_service_no_of_booking2 != 0){
						
						if($check_minimum_bookdate == 1){
						
						if($check_minimum_booktime2 == 1){
					
					$slot_available2 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_vendor_slot_available2){
					
							if($slot_available2){
				
								$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
						
								$branch_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$vendor_endtime_slot2);
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$branch_aval_slots));
								
								$input_array2 = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $get_staff2, 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array2);
					
								$matrix4_Result[] = array('status' => 'true','message' =>'The '.$provider_email.' and '.$user_email.' booking confirmed.','content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 is not available. The minimum booking Date exceed.','content'=>null);			
						}
						}else{
							
							$matrix4_Result[] = array('status' => 'false','message' => 'The given service2 Participants FUll.','content'=>null);
						}
					
					$matrix4_Result[] = array('status' => 'false','message' => 'The given service1 is not available in the branch.','content'=>null);
				}
				
				else{
					$matrix4_Result[] = array('status' => 'false','message' => 'The given services are not available in the branch.','content'=>null);				
				}
			}
			else{
				$matrix4_Result[] = array('status' => 'false','message' => 'The International Users Not allowed.','content'=>null);	
				}
			
			}else{
				$matrix4_Result[] = array('status' => 'false','message' => 'The '.$provider_email.' time zone not available.','content'=>null);
				}
				
			
			}else{
				
				
				$matrix4_Result[] = array('status' => 'false','message' => 'The given branch is not available.','content'=>null);			
			}
			
		}
			
			return json_encode(@$matrix4_Result);
		
	}
	
	public function getMatrix5_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $start_date, $start_time1, $end_time1, $branch2_id, $service2_id, $start_time2, $end_time2, $timezone_id, $type,$frequency){
			
			$provider_email = urldecode($provider_email);
			$user_email = urldecode($user_email);
			$start_time1 = urldecode($start_time1);
			$end_time1 = urldecode($end_time1);
			$start_time2 = urldecode($start_time2);
			$end_time2 = urldecode($end_time2);
			
			$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
			$get_branch2 = $this->getProviderWithBranch($provider_id,$branch2_id);

			$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
			$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
			$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');

			$vendor_starttime_slot1 = $this->getTimeSlotWithTimezone($start_date, $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
			$vendor_endtime_slot1 = $this->getTimeSlotWithTimezone($start_date, $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
			
			$vendor_starttime_slot2 = $this->getTimeSlotWithTimezone($start_date, $start_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
			$vendor_endtime_slot2 = $this->getTimeSlotWithTimezone($start_date, $end_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
			
			$check_vendor_slot_available1 = $this->getBranchTimeSlots($branch1_id,$vendor_starttime_slot1,$vendor_endtime_slot1);	
			$check_vendor_slot_available2 = $this->getBranchTimeSlots($branch2_id,$vendor_starttime_slot2,$vendor_endtime_slot2);				
					
			$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
			$get_service2 = $this->getServiceWithBranch($service2_id, $branch2_id);
			
			$get_staff1 = $this->getStaffWithServiceid($service1_id);
			$get_staff2 = $this->getStaffWithServiceid($service2_id);
				
		if($get_provider_timezone_id){
			
			if($get_customer_timezone_vlaue == "(GMT+05:30)"){
			
			//For Branch 1
			if($get_branch1 && $get_service1){
				$get_service1_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
				
				if($get_service_no_of_booking != 0){
					
					$check_minimum_bookdate = $this->getMinimumBookDate($provider_id,$start_date);
						
					if($check_minimum_bookdate == 1){
						
									$check_minimum_booktime = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot1);
					
						if($check_minimum_booktime == 1){
					
					$slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$get_staff1,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_vendor_slot_available1){
					
							if($slot_available1){
				
								$matrix5_Result = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							
							}else{
								
								$provider_aval_slots = $this->getBranchAvaliableTimeSlots($branch1_id,$start_date);
								$matrix5_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$provider_aval_slots));
								
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => '', 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array);
					
								$matrix5_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix5_Result = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
						}else{
						
							$matrix5_Result= array('status' => 'false','message' => 'The given service is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix5_Result= array('status' => 'false','message' => 'The given service is not available. The minimum booking Date exceed.','content'=>null);			
						}
						
						}else{
				
					$matrix5_Result = array('status' => 'false','message' => 'The given service Participants FUll.','content'=>null);			
				}
						
			}else if($get_branch1 && $get_service1 == ""){
				
				$matrix5_Result = array('status' => 'false','message' => 'The given service1 is not available in the branch1.','content'=>null);
				
			}else if($get_branch1 == ""){
				
				$matrix5_Result = array('status' => 'false','message' => 'The given branch1 is not available.','content'=>null);
			}else{
				
				$matrix5_Result = array('status' => 'false','message' => 'The given branch1 and Service1 is not available.','content'=>null);
			}
			
			
			//For Branch 2
			if($get_branch2 && $get_service2){
				
				$get_service2_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service2_id)->value('participants_allowed');
				
				if($get_service_no_of_booking != 0){
					
					$check_minimum_bookdate = $this->getMinimumBookDate($provider_id,$start_date);
						
					if($check_minimum_bookdate == 1){
						
									$check_minimum_booktime = $this->getMinimumBookTime($provider_id,$vendor_starttime_slot2);
					
						if($check_minimum_booktime == 1){
				
				$slot_available2 = $this->checkBookedSlots($provider_id,$branch2_id,$get_staff2,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_vendor_slot_available2){
					
							if($slot_available2){
				
								$matrix5_Result = array('status' => 'false','message' => 'The '.$provider_email.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
								
								$provider_aval_slots = $this->getBranchAvaliableTimeSlots($branch2_id,$start_date);
								$matrix5_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$provider_aval_slots));
								
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch2_id, 'staff_id' => '', 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array);
					
								$matrix5_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix5_Result = array('status' => 'false','message' => 'The '.$provider_email.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						}else{
						
							$matrix5_Result= array('status' => 'false','message' => 'The given service is not available. The minimum booking time exceed.','content'=>null);			
							}
						
						}else{
					
						$matrix5_Result= array('status' => 'false','message' => 'The given service is not available. The minimum booking Date exceed.','content'=>null);			
						}
						
						}else{
				
					$matrix5_Result = array('status' => 'false','message' => 'The given service Participants FUll.','content'=>null);			
				}
						
			}else if($get_branch2 && $get_service2 == ""){
				
				$matrix5_Result = array('status' => 'false','message' => 'The given service2 is not available in the branch2.','content'=>null);
				
			}else if($get_branch1 == ""){
				
				$matrix5_Result = array('status' => 'false','message' => 'The given branch2 is not available.','content'=>null);
			}else{
				
				$matrix5_Result = array('status' => 'false','message' => 'The given branch2 and Service2 is not available.','content'=>null);
			}
			
			}else{
				
					$matrix5_Result= array('status' => 'false','message' => 'The International Users Not allowed.','content'=>null);			
				}

		}else{
			
			$matrix5_Result = array('status' => 'false','message' => 'The '.$provider_email.' user time zone not available.','content'=>null);	
		}
			
			return json_encode(@$matrix5_Result);
		
	}
	
	
	public function getMatrix7_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $staff1_id, $start_date, $start_time1, $end_time1, $branch2_id, $service2_id, $staff2_id, $start_time2, $end_time2, $timezone_id, $type){
			
			$provider_email = urldecode($provider_email);
			$user_email = urldecode($user_email);
			$start_time1 = urldecode($start_time1);
			$end_time1 = urldecode($end_time1);
			$start_time2 = urldecode($start_time2);
			$end_time2 = urldecode($end_time2);
			
			$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
			$get_branch2 = $this->getProviderWithBranch($provider_id,$branch2_id);

			$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
			$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
			$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');

			$vendor_starttime_slot1 = $this->getTimeSlotWithTimezone($start_date, $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
			$vendor_endtime_slot1 = $this->getTimeSlotWithTimezone($start_date, $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
			
			$vendor_starttime_slot2 = $this->getTimeSlotWithTimezone($start_date, $start_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
			$vendor_endtime_slot2 = $this->getTimeSlotWithTimezone($start_date, $end_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
			
			$check_vendor_slot_available1 = $this->getStaffTimeSlots($staff1_id,$vendor_starttime_slot1,$vendor_endtime_slot1);	
			$check_vendor_slot_available2 = $this->getStaffTimeSlots($staff2_id,$vendor_starttime_slot2,$vendor_endtime_slot2);				
					
			$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
			$get_service2 = $this->getServiceWithBranch($service2_id, $branch2_id);
			
			$get_staff1 = $this->getStaffWithService($service1_id, $staff1_id);
			$get_staff2 = $this->getStaffWithService($service2_id, $staff2_id);
				
		if($get_provider_timezone_id){
			
			//For Staff 1
			if($get_branch1 && $get_service1 && $get_staff1){
				
				$slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$staff1_id,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_vendor_slot_available1){
					
							if($slot_available1){
				
								$matrix7_Result = array('status' => 'false','message' => 'The '.$get_staff1.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
						
								$provider_aval_slots = $this->getProviderAvaliableTimeSlots($staff1_id,$vendor_starttime_slot1);
								$matrix7_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$provider_aval_slots));
								
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $staff1_id, 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array);
					
								$matrix7_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix7_Result = array('status' => 'false','message' => 'The '.$get_staff1.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
			}else if($get_branch1 && $get_service1 == ""){
				
				$matrix7_Result = array('status' => 'false','message' => 'The given service1 is not available in the branch1.','content'=>null);
				
			}else if($get_service1 && $get_staff1 == ""){
				
				$matrix7_Result = array('status' => 'false','message' => 'The '.$get_staff1.' is not available in the service1.','content'=>null);
				
			}else if($get_branch1 == ""){
				
				$matrix7_Result = array('status' => 'false','message' => 'The given branch1 is not available.','content'=>null);
			}else{
				$matrix7_Result = array('status' => 'false','message' => 'The given service1 and '.$get_staff1.' is not available in the given branch1.','content'=>null);
			}
			
			
			//For Staff 2
			if($get_branch2 && $get_service2 && $get_staff2){
				
				$slot_available2 = $this->checkBookedSlots($provider_id,$branch2_id,$staff2_id,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_vendor_slot_available2){
					
							if($slot_available2){
				
								$matrix7_Result = array('status' => 'false','message' => 'The '.$get_staff2.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
						
								$provider_aval_slots = $this->getProviderAvaliableTimeSlots($staff2_id,$vendor_starttime_slot2);
								$matrix7_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$provider_aval_slots));
								
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch2_id, 'staff_id' => $staff2_id, 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array);
					
								$matrix7_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix7_Result = array('status' => 'false','message' => 'The '.$get_staff2.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
			}else if($get_branch2 && $get_service2 == ""){
				
				$matrix7_Result = array('status' => 'false','message' => 'The given service2 is not available in the branch2.','content'=>null);
				
			}else if($get_service2 && $get_staff2 == ""){
				
				$matrix7_Result = array('status' => 'false','message' => 'The '.$get_staff2.' is not available in the service2.','content'=>null);
				
			}else if($get_branch1 == ""){
				
				$matrix7_Result = array('status' => 'false','message' => 'The given branch2 is not available.','content'=>null);
			}else{
				$matrix7_Result = array('status' => 'false','message' => 'The given service2 and '.$get_staff2.' is not available in the given branch2.','content'=>null);
			}

		}else{
			
			$matrix7_Result = array('status' => 'false','message' => 'The '.$provider_email.' time zone not available.','content'=>null);	
		}
			
			return json_encode(@$matrix7_Result);
		
	}
	
	
	public function getMatrix9_Result($provider_email,$user_email,$provider_id, $user_id, $branch1_id, $service1_id, $staff1_id, $start_date, $start_time1, $end_time1, $branch2_id, $service2_id, $staff2_id, $start_time2, $end_time2, $timezone_id, $type){
			
			$provider_email = urldecode($provider_email);
			$user_email = urldecode($user_email);
			$start_time1 = urldecode($start_time1);
			$end_time1 = urldecode($end_time1);
			$start_time2 = urldecode($start_time2);
			$end_time2 = urldecode($end_time2);
			
			$get_branch1 = $this->getProviderWithBranch($provider_id,$branch1_id);
			$get_branch2 = $this->getProviderWithBranch($provider_id,$branch2_id);

			$get_customer_timezone_vlaue = DB::table('timezone')->where('timezone_id', $timezone_id)->value('gmt');
			$get_provider_timezone = $this->getGmtWithProviderid($provider_id);
			$get_provider_timezone_id = DB::table('timezone')->where('gmt', $get_provider_timezone)->value('timezone_id');

			$vendor_starttime_slot1 = $this->getTimeSlotWithTimezone($start_date, $start_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
			$vendor_endtime_slot1 = $this->getTimeSlotWithTimezone($start_date, $end_time1, $get_customer_timezone_vlaue, $get_provider_timezone);
			
			$vendor_starttime_slot2 = $this->getTimeSlotWithTimezone($start_date, $start_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
			$vendor_endtime_slot2 = $this->getTimeSlotWithTimezone($start_date, $end_time2, $get_customer_timezone_vlaue, $get_provider_timezone);
			
			$check_vendor_slot_available1 = $this->getStaffTimeSlots($staff1_id,$vendor_starttime_slot1,$vendor_endtime_slot1);	
			$check_vendor_slot_available2 = $this->getStaffTimeSlots($staff2_id,$vendor_starttime_slot2,$vendor_endtime_slot2);				
					
			$get_service1 = $this->getServiceWithBranch($service1_id, $branch1_id);
			$get_service2 = $this->getServiceWithBranch($service2_id, $branch2_id);
			
			$get_staff1 = $this->getStaffWithService($service1_id, $staff1_id);
			$get_staff2 = $this->getStaffWithService($service2_id, $staff2_id);
				
		if($get_provider_timezone_id){
			
			//For Staff 1
			if($get_branch1 && $get_service1 && $get_staff1){
				
				$slot_available1 = $this->checkBookedSlots($provider_id,$branch1_id,$staff1_id,$vendor_starttime_slot1,$vendor_endtime_slot1,$get_provider_timezone_id);
					
						if($check_vendor_slot_available1){
					
							if($slot_available1){
				
								$matrix9_Result = array('status' => 'false','message' => 'The '.$get_staff1.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
						
								$provider_aval_slots = $this->getProviderAvaliableTimeSlots($staff1_id,$vendor_starttime_slot1);
								$matrix9_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$provider_aval_slots));
								
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch1_id, 'staff_id' => $staff1_id, 'booking_date' => $vendor_starttime_slot1, 'booking_start_time' => $vendor_starttime_slot1, 'booking_end_time' => $vendor_endtime_slot1, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details1 = $this->putConfirmationEntry($input_array);
					
								$matrix9_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$get_confirmation_details1));
								
							}
						}else{
							
							$matrix9_Result = array('status' => 'false','message' => 'The '.$get_staff1.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
			}else if($get_branch1 && $get_service1 == ""){
				
				$matrix9_Result = array('status' => 'false','message' => 'The given service1 is not available in the branch1.','content'=>null);
				
			}else if($get_service1 && $get_staff1 == ""){
				
				$matrix9_Result = array('status' => 'false','message' => 'The '.$get_staff1.' is not available in the service1.','content'=>null);
				
			}else if($get_branch1 == ""){
				
				$matrix9_Result = array('status' => 'false','message' => 'The given branch1 is not available.','content'=>null);
			}else{
				$matrix9_Result = array('status' => 'false','message' => 'The given service1 and '.$get_staff1.' is not available in the given branch1.','content'=>null);
			}
			
			
			//For Staff 2
			if($get_branch2 && $get_service2 && $get_staff2){
				
				$slot_available2 = $this->checkBookedSlots($provider_id,$branch2_id,$staff2_id,$vendor_starttime_slot2,$vendor_endtime_slot2,$get_provider_timezone_id);
					
						if($check_vendor_slot_available2){
					
							if($slot_available2){
				
								$matrix9_Result = array('status' => 'false','message' => 'The '.$get_staff2.' and '.$user_email.' are already booked the given time slot.','content'=>null);
							}else{
						
								$provider_aval_slots = $this->getProviderAvaliableTimeSlots($staff2_id,$vendor_starttime_slot2);
								$matrix9_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$provider_aval_slots));
								
								$input_array = array('customer_id' => $user_id, 'provider_id' => $provider_id, 'branch_id' => $branch2_id, 'staff_id' => $staff2_id, 'booking_date' => $vendor_starttime_slot2, 'booking_start_time' => $vendor_starttime_slot2, 'booking_end_time' => $vendor_endtime_slot2, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id);
								$get_confirmation_details2 = $this->putConfirmationEntry($input_array);
					
								$matrix9_Result = array('status' => 'true','message' =>null,'content'=>array('invitations'=>$get_confirmation_details2));
								
							}
						}else{
							
							$matrix9_Result = array('status' => 'false','message' => 'The '.$get_staff2.' is not available for your time slot.Please check another time slot.','content'=>null);
						}
						
			}else if($get_branch2 && $get_service2 == ""){
				
				$matrix9_Result = array('status' => 'false','message' => 'The given service2 is not available in the branch2.','content'=>null);
				
			}else if($get_service2 && $get_staff2 == ""){
				
				$matrix9_Result = array('status' => 'false','message' => 'The '.$get_staff2.' is not available in the service2.','content'=>null);
				
			}else if($get_branch1 == ""){
				
				$matrix9_Result = array('status' => 'false','message' => 'The given branch2 is not available.','content'=>null);
			}else{
				$matrix9_Result = array('status' => 'false','message' => 'The given service2 and '.$get_staff2.' is not available in the given branch2.','content'=>null);
			}

		}else{
			
			$matrix9_Result = array('status' => 'false','message' => 'The ".$provider_email." time zone not available.','content'=>null);	
		}
			
			return json_encode(@$matrix9_Result);
		
	}
	
	
}
