<?php

namespace App\Http\Controllers;
use DB;

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
	public function getCustomerEmailBooking($email, $bookdate, $timeinterval, $timezone){
		
		$email_explode = explode(',',$email);
		$bookdate_explode =  explode(',',$bookdate);
		$timeinterval_explode =  explode(',',$timeinterval);
		$timezone_explode =  explode(',',$timezone);
		
		$start_datetime = date_create($bookdate_explode[0]);
		$start_date = date_format($start_datetime,"Y-m-d");		
		//$start_time = date_format($start_datetime,"H:i:s");
		$start_time = $timeinterval_explode[0];
		
		//$end_datetime = date_create($bookdate_explode[1]);
		//$end_date = date_format($end_datetime,"Y-m-d");		
		//$end_time = date_format($end_datetime,"H:i:s");
		$end_time = $timeinterval_explode[1];
		
		$time_zone = $timezone_explode[0];
		$time_zone_id = DB::table('timezone')->where('timezone_desc', $time_zone)->value('timezone_id');

		$user1_id = DB::table('customers')->where('email', $email_explode[0])->value('id');	
		$user2_id = DB::table('customers')->where('email', $email_explode[1])->value('id');
									
		$slot_available = DB::table('customer_booking_confirmation')
									 ->where('customer_id', '=', $user1_id)
									 ->where('vendor_id', '=', $user2_id)
									 ->where('booking_date', '=', $start_date)
									 ->where('booking_start_time', '=', $start_time)
									 ->where('booking_end_time', '=', $end_time)
									 ->where('booking_timezone_id', '=', $time_zone_id)
									 ->value('id');
									 
		if($user1_id && $user2_id) {
				if($slot_available){
			
					return $this->createErrorResponse($email_explode[1]." and ".$email_explode[0]." already booked with the time slot ".$start_date." ".$start_time." - ".$end_time , 404);
				}else{
					
					DB::table('customer_booking_confirmation')->insert(
							['customer_id' => $user1_id, 'vendor_id' => $user2_id, 'booking_date' => $start_date, 'booking_start_time' => $start_time, 'booking_end_time' => $end_time, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $time_zone_id]
								);
								
					return $this->createSuccessResponse("We have confirmed the booking.", 200);
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
	
	public function getConfirmedBooking(){

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
