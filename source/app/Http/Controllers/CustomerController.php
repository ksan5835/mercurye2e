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
	 
	 	public function getStaffWithServiceid($service_id, $meetingtype_id){
		
			/* $service_staff_id = DB::table('provider_biz_staff_service')
						 ->select('staff_id')
						 ->where('service_id', '=', $service_id)
						 ->value('staff_id'); */
						 
			$service_staff_id = DB::table('provider_biz_staff_service')
								->leftJoin('provider_biz_staff_service_meetingtype', 'provider_biz_staff_service.staff_id', '=', 'provider_biz_staff_service_meetingtype.staff_id')
								->select('provider_biz_staff_service.staff_id')
								->where('provider_biz_staff_service.service_id', '=', $service_id)
								->where('provider_biz_staff_service_meetingtype.meetingtype_id', '=', $meetingtype_id)
								->where('provider_biz_staff_service_meetingtype.service_id', '=', $service_id)
								//->value('provider_biz_staff_service.staff_id');
								->get();
								
								//print_r($service_staff_id[0]->staff_id);die;
								
				if(count($service_staff_id) > 1){
					
					for($i=0; $i< count($service_staff_id); $i++){
						
						$service_staff_ids[] = $service_staff_id[$i]->staff_id;
					}
					
				}else{
					$service_staff_id = isset($service_staff_id[0]->staff_id) ? $service_staff_id[0]->staff_id : '' ;
					//$service_staff_id = ($service_staff_id[0]->staff_id == 0 ) ? 0 :$service_staff_id[0]->staff_id;
					
					$service_staff_ids = array ($service_staff_id);
				}

			return $service_staff_ids;

		} 
		
		public function get_breaks_time($branch_id){
		
			/* $BR_slot_interval = DB::table('setting_provider')
						 ->select('BR_slot_interval')
						 ->where('provider_id', '=', $branch_id)
						 ->value('BR_slot_interval'); */
						 
			$BR_slot_interval = DB::table('setting_provider')
								->leftJoin('provider_biz_branch', 'setting_provider.provider_id', '=', 'provider_biz_branch.biz_id')
								->select('setting_provider.BR_slot_interval')
								->where('provider_biz_branch.branch_id', '=', $branch_id)
								->value('setting_provider.BR_slot_interval');
								//->get();
						 
			return $BR_slot_interval;
		} 
		
		public function getBookingTimePeriod($branch_id){
		
	 
			$BookingTimePeriod = DB::table('setting_provider')
								->leftJoin('provider_biz_branch', 'setting_provider.provider_id', '=', 'provider_biz_branch.biz_id')
								->select('setting_provider.BR_Booking_Allowed_From', 'setting_provider.BR_Booking_Allowed_Till')
								->where('provider_biz_branch.branch_id', '=', $branch_id)
								->get();

			
			$date = date('Y-m-d');
			$prev_date = date('d-m-Y', strtotime($date .' -'.$BookingTimePeriod[0]->BR_Booking_Allowed_From.' day'));
			$next_date = date('d-m-Y', strtotime($date .' +'.$BookingTimePeriod[0]->BR_Booking_Allowed_Till.' day')); 
			
			$BookingTimePeriod = strtotime($prev_date).'-'.strtotime($next_date); 
			
			return $BookingTimePeriod;
		} 
		
		public function get_branch_timeing($branch_id,$start_date){
		
			$branch_time = DB::select( DB::raw("SELECT weekendadd FROM provider_biz_branch WHERE branch_id = '".$branch_id."'") );
			//echo $branch_time[0]->weekendadd;die;
					$branch_time_data = unserialize($branch_time[0]->weekendadd);
					/* echo '<pre>';
					print_r(unserialize($branch_time[0]->weekendadd));
					echo '</pre>'; */
					for($s = 0; $s < count($branch_time_data[0]['weekends']); $s++){
						
						$branch_slot_details = $branch_time_data[0]['weekends'][$s];
						
						$branch_slot_day = $branch_slot_details['weekstr'];
						$branch_slot_active = $branch_slot_details['active'];
						if($branch_slot_day == $start_date  && $branch_slot_active == 1){
							$start_Time = $branch_time_data[0]['timing']['start_time'];
							$end_Time = $branch_time_data[0]['timing']['end_time'];
						}
					}
				$branch_time = isset($start_Time) ? $start_Time.'-'.$end_Time : '' ;		 
			return $branch_time;
		} 
		
		
		/* public function get_staff_timeing($branch_id,$service_id,$staff_id,$start_date){
		
		$check_vendor_slot_available = DB::select( DB::raw("SELECT weekendadd FROM provider_biz_staff_working_hours WHERE staff_id = ".$staff_id[0]." and branch_id = '".$branch_id."' and service_id = '".$service_id."' ") );
					
					if($check_vendor_slot_available){
						
						$staff_time_data = unserialize($check_vendor_slot_available[0]->weekendadd);
					
					  echo '<pre>';
					print_r(unserialize($check_vendor_slot_available[0]->weekendadd));
					echo '</pre>'; 
					for($s = 0; $s < count($staff_time_data[0]['weekends']); $s++){
						
						$branch_slot_details = $staff_time_data[0]['weekends'][$s];
						
						$branch_slot_day = $branch_slot_details['weekstr'];
						$branch_slot_active = $branch_slot_details['active'];
						if($branch_slot_day == $start_date  && $branch_slot_active == 1){
							$start_Time = $staff_time_data[0]['timing']['start_time'];
							$end_Time = $staff_time_data[0]['timing']['end_time'];
						}
					}
					$staff_time = isset($start_Time) ? $start_Time.'-'.$end_Time : '' ;
					
					}else{
						$staff_time = 0;
					}
		 
			return $staff_time;
		}  */
		
		 public function getStaffTimeSlots($branch_id,$service_id,$staff_id){
		
			$check_vendor_slot_available = DB::select( DB::raw("SELECT working_hours_id FROM provider_biz_staff_working_hours WHERE staff_id = '$staff_id' and branch_id = '$branch_id' and service_id = '$service_id'") );
			if($check_vendor_slot_available){
				$check_vendor_slot_available_id = $check_vendor_slot_available[0]->working_hours_id;
			}else{
				$check_vendor_slot_available_id = '';
				} 

			return $check_vendor_slot_available_id;
			
		}
		
		public function checkStaffBookedSlots($branch_id,$staff_id,$book_start_date,$book_start_time ){
		
			$book_start_date = date_create($book_start_date);
			$book_start_date = date_format($book_start_date,"Y-m-d");
					
			for($i=0; $i< count($staff_id); $i++){
				
				$slot_available = DB::select( "SELECT booking_id FROM appointment WHERE staff_id = '$staff_id[$i]' and appointment_date = '$book_start_date' and appointment_start_time = '$book_start_time' and provider_id = '$branch_id'") ;
				
				if(@$slot_available[0]->booking_id)
				$slot_availables[] = $slot_available[0]->booking_id;
		
			}
				
			
			if(@$slot_availables &&  count($slot_availables) == count($staff_id) ){
				
				$slot_available_id = $slot_availables;
				
			}else{
				$slot_available_id = '';
			}
			
			return $slot_available_id;
		}
		
		public function checkStaffBlockedHours($staff_id,$bookdate,$book_start_time,$book_end_time){
		
			$vendor_book_date = date_create($bookdate);
			$bookdate = date_format($vendor_book_date,"Y-m-d");

			for($i=0; $i< count($staff_id); $i++){
				
			$slot_available = DB::select( DB::raw("SELECT staff_blocked_hours_id FROM provider_biz_staff_blocked_hours WHERE staff_id = '$staff_id[$i]' and date(start_date) <= date('$bookdate') and start_time <= '$book_start_time' and date(end_date) >= date('$bookdate') and end_time >= '$book_end_time'") );
				
				if(@$slot_available[0]->staff_blocked_hours_id)
				$slot_availables[] = $slot_available[0]->staff_blocked_hours_id;
			}					 
			if(@$slot_availables &&  count($slot_availables) == count($staff_id)){
				$slot_available_id = $slot_available[0]->staff_blocked_hours_id;
			}else{
				$slot_available_id = '';
			}
			
			return $slot_available_id;
		}
	

		public function getProviderAvaliableTimeSlots($branch_id,$service_id,$staff_id,$bookdate,$staff_flag){
			
			$start_datetime = date_create($bookdate);
			$start_date = date_format($start_datetime,"l");	
			
			$get_service_slot_available = DB::select( DB::raw("SELECT padding_minutes,precision_time FROM provider_biz_service WHERE service_id = '$service_id'" ));
			
			if($get_service_slot_available[0]->precision_time){
				$precision_time_slot = explode(",",$get_service_slot_available[0]->precision_time);
				
				for($i=0; $i < count($precision_time_slot); $i++){
						
					$check_book_time_slot = $this->checkStaffBookedSlots($branch_id,$staff_id,$bookdate,$precision_time_slot[$i]);
					
					$check_blocked_hours = $this->checkStaffBlockedHours($staff_id,$bookdate,$precision_time_slot[$i],@$precision_time_slot[$i+1]);
					if($i < count(@$precision_time_slot)-1)
					if($check_blocked_hours){
						//$time_slot = str_replace('.',':',$precision_time_slot[$i]).'- '.$precision_time_slot[$i+1];
						$time_slot_status = "H";
						$final_slot [] = array ("slot_start_time" => $precision_time_slot[$i], "slot_end_time" => $precision_time_slot[$i+1], "status" => $time_slot_status);
					}elseif($check_book_time_slot){
						//$time_slot = str_replace('.',':',$precision_time_slot[$i]).'- '.$precision_time_slot[$i+1];
						$time_slot_status  = "B";
						$final_slot [] = array ("slot_start_time" => $precision_time_slot[$i], "slot_end_time" => $precision_time_slot[$i+1], "status" => $time_slot_status);
					}else{
						//$time_slot = str_replace('.',':',$precision_time_slot[$i]).'- '.$precision_time_slot[$i+1];
						$time_slot_status = "A";
						$final_slot [] = array ("slot_start_time" => $precision_time_slot[$i], "slot_end_time" => $precision_time_slot[$i+1], "status" => $time_slot_status);
					}
				}
				
			}else{
				
			
			//$check_vendor_slot_available = DB::select( DB::raw("SELECT weekendadd FROM provider_biz_staff_working_hours WHERE staff_id = ".$staff_id." and branch_id = '".$branch_id."' and service_id = '".$service_id."' ") );
/* echo '<pre>';

print_r(unserialize($check_vendor_slot_available[0]->weekendadd));echo '</pre>';

print_r(count($slot_data[0]['weekends']));
 *//* 
		$slot_data = unserialize($check_vendor_slot_available[0]->weekendadd);
		for($s = 0; $s < count($slot_data[0]['weekends']); $s++){
			
			$slot_details = $slot_data[0]['weekends'][$s];
			
			$slot_day = $slot_details['weekstr'];
			$slot_active = $slot_details['active'];
			if($slot_day == $start_date  && $slot_active == 1){
				$status = 1;
			}
		} */
	/* if($staff_flag == 1){
		
		$staff_avil_times = $this->get_staff_timeing($branch_id,$service_id,$staff_id,$start_date);
		
		$staff_timings = isset($staff_avil_times)? explode("-",$staff_avil_times) : "";
		
		$startTime = new DateTime($staff_timings[0]);
		$endTime = new DateTime(@$staff_timings[1]);
		
	}else{ */
		
		$branch_avil_times = $this->get_branch_timeing($branch_id,$start_date);
		
		$branch_timings = isset($branch_avil_times)? explode("-",$branch_avil_times) : "";
		
		$startTime = new DateTime($branch_timings[0]);
		$endTime = new DateTime(@$branch_timings[1]);
//	}
		
		$breaks_time = $this->get_breaks_time($branch_id);
		
		
		//die;
				//if(!empty($check_vendor_slot_available) && $status == 1){	
					
					
					/* if($get_service_slot_available[0]->duration){
					$interval = ($get_service_slot_available[0]->duration + $breaks_time);
					}else{
					$interval = (30 + $breaks_time);	
					} */
					//$interval = $breaks_time + @$get_service_slot_available[0]->padding_minutes;
					
					$interval = $breaks_time;
					
					$i=1;
					
					while($startTime <= $endTime) {
						$staff_time_slot[] = $startTime->format('H:i:s') . '';
						$startTime->add(new DateInterval('PT'.$interval.'M'));
						$i++;
					}
					
					for($i=0; $i < count(@$staff_time_slot); $i++){
					
					//$slot_start_date = $bookdate.' '.$staff_time_slot[$i];
					
					//$slot_end_date = $bookdate.' '.$staff_time_slot[$i+1];
								
					$check_book_time_slot = $this->checkStaffBookedSlots($branch_id,$staff_id,$bookdate,$staff_time_slot[$i]);
					
					$check_blocked_hours = $this->checkStaffBlockedHours($staff_id,$bookdate,$staff_time_slot[$i],@$staff_time_slot[$i+1]);
					
					if($i < count(@$staff_time_slot)-1)
					if($check_blocked_hours){
						//$time_slot = str_replace('.',':',$staff_time_slot[$i]).'- '.$staff_time_slot[$i+1].'';
						$time_slot_status = "H";
						$final_slot [] = array ("slot_start_time" => $staff_time_slot[$i], "slot_end_time" => $staff_time_slot[$i+1], "status" => $time_slot_status);
					}elseif($check_book_time_slot){
						//$time_slot  = $staff_time_slot[$i].'- '.$staff_time_slot[$i+1].' ';
						$time_slot_status  = "B";
						$final_slot [] = array ("slot_start_time" => $staff_time_slot[$i], "slot_end_time" => $staff_time_slot[$i+1], "status" => $time_slot_status);
					}else{
						//$time_slot = $staff_time_slot[$i].'- '.$staff_time_slot[$i+1].'';
						$time_slot_status = "A";
						$final_slot [] = array ("slot_start_time" => $staff_time_slot[$i], "slot_end_time" => $staff_time_slot[$i+1], "status" => $time_slot_status);
					}
				}
					
				//}
			}
			
			//print_r($final_slot);die;
			return @$final_slot;
		}	

	public function getMatrix1_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date, $timezone_id, $meetingtype_id){
		

		$get_service_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
		
		if($staff1_id == 0){
			
		$get_staff1 = $this->getStaffWithServiceid($service1_id,$meetingtype_id);
		$staff_flag = 0;
		
		}else{
			$get_staff1 = array($staff1_id);
			$staff_flag = 1;
		}
		
		$get_booking_time_period = explode("-",$this->getBookingTimePeriod($branch1_id));
		
		$booking_time_from = $get_booking_time_period[0];
		
		$booking_time_till = $get_booking_time_period[1];
		
		$booking_date = strtotime($start_date); 
		//print_r($get_staff1);
		
		//die;
		//$check_branch_slot_available = $this->getStaffTimeSlots($branch1_id,$service1_id,$get_staff1);
			
		if($get_service_no_of_booking != 0 && $get_service_no_of_booking >= $participants && $get_staff1[0] != "" && $booking_time_from <= $booking_date ){
			
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date,$staff_flag);
			//print_r($branch_aval_slots);die;
				if($branch_aval_slots == ""){
					$matrix1_Result =  array('status'=> 'false', 'content('.$start_date.')'=>'(Busy)' );

				}else{
										$start_datetime = date_create($start_date);
										$start_date = date_format($start_datetime,"d-m-y");
										
								
										$staff_ids = implode(",",$get_staff1 );
										$matrix1_Result=  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date, 'service_id' => $service1_id, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'time_slots' => $branch_aval_slots ));
									
									
				}		
									
									
								}else{
										$matrix1_Result =  array('status'=> 'false', 'content('.$start_date.')'=>'(Busy)' );
								}

								
			
			return $matrix1_Result;
		
	}
	
	
	public function getMatrix2_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date, $timezone_id, $meetingtype_id){
		
		$start_date =  explode(',',$start_date);
		
		$get_service_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
		
		if($staff1_id == 0){
			
		$get_staff1 = $this->getStaffWithServiceid($service1_id,$meetingtype_id);
		$staff_flag = 0;
		
		}else{
			$get_staff1 = array($staff1_id);
			$staff_flag = 1;
		}
		
		$get_booking_time_period = explode("-",$this->getBookingTimePeriod($branch1_id));
		
		$booking_time_from = $get_booking_time_period[0];
		
		$booking_time_till = $get_booking_time_period[1];
		

		for($i=0; $i < count($start_date); $i++ )
		{
			
			$booking_date = strtotime($start_date[$i]); 
				
			if($get_service_no_of_booking != 0 && $get_service_no_of_booking >= $participants && $get_staff1[0] != "" && $booking_time_from <= $booking_date ){
				
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date[$i],$staff_flag);
 

				if($branch_aval_slots == ""){
					$matrix2_Result[] =  array('status'=> 'false', 'content('.$start_date[$i].')'=>'(Busy)' );

				}else{
					$start_datetime = date_create($start_date[$i]);
					$start_date = date_format($start_datetime,"d-m-Y");

					$staff_ids = implode(",",$get_staff1 );
					$matrix2_Result[] =  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date, 'service_id' => $service1_id, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'time_slots' => $branch_aval_slots ));					
				}	
				
			}else{
				$matrix2_Result[] =  array('status'=> 'false', 'content('.$start_date[$i].')'=>'(Busy)' );
			}						
			
		}
		
			return $matrix2_Result;
		
	}
}
	
?>