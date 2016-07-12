<?php

namespace App\Http\Controllers;
use DB;

use App\Models\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class BranchController extends Controller{
  
  
    public function index(){
  
        $Branch  = Branch::all();
  
        return response()->json($Branch);
  
    }
  
    public function getBranch($id){
  
        $Branch  = Branch::find($id);
		
		if(!empty($Branch)){
			return $this->createSuccessResponse($Branch, 200);
		}
		
		return $this->createErrorResponse('The given id is not available. Need to register as new branch.', 404);
    }
	
	public function getBranchEmail($branch_email){

			$userExists = Branch::where('branch_email', $branch_email)->count();

			if($userExists) {
				return $this->createSuccessResponse("Email ID is available.", 200);
			}else{
				return response()->json("No user available for this ID.Please register as new branch");
			}
    }
	
	public function createBranch(Request $request){
  
        $Custom = Branch::create($request->all());
  
        return response()->json($Custom);
  
    }
	
	public function deleteBranch($id){
        $Custom  = Branch::find($id);
        $Custom->delete();
 
        return response()->json('deleted');
    }
  
    public function updateBranch(Request $request,$id){
        $Branch  = Branch::find($id);
        $Branch->first_name = $request->input('first_name');
        $Branch->last_name = $request->input('last_name');
        $Branch->email = $request->input('email');
		$Branch->mobile = $request->input('mobile');
        $Branch->save();
  
        return response()->json($Branch);
    }
	
	
	public function getBranchServiceList($branch_id){

			$branchExists = Branch::where('id', $branch_id)->count();

			if($branchExists) {
				
				$servicelist = DB::table('provider_biz_service')
                     ->select(DB::raw('*'))
                     ->where('biz_id', '=', $branch_id)
                     ->get();
				if($servicelist){
					return response()->json($servicelist);
				}else{
					return response()->json("No services available for this branch ID.");
				}
				
			}else{
				return response()->json("No branch available for this ID.Please register as new branch");
			}
    }
	
	public function getBranchService($branch_id, $service_id){

			$branchExists = Branch::where('id', $branch_id)->count();

			if($branchExists) {
				
				$service = DB::table('provider_biz_service')
                     ->select(DB::raw('*'))
                     ->where('service_id', '=', $service_id)
                     ->get();
				if($service){
					return response()->json($service);
				}else{
					return response()->json("No service available for this branch ID and service ID.");
				}
				
			}else{
				return response()->json("No branch available for this ID.Please register as new branch");
			}
    }
	
	//For one to one meating check
	public function getCustomerEmailBooking($email, $bookdate){
		
		DB::connection()->enableQueryLog();
		$email_explode = explode(',',$email);
		$bookdate_explode =  explode(',',$bookdate);
		$timeinterval_explode =  explode(',',$timeinterval);
				
		$start_datetime = date_create($bookdate_explode[0]);
		$start_date = date_format($start_datetime,"Y-m-d");		
		
		$start_time = $timeinterval_explode[0];		
		$end_time = $timeinterval_explode[1];

		$user1_id = DB::table('provider')->where('email', $email_explode[0])->value('user_id');	
		$user2_id = DB::table('customer')->where('email', $email_explode[1])->value('user_id');
		if(!$user2_id){$user2_id = 0;}
		
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
		$vendor_starttime_slot = date('Y-m-d H:i:s', $vendorStartTime->format('U') + $offset);
		
		$vendorEndTime = new DateTime($start_date.' '.$end_time, $vendorTimezone);
		$offset = $userTimezone->getOffset($vendorEndTime);
		$vendor_endtime_slot = date('Y-m-d H:i:s', $vendorEndTime->format('U') + $offset);
		
		$check_vendor_slot_available = DB::table('biz_staff_workinghours')
									 ->where('staff_id', '=', $user1_id)
									 ->whereDate('start_time', '<=', date('Y-m-d H:i:s', $vendorStartTime->format('U') + $offset))
									 ->whereDate('end_time', '>=', date('Y-m-d H:i:s', $vendorEndTime->format('U') + $offset))
									 ->value('workinghours_id');  
									 
		$vendor_book_date = date_create($vendor_starttime_slot);
		$vendor_aval_date = date_format($vendor_book_date,"Y-m-d");

		$vendor_start_time = date_create($vendor_starttime_slot);
		$vendor_aval_start_time = date_format($vendor_start_time,"H:i");

		$vendor_end_time = date_create($vendor_endtime_slot);
		$vendor_aval__end_time = date_format($vendor_end_time,"H:i");	

//die;		
							
		$slot_available = DB::table('customer_booking_confirmation')
									 ->where('customer_id', '=', $user2_id)
									 ->where('vendor_id', '=', $user1_id)
									 ->where('booking_date', '=', $vendor_aval_date)
									 ->where('booking_start_time', '=', $vendor_aval_start_time)
									 ->where('booking_end_time', '=', $vendor_aval__end_time)
									 ->where('booking_timezone_id', '=', $get_provider_timezone_id)
									 ->value('id');
									 
			
									 
		if($user1_id) {
			
			if($get_provider_timezone_id){
			
				if($check_vendor_slot_available){
			
					if($slot_available){
		
						return $this->createErrorResponse($email_explode[1]." and ".$email_explode[0]." already booked with the time slot ".$start_date." ".$start_time." - ".$end_time , 404);
					}else{
				
						DB::table('customer_booking_confirmation')->insert(
						['customer_id' => $user2_id, 'vendor_id' => $user1_id, 'booking_date' => $vendor_aval_date, 'booking_start_time' => $vendor_aval_start_time, 'booking_end_time' => $vendor_aval__end_time, 'booking_title' => "Meeting", 'booking_desc' => "Meeting for project requirement discussion.", 'booking_timezone_id' => $get_provider_timezone_id]);
							
						return $this->createSuccessResponse("We have confirmed the booking.", 200);
					}
				}else{
					return $this->createErrorResponse($email_explode[1]." is not available for your time slot.Please check another time slot.", 404);
					}
			}else{
				return $this->createErrorResponse($email_explode[1]." user time zone not available.", 404);
				}
		}else{
				
					return $this->createErrorResponse($email_explode[1]." is not available.Please register as new user", 404);			
		}	
	
    }
	
}
