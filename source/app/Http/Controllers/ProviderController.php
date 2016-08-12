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
		$Provider_result = array('status' => 'true','message' =>null,'content'=>$Provider);
		return json_encode($Provider_result);
  
    }
  
    public function getProvider($id){
  
        $ProviderExists = DB::table('provider')
                     ->select(DB::raw('*'))
                     ->where('user_id', '=', $id)
                     ->get();
		
		if(!empty($ProviderExists)){
			$ProviderExists_result = array('status' => 'true','message' =>null,'content'=>$ProviderExists);
			return json_encode($ProviderExists_result);
		}
		$ProviderExists_result = array('status' => 'false','message' =>'The given id is not available. Need to register as new Provider.','content'=>null);
		return json_encode($ProviderExists_result);
    }
	
	public function getProviderEmail($Provider_email){

			$ProviderExists = DB::table('provider')
                     ->select(DB::raw('*'))
                     ->where('email', '=', $Provider_email)
                     ->get();

			if($ProviderExists) {
				$ProviderExists_result = array('status' => 'true','message' =>'Email ID is available.','content'=>$ProviderExists);
				return json_encode($ProviderExists_result);
			}else{
				$ProviderExists_result = array('status' => 'false','message' =>'No user available for this ID.Please register as new Provider.','content'=>null);
				return json_encode($ProviderExists_result);
			}
    }
	
	
	
}
