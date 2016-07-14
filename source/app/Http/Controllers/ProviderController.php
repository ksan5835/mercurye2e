<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
use App\Models\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class ProviderController extends Controller{
  
  
   /**
     * @SWG\Get(
     *     path="/v1/branch",
     *     tags={"branch"},
     *     operationId="index",
     *     summary="get all branch list",
     *     description="",
     *     consumes={"application/json", "application/xml"},
     *     produces={"application/xml", "application/json"},

     * )
     */
  
    public function index(){
  
        $Provider  = Provider::all();
  
        return response()->json($Provider);
  
    }
  
    public function getProvider($id){
  
        $ProviderExists = DB::table('provider')
                     ->select(DB::raw('*'))
                     ->where('user_id', '=', $id)
                     ->get();
		
		if(!empty($ProviderExists)){
			return $this->createSuccessResponse($ProviderExists, 200);
		}
		
		return $this->createErrorResponse('The given id is not available. Need to register as new Provider.', 404);
    }
	
	public function getProviderEmail($Provider_email){

			$ProviderExists = DB::table('provider')
                     ->select(DB::raw('*'))
                     ->where('email', '=', $Provider_email)
                     ->get();

			if($ProviderExists) {
				return $this->createSuccessResponse($ProviderExists, 200);
			}else{
				return response()->json("No user available for this ID.Please register as new Provider");
			}
    }
	
	
	
}
