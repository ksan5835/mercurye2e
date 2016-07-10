<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class CustomerController extends Controller{
  
  
    public function index(){
  
        $Customer  = Customer::all();
  
        return response()->json($Customer);
  
    }
  
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
	public function getCustomerEmailBooking($email, $bookdate, $timeinterval, $timezone_id){
		
		$email_explode = explode(',',$email);
		$bookdate_explode =  explode(',',$bookdate);
		$timeinterval_explode =  explode(',',$timeinterval);
				
		$start_datetime = date_create($bookdate_explode[0]);
		$start_date = date_format($start_datetime,"Y-m-d");		
		
		$start_time = $timeinterval_explode[0];		
		$end_time = $timeinterval_explode[1];

		$user1_id = DB::table('provider')->where('email', $email_explode[0])->value('user_id');	
		$user2_id = DB::table('customer')->where('email', $email_explode[1])->value('user_id');
		
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
		$vendorStartTime = new DateTime($start_date.' '.$start_time, $vendorTimezone);
		$offset = $userTimezone->getOffset($vendorStartTime);
		$vendor_starttime_slot = date('Y-m-d H:i', $vendorStartTime->format('U') + $offset);
		
		$vendorEndTime = new DateTime($start_date.' '.$end_time, $vendorTimezone);
		$offset = $userTimezone->getOffset($vendorEndTime);
		$vendor_endtime_slot = date('Y-m-d H:i', $vendorEndTime->format('U') + $offset);
		
		$check_vendor_slot_available = DB::table('biz_staff_workinghours')
									 ->where('staff_id', '=', $user1_id)
									 ->where('start_time', '<=', DB::getPdo()->quote($vendor_starttime_slot))
									 ->where('end_time', '>=', DB::getPdo()->quote($vendor_endtime_slot))
									 ->value('workinghours_id');  
									 
									 
		//$check_vendor_slot_available = 1;

		$vendor_book_date = date_create($vendor_starttime_slot);
		$vendor_aval_date = date_format($vendor_book_date,"Y-m-d");

		$vendor_start_time = date_create($vendor_starttime_slot);
		$vendor_aval_start_time = date_format($vendor_start_time,"H:i");

		$vendor_end_time = date_create($vendor_endtime_slot);
		$vendor_aval__end_time = date_format($vendor_end_time,"H:i");	

//die;		
							
		$slot_available = DB::table('customer_booking_confirmation')
									 ->where('customer_id', '=', $user1_id)
									 ->where('vendor_id', '=', $user2_id)
									 ->where('booking_date', '=', $vendor_aval_date)
									 ->where('booking_start_time', '=', $vendor_aval_start_time)
									 ->where('booking_end_time', '=', $vendor_aval__end_time)
									 ->where('booking_timezone_id', '=', $get_provider_timezone_id)
									 ->value('id');
									 
			
									 
		if($user1_id && $user2_id) {
			
			if($check_vendor_slot_available){
				
				if($slot_available){
			
					return $this->createErrorResponse($email_explode[1]." and ".$email_explode[0]." already booked with the time slot ".$start_date." ".$start_time." - ".$end_time , 404);
				}else{
					
					DB::table('customer_booking_confirmation')->insert(
							['customer_id' => $user1_id, 'vendor_id' => $user2_id, 'booking_date' => $vendor_aval_date, 'booking_start_time' => $vendor_aval_start_time, 'booking_end_time' => $vendor_aval__end_time, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id]
								);
								
					return $this->createSuccessResponse("We have confirmed the booking.", 200);
				}
			}else{
				return $this->createErrorResponse($email_explode[1]." is not available for your time slot.Please check another time slot.", 404);
			}
		}else{
				if($user1_id){
					return $this->createErrorResponse($email_explode[1]." is not available.Please register as new user", 404);
				}else if($user2_id){
					return $this->createErrorResponse($email_explode[0]." is not available.Please register as new user", 404);
				}else{
					return $this->createErrorResponse("Both the user is not available.Please register as new user",404);
				}				
		}	
	
    }
	
	public function getConfirmedBooking($id){

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
	
}
