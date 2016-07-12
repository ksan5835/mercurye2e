<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
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
	
	//Staff Slot
	public function getStaffSlotEmail($email, $bookdate){
		
		//Getting the provider information
		$user1_id = DB::table('provider')->where('email', $email)->value('user_id');	
		
		if(!empty($user1_id)){
			//Getting the available date
			$start_datetime = date_create($bookdate);
			$start_date = date_format($start_datetime,"Y-m-d");	
echo env('APP_TIMEZONE') . "\n";
			$check_vendor_slot_available = DB::table('biz_staff_workinghours')
										 ->select(DB::raw('*'))
										 ->where('staff_id', '=', $user1_id)
										 ->whereDate('start_time', '=', date($start_date))
										 ->get();
			if(!empty($check_vendor_slot_available)){
				print_r($check_vendor_slot_available);	
				$start = date_create ( $check_vendor_slot_available[0]->start_time);
				$end = date_create ( $check_vendor_slot_available[0]->end_time );
				$diff = date_diff($end,$start);
				echo $diff->h;
				
				$ex_stime = explode(' ',$check_vendor_slot_available[0]->start_time);
				$ex_etime = explode(' ',$check_vendor_slot_available[0]->end_time);
				
				$datetime = DateTime::createFromFormat('g:i:s', $ex_stime[1]);
				$datetime->modify('+60 minutes');
				echo $datetime->format('g:i:s');
				
				

				

			}else{
				return $this->createErrorResponse($start_date." Slot closed for this date", 404);	
			}
		
		}else{			
			return $this->createErrorResponse($email." is not available.Please register as new provider", 404);	
		}							 
		
									 
		
	
    }
	
}
