<?php

namespace App\Http\Controllers;


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
	
	public function getCustomerEmailBooking($email, $bookdate){
		
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
