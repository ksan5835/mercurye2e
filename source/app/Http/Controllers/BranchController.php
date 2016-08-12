<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
use DateInterval;
use App\Models\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class BranchController extends Controller{
  
  
   /**
     * @SWG\Get(
     *     path="/v1/branch",
     *     tags={"branch"},
     *     operationId="index",
     *     summary="get all branch list",
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
  
        $Branch  = Branch::all();
		$Branch_result = array('status' => 'true','message' =>null,'content'=>$Branch);
		return json_encode($Branch_result);
 
    }
  
    public function getBranch($id){
  
        $branchExists = DB::table('provider_biz_branch')->where('branch_id', $id)->count();	
		
		if(!empty($branchExists)){
			$branchDatas = DB::table('provider_biz_branch')
                     ->select(DB::raw('*'))
                     ->where('branch_id', '=', $id)
                     ->get();
			$branchDatas_result = array('status' => 'true','message' =>null,'content'=>$branchDatas);
			return json_encode($branchDatas_result);
		}
		$branchDatas_result = array('status' => 'false','message' =>'The given id is not available. Need to register as new branch.','content'=>null);
		return json_encode($branchDatas_result);
    }
	
	public function getBranchEmail($branch_email){

			$userExists = Branch::where('branch_email', urldecode($branch_email))->count();

			if($userExists) {
				$userExists_result = array('status' => 'true','message' =>'Email ID is available.','content'=>null);
				return json_encode($userExists_result);
			}else{
				$userExists_result = array('status' => 'false','message' =>'No user available for this ID.Please register as new branch.','content'=>null);
				return json_encode($userExists_result);
			}
    }
	
	public function createBranch(Request $request){
  
        $Custom = Branch::create($request->all());
		$Custom_result = array('status' => 'true','message' =>null,'content'=>$Custom);
		return json_encode($Custom_result);
 
    }
	
	public function deleteBranch($branch_id){
        $branch_work = DB::table('biz_branch_workinghours')
                     ->select(DB::raw('branch_id'))
                     ->where('branch_id', '=', $branch_id)
                     ->get();
				if($branch_work){
					$Branch_delete_result = array('status' => 'false','message' =>'Branch working hours entry is available, so record will not be deleted.','content'=>$branch_work);
					return json_encode($Branch_delete_result);
				}else{
					DB::table('provider_biz_branch')->where('branch_id', '=', $branch_id)->delete();
 					$Branch_delete_result = array('status' => 'true','message' =>'deleted','content'=>null);
					return json_encode($Branch_delete_result);
				}
		

    }
  
    public function updateBranch(Request $request,$id){
        $Branch  = Branch::find($id);
        $Branch->first_name = $request->input('first_name');
        $Branch->last_name = $request->input('last_name');
        $Branch->email = $request->input('email');
		$Branch->mobile = $request->input('mobile');
        $Branch->save();
		
		$Branch_result = array('status' => 'true','message' =>null,'content'=>$Branch);
		return json_encode($Branch_result);
      }
	
	
	public function getBranchServiceList($branch_id){

			$branchExists = Branch::where('branch_id', $branch_id)->count();

			if($branchExists) {
				
				$servicelist = DB::table('provider_biz_service')
                     ->select(DB::raw('*'))
                     ->where('biz_id', '=', $branch_id)
                     ->get();
				if($servicelist){
					$servicelist_result = array('status' => 'true','message' =>null,'content'=>$servicelist);
					return json_encode($servicelist_result);
				}else{
					$servicelist_result = array('status' => 'false','message' =>'No services available for this branch ID.','content'=>null);
					return json_encode($servicelist_result);
				}
				
			}else{
				$branchExists_result = array('status' => 'false','message' =>'No branch available for this ID.Please register as new branch.','content'=>null);
				return json_encode($branchExists_result);
			}
    }
	
	public function getServiceBranchList($service_id){
		
				$servicelist = DB::table('biz_service_branch')
                     ->select(DB::raw('*'))
                     ->where('service_id', '=', $service_id)
                     ->get();
				if($servicelist){
					$servicelist_result = array('status' => 'true','message' =>null,'content'=>$servicelist);
					return json_encode($servicelist_result);
				}else{
					$servicelist_result = array('status' => 'false','message' =>'The given service is not available in any branch.','content'=>null);
					return json_encode($servicelist_result);
				}
    }
	
	public function getBranchService($branch_id, $service_id){

			$branchExists = Branch::where('branch_id', $branch_id)->count();

			if($branchExists) {
				
				$service = DB::table('provider_biz_service')
                     ->select(DB::raw('*'))
                     ->where('service_id', '=', $service_id)
					 ->where('biz_id', '=', $branch_id)
                     ->get();
				if($service){
					$service_result = array('status' => 'true','message' =>null,'content'=>$service);
					return json_encode($service_result);
				}else{
					$service_result = array('status' => 'false','message' =>'No service available for this branch ID and service ID.','content'=>null);
					return json_encode($service_result);
				}
				
			}else{
				$branchExists_result = array('status' => 'false','message' =>'No branch available for this ID.Please register as new branch.','content'=>null);
				return json_encode($branchExists_result);
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

			$check_vendor_slot_available = DB::select( DB::raw("SELECT start_time,end_time FROM biz_staff_workinghours WHERE staff_id = '$user1_id' and date(start_time) = date('$start_date') ") );

					
			if(!empty($check_vendor_slot_available)){
							
				$startTime = new DateTime($check_vendor_slot_available[0]->start_time);
				$endTime = new DateTime($check_vendor_slot_available[0]->end_time );
				$i=1;
				while($startTime <= $endTime) {
					$time_slot['slot'.$i] = $startTime->format('H:i:s') . ' ';
					$startTime->add(new DateInterval('PT60M'));
					$i++;
				}
					$time_slot_result = array('status' => 'true','message' =>null,'content'=>$time_slot);
					return json_encode($time_slot_result);
				
			}else{
				$check_vendor_slot_available_result = array('status' => 'false','message' =>$start_date.' Slot closed for this date.','content'=>null);
				return json_encode($check_vendor_slot_available_result);
			}
		
		}else{
				$check_vendor_slot_available_result = array('status' => 'false','message' =>$email.' is not available.Please register as new provider.','content'=>null);
				return json_encode($check_vendor_slot_available_result);			
		}							 
		
									 
		
	
    }
	
}
