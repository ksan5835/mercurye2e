<?php

namespace App\Http\Controllers;


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
		
		return $this->createErrorResponse('The given id is not available. Need to register as new user.', 404);
    }
	
	public function getBranchEmail($branch_email){

			$userExists = Branch::where('branch_email', $branch_email)->count();

			if($userExists) {
				return $this->createSuccessResponse("Email ID is available.", 200);
			}else{
				return response()->json("No user available for this ID.Please register as new user");
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
	
}
