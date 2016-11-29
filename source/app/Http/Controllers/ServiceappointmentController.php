<?php

namespace App\Http\Controllers;
use DB;
use DateTimeZone;
use DateTime;
use DateInterval;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

  
class ServiceappointmentController extends Controller{
  
  
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
			$prev_date = date('d-m-Y', strtotime($date .' +'.@$BookingTimePeriod[0]->BR_Booking_Allowed_From.' day'));
			$next_date = date('d-m-Y', strtotime($date .' +'.@$BookingTimePeriod[0]->BR_Booking_Allowed_Till.' day')); 
			
			$BookingTimePeriod = strtotime($prev_date).'-'.strtotime($next_date); 
			
			return $BookingTimePeriod;
		} 
		
		public function get_branch_timeing($branch_id,$service_id,$start_date){
		
			$branch_time = DB::select( DB::raw("SELECT weekendadd FROM provider_biz_branch WHERE branch_id = '".$branch_id."'") );
			//echo $branch_time[0]->weekendadd;die;
			
			/* $branch_time = DB::table('provider_biz_branch')
								->leftJoin('provider_biz_service_branch', 'provider_biz_branch.branch_id', '=', 'provider_biz_service_branch.branch_id')
								->select('provider_biz_branch.weekendadd')
								->where('provider_biz_branch.branch_id', '=', $branch_id)
								->where('provider_biz_service_branch.branch_id', '=', $branch_id)
								->where('provider_biz_service_branch.service_id', '=', $service_id)
								//->value('provider_biz_staff_service.staff_id');
								->get(); */
								
					
			
					$branch_time_data = unserialize($branch_time[0]->weekendadd);
					/*  echo '<pre>';
					print_r(unserialize($branch_time[0]->weekendadd));
					echo count($branch_time_data);
					echo '</pre>'; die; */
					for($s = 1; $s < count($branch_time_data); $s++){
						
						$branch_slot_details = $branch_time_data[$s];
						
						$branch_slot_day = $branch_slot_details['weekstr'];
						$branch_slot_active = $branch_slot_details['active']; 
						//echo $start_Time = $branch_slot_details['working_hours']['start_time'];
						
						//print_r($branch_slot_details['working_hours'][0]['start_time']);die;
						if($branch_slot_day == $start_date  && $branch_slot_active == 1){
							$start_Time = $branch_slot_details['working_hours'][0]['start_time'];
							$end_Time = $branch_slot_details['working_hours'][0]['end_time'];
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
		
		public function checkStaffBookedSlots($branch_id,$service_id,$staff_id,$book_start_date,$book_start_time,$book_end_time ){
		
			$book_start_date = date_create($book_start_date);
			$book_start_date = date_format($book_start_date,"Y-m-d");
					
			for($i=0; $i< count($staff_id); $i++){
				
				$slot_available = DB::select( "SELECT booking_id FROM appointment WHERE staff_id = '$staff_id[$i]' and branch_id = '$branch_id' and service_id = '$service_id' and appointment_date = '$book_start_date' and appointment_start_time <= '$book_start_time' and appointment_end_time  >= '$book_end_time' ") ;
				//$slot_available = DB::select( "SELECT booking_id FROM appointment WHERE staff_id = '$staff_id[$i]' and appointment_date = '$book_start_date' and appointment_start_time BETWEEN '$book_start_time' and '$book_end_time' OR appointment_end_time BETWEEN '$book_start_time' and '$book_end_time' and provider_id = '$branch_id'") ;

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
				
			//$slot_available = DB::select( DB::raw("SELECT staff_blocked_hours_id FROM provider_biz_staff_blocked_hours WHERE staff_id = '$staff_id[$i]' and date(start_date) = date('$bookdate') and start_time BETWEEN '$book_start_time' AND '$book_end_time' or date(end_date) = date('$bookdate') and end_time BETWEEN '$book_start_time' AND '$book_end_time'") );
			
			$slot_available = DB::select( DB::raw("SELECT staff_blocked_hours_id FROM provider_biz_staff_blocked_hours WHERE staff_id = '$staff_id[$i]' and date(start_date) = date('$bookdate') AND start_time <= '$book_start_time' AND end_time >= '$book_end_time'") );

				
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
		
		public function checkStaffBlockedSlots($branch_id,$service_id,$staff_id,$start_date,$start_time,$end_time){
		
		//print_r($start_time);die;		

			for($i=0; $i< count($staff_id); $i++){
				
				$check_vendor_slot_available = DB::select( DB::raw("SELECT weekendadd FROM provider_biz_staff_working_hours WHERE staff_id = ".$staff_id[$i]." and branch_id = '".$branch_id."' and service_id = '".$service_id."' ") );
				

				$staff_time_data = unserialize(@$check_vendor_slot_available[0]->weekendadd);
					
				  /*  echo '<pre>';
				print_r(unserialize($check_vendor_slot_available[0]->weekendadd));
				echo '</pre>';   */
				
				for($s = 0; $s < count($staff_time_data); $s++){
					
					$staff_slot_details = $staff_time_data[$s];
					
					$staff_slot_day = $staff_slot_details['weekstr'];
					$staff_slot_active = $staff_slot_details['active'];
					if($staff_slot_day == $start_date  && $staff_slot_active == 1){
						$staff_avil_start_time [] = $staff_slot_details['working_hours'][0]['start_time'];
						$staff_avil_end_time [] = $staff_slot_details['working_hours'][0]['end_time'];
						
					}
				}

			}
			
			if(isset($staff_avil_start_time) && isset($staff_avil_start_time)){
				$start_min_value = min(@$staff_avil_start_time);
				$end_max_value = max(@$staff_avil_end_time);
			}
			/* else{
				$start_min_value = $start_time;
				$end_max_value = $end_time;
			} */
			//print_r($staff_avil_start_time);die;
			
			if($start_time >= @$start_min_value && $end_time <= @$end_max_value){
				$staff_available_id = 1;
			}else{
				$staff_available_id = 0;
			}

			return $staff_available_id;
		}
	

		public function getProviderAvaliableTimeSlots($branch_id,$service_id,$staff_id,$bookdate,$staff_flag){
			
			$start_datetime = date_create($bookdate);
			$start_date = date_format($start_datetime,"l");	
			
			$get_service_slot_available = DB::select( DB::raw("SELECT duration,precision_time FROM provider_biz_service WHERE service_id = '$service_id'" ));
			
			if($get_service_slot_available[0]->precision_time){
				$precision_time_slot = explode(",",$get_service_slot_available[0]->precision_time);
				
				for($i=0; $i < count($precision_time_slot); $i++){
						
					$check_book_time_slot = $this->checkStaffBookedSlots($branch_id,$service_id,$staff_id,$bookdate,$precision_time_slot[$i],@$precision_time_slot[$i+1]);
					
					$check_blocked_hours = $this->checkStaffBlockedHours($staff_id,$bookdate,$precision_time_slot[$i],@$precision_time_slot[$i+1]);
					
					$check_staff_blocked_hours = $this->checkStaffBlockedSlots($branch_id,$service_id,$staff_id,$start_date,$precision_time_slot[$i],@$precision_time_slot[$i+1]);

					
					if($i < count(@$precision_time_slot)-1)
					if($check_blocked_hours  || $check_staff_blocked_hours == 0){
						//$time_slot = str_replace('.',':',$precision_time_slot[$i]).'- '.$precision_time_slot[$i+1];
						$time_slot_status = "H";
						$final_slot [] = array ("slot_start_time" => $precision_time_slot[$i], "slot_end_time" => $precision_time_slot[$i+1], "status" => $time_slot_status);
					}/*elseif($check_staff_blocked_hours == 0){
						//$time_slot  = $staff_time_slot[$i].'- '.$staff_time_slot[$i+1].' ';
						$time_slot_status  = "NA";
						$final_slot [] = array ("slot_start_time" => $staff_time_slot[$i], "slot_end_time" => $staff_time_slot[$i+1], "status" => $time_slot_status);
					}*/elseif($check_book_time_slot){
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
		
		$branch_avil_times = $this->get_branch_timeing($branch_id,$service_id,$start_date);
		
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
								
					$check_book_time_slot = $this->checkStaffBookedSlots($branch_id,$service_id,$staff_id,$bookdate,$staff_time_slot[$i],@$staff_time_slot[$i+1]);
					
					$check_blocked_hours = $this->checkStaffBlockedHours($staff_id,$bookdate,$staff_time_slot[$i],@$staff_time_slot[$i+1]);
					
					$check_staff_blocked_hours = $this->checkStaffBlockedSlots($branch_id,$service_id,$staff_id,$start_date,$staff_time_slot[$i],@$staff_time_slot[$i+1]);
					
					if($i < count(@$staff_time_slot)-1)
					if($check_blocked_hours || $check_staff_blocked_hours == 0){
						//$time_slot = str_replace('.',':',$staff_time_slot[$i]).'- '.$staff_time_slot[$i+1].'';
						$time_slot_status = "H";
						$final_slot [] = array ("slot_start_time" => $staff_time_slot[$i], "slot_end_time" => $staff_time_slot[$i+1], "status" => $time_slot_status);
					}/*elseif($check_staff_blocked_hours == 0){
						//$time_slot  = $staff_time_slot[$i].'- '.$staff_time_slot[$i+1].' ';
						$time_slot_status  = "NA";
						$final_slot [] = array ("slot_start_time" => $staff_time_slot[$i], "slot_end_time" => $staff_time_slot[$i+1], "status" => $time_slot_status);
					}*/elseif($check_book_time_slot){
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

	/* public function getMatrix1_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date, $timezone_id, $meetingtype_id){
		

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
		
		$get_service_duration = DB::select( DB::raw("SELECT duration FROM provider_biz_service WHERE service_id = '$service1_id'" ));

		$breaks_time = $this->get_breaks_time($branch1_id);
		
		$block_argument_count = @$get_service_duration[0]->duration / @$breaks_time;
		
		if(empty($block_argument_count)){
			$block_argument_count = 0;
		}else{
			$block_argument_count = $block_argument_count;
		}
		
		$today_date = strtotime(date('Y-m-d'));
		
		if($booking_time_from >= $booking_date && $booking_date >= $today_date ){
			
			if($get_service_no_of_booking != 0 ){
				
				if($get_service_no_of_booking >= $participants){

					if($get_staff1[0] != ""){ 
				
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date,$staff_flag);
				
				$get_service_padding = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('padding_time_when');
								
				if($get_service_padding == 1 ){
					$padding_before_value = 1;
					$padding_after_value = 0;
				}else if($get_service_padding == 2 ){
					$padding_before_value = 0;
					$padding_after_value = 1;
				}else if($get_service_padding == 3 ){
					$padding_before_value = 1;
					$padding_after_value = 1;
				}else {
					$padding_before_value = 0;
					$padding_after_value = 0;
				}
			//print_r($branch_aval_slots);die;
				if($branch_aval_slots == ""){
					
					return $this->createErrorResponse("The given service or staff based slots not available.", 405);

					//$matrix1_Result =  array('status'=> 'false', 'message' =>'The given service or staff based slots not available.', 'content('.$start_date.')'=>'(Busy)' );

				}else{
										$start_datetime = date_create($start_date);
										$start_date = date_format($start_datetime,"d-m-Y");
										
								
										$staff_ids = implode(",",$get_staff1 );
										$matrix1_Result=  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date, 'service_id' => $service1_id, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'time_slots' => $branch_aval_slots ));
									
									
						}			
								}else{
										return $this->createErrorResponse("The Staff is not available for this service.", 406);

										//$matrix1_Result =  array('status'=> 'false','message' =>'The Staff is not available for thi service. ', 'content('.$start_date.')'=>'(Busy)' );
								}				
								}else{
										return $this->createErrorResponse("The given participants count is grater than the allowed participants.", 407);
																			
										//$matrix1_Result =  array('status'=> 'false','message' =>'The given participants count is grater than the allowed participants. ','content('.$start_date.')'=>'(Busy)' );
								}
								}else{
										return $this->createErrorResponse("The Service no of participants is Full.", 408);

										//$matrix1_Result =  array('status'=> 'false','message' =>'The Service no of participants is Full. ','content('.$start_date.')'=>'(Busy)' );
								}
								}else{
										return $this->createErrorResponse("The given booking date is past or blocked future date.", 409);

										//$matrix1_Result =  array('status'=> 'false','message' =>'The given booking date is past or blocked future date. ', 'content('.$start_date.')'=>'(Busy)' );
								}
								

								
			
			return $matrix1_Result;
		
	} */
	
	public function getMatrix1_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date, $timezone_id, $meetingtype_id){
	/*public function getMatrix1_Result(Request $request){
		
		$participants=$request->input('participants');
		$branch1_id=$request->input('branch1_id');
		$service1_id=$request->input('service1_id');
		$staff1_id=$request->input('staff1_id');
		$start_date=$request->input('start_date');
		$timezone_id=$request->input('timezone_id');
		$meetingtype_id=$request->input('meetingtype_id');*/
		
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
		
		$get_service_duration = DB::select( DB::raw("SELECT duration FROM provider_biz_service WHERE service_id = '$service1_id'" ));

		$breaks_time = $this->get_breaks_time($branch1_id);
		
		$block_argument_count = @$get_service_duration[0]->duration / @$breaks_time;
		
		if(empty($block_argument_count)){
			$block_argument_count = 0;
		}else{
			$block_argument_count = $block_argument_count;
		}
		
		$today_date = strtotime(date('Y-m-d'));
		
		if($booking_time_from >= $booking_date && $booking_date >= $today_date ){
			
			if($get_service_no_of_booking != 0 ){
				
				if($get_service_no_of_booking >= $participants){

					if($get_staff1[0] != ""){ 
				
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date,$staff_flag);
				
				$get_service_padding = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('padding_time_when');
								
				if($get_service_padding == 1 ){
					$padding_before_value = 1;
					$padding_after_value = 0;
				}else if($get_service_padding == 2 ){
					$padding_before_value = 0;
					$padding_after_value = 1;
				}else if($get_service_padding == 3 ){
					$padding_before_value = 1;
					$padding_after_value = 1;
				}else {
					$padding_before_value = 0;
					$padding_after_value = 0;
				}
			//print_r($branch_aval_slots);die;
				if($branch_aval_slots == ""){
					
					return $this->createErrorResponse("The given service or staff based slots not available.", 405);

					//$matrix1_Result =  array('status'=> 'false', 'message' =>'The given service or staff based slots not available.', 'content('.$start_date.')'=>'(Busy)' );

				}else{
										$start_datetime = date_create($start_date);
										$start_date = date_format($start_datetime,"d-m-Y");
										
								
										$staff_ids = implode(",",$get_staff1 );
										$matrix1_Result=  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date, 'service_id' => $service1_id, 'service_duration' => $get_service_duration[0]->duration, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'time_slots' => $branch_aval_slots ));
									
									
						}			
								}else{
										return $this->createErrorResponse("The Staff is not available for this service.", 406);

										//$matrix1_Result =  array('status'=> 'false','message' =>'The Staff is not available for thi service. ', 'content('.$start_date.')'=>'(Busy)' );
								}				
								}else{
										return $this->createErrorResponse("The given participants count is grater than the allowed participants.", 407);
																			
										//$matrix1_Result =  array('status'=> 'false','message' =>'The given participants count is grater than the allowed participants. ','content('.$start_date.')'=>'(Busy)' );
								}
								}else{
										return $this->createErrorResponse("The Service no of participants is Full.", 408);

										//$matrix1_Result =  array('status'=> 'false','message' =>'The Service no of participants is Full. ','content('.$start_date.')'=>'(Busy)' );
								}
								}else{
										return $this->createErrorResponse("The given booking date is past or blocked future date.", 409);

										//$matrix1_Result =  array('status'=> 'false','message' =>'The given booking date is past or blocked future date. ', 'content('.$start_date.')'=>'(Busy)' );
								}
								

								
			
			return $matrix1_Result;
		
	}
	
	
	public function getMatrix2_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date,$end_date, $repetition_type, $timezone_id, $meetingtype_id){

		$event_date = $start_date;
		$event_end_date = $end_date;
		$event_repetition_type = $repetition_type;

		$date_calculation = "";
		switch ($event_repetition_type) {
			case "1":
			$date_calculation = " +1 day";
			break;
		case "2":
			$date_calculation = " +1 week";
			break;
		case "3":
			$date_calculation = " +1 month";
			break;
		default:
			$date_calculation = "none";
		}

		$dateArray[] =  $event_date;

		$day = strtotime($event_date);
		$to = strtotime($event_end_date);

		while( $day <= $to ) 
		{
			$day = strtotime(date("Y-m-d", $day) . $date_calculation);
			if($day <= $to)
			$dateArray[] = date("Y-m-d" , $day);
		}


		//here make above array as key in $a array
		$start_date = $dateArray;
		$j = 1;
		for($i=0; $i < count($start_date); $i++ )
		{
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
		
		$booking_date = strtotime($start_date[$i]);
		
		$get_service_duration = DB::select( DB::raw("SELECT duration FROM provider_biz_service WHERE service_id = '$service1_id'" ));

		$breaks_time = $this->get_breaks_time($branch1_id);
		
		@$block_argument_count = @$get_service_duration[0]->duration / @$breaks_time;
		
		if(empty($block_argument_count)){
			$block_argument_count = 0;
		}else{
			$block_argument_count = $block_argument_count;
		}
		
		$today_date = strtotime(date('Y-m-d'));
		
			
		if($booking_time_from >= $booking_date && $booking_date >= $today_date ){
			
			if($get_service_no_of_booking != 0 ){
				
				if($get_service_no_of_booking >= $participants){
					
					if($get_staff1[0] != ""){ 
		
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date[$i],$staff_flag);
 
				//if($branch_aval_slots == ""){
					
					//$matrix2_Result [] =  array('status'=> 'false', 'message' =>'The given service or staff based slots not available.', 'content('.$start_date[$i].')'=>'(Busy)' );

				//}else{
					
					$get_service_padding = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('padding_time_when');

					if($get_service_padding == 1 ){
						$padding_before_value = 1;
						$padding_after_value = 0;
					}else if($get_service_padding == 2 ){
						$padding_before_value = 0;
						$padding_after_value = 1;
					}else if($get_service_padding == 3 ){
						$padding_before_value = 1;
						$padding_after_value = 1;
					}else {
						$padding_before_value = 0;
						$padding_after_value = 0;
					}
					
						$start_datetime = date_create($start_date[$i]);
						$start_date_format = date_format($start_datetime,"d-m-Y");

						$staff_ids = implode(",",$get_staff1 );

						//$matrix2_Result[] =  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date_format, 'service_id' => $service1_id, 'service_duration' => $get_service_duration[0]->duration, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'time_slots' => $branch_aval_slots ));					
						
						if($branch_aval_slots){
						
							$branch_aval_slots_list[] =  array('date'.$j =>$start_date_format,  'time_slots'.$j => $branch_aval_slots );					
	
						}
						$j = $j+1;
					//}	
				
			}/* else{
						$matrix2_Result[] =  array('status'=> 'false','message' =>'The Staff is not available for thi service. ', 'content('.$start_date[$i].')'=>'(Busy)' );
				}				
					}else{
							$matrix2_Result[] =  array('status'=> 'false','message' =>'The given participants count is grater than the allowed participants. ','content('.$start_date[$i].')'=>'(Busy)' );
					}
						}else{
								$matrix2_Result[] =  array('status'=> 'false','message' =>'The Service no of participants is Full. ','content('.$start_date[$i].')'=>'(Busy)' );
						}
							}else{
									$matrix2_Result[] =  array('status'=> 'false','message' =>'The given booking date is past or blocked future date. ', 'content('.$start_date[$i].')'=>'(Busy)' );
							}	 */				
			
		}
	}
	}
}
		if(@$branch_aval_slots_list){
			$matrix2_Result =  array('status'=> 'true', 'message' =>'success','content'=> array('service_id' => $service1_id, 'service_duration' => $get_service_duration[0]->duration, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'slots' => $branch_aval_slots_list ));					
		}else{
			
			$matrix2_Result =  array('status'=> 'false', 'content'=>'(Busy)' );
		}
			return $matrix2_Result;
		
	}
	
	
	public function getMatrix3_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date, $timezone_id, $meetingtype_id){
	
	/*public function getMatrix3_Result(Request $request){
		
		$participants=$request->input('participants');
		$branch1_id=$request->input('branch1_id');
		$service1_id=$request->input('service1_id');
		$staff1_id=$request->input('staff1_id');
		$start_date=$request->input('start_date');
		$timezone_id=$request->input('timezone_id');
		$meetingtype_id=$request->input('meetingtype_id');*/
		
		$get_service_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
		
		//get staff
		$get_staff1 = explode(",",$staff1_id);
		$staff_flag = 1;
		
		//print_r($get_staff1);die;

		$get_booking_time_period = explode("-",$this->getBookingTimePeriod($branch1_id));
		
		$booking_time_from = $get_booking_time_period[0];
		
		$booking_time_till = $get_booking_time_period[1];
		
		$booking_date = strtotime($start_date); 
		
		$get_service_duration = DB::select( DB::raw("SELECT duration FROM provider_biz_service WHERE service_id = '$service1_id'" ));

		$breaks_time = $this->get_breaks_time($branch1_id);
		
		$block_argument_count = @$get_service_duration[0]->duration / @$breaks_time;
		
		if(empty($block_argument_count)){
			$block_argument_count = 0;
		}else{
			$block_argument_count = $block_argument_count;
		}
		
		$today_date = strtotime(date('Y-m-d'));
		
		if($booking_time_from >= $booking_date && $booking_date >= $today_date ){
			
			if($get_service_no_of_booking != 0 ){
				
				if($get_service_no_of_booking >= $participants){

					if($get_staff1[0] != ""){ 
				
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date,$staff_flag);
				
				$get_service_padding = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('padding_time_when');
								
				if($get_service_padding == 1 ){
					$padding_before_value = 1;
					$padding_after_value = 0;
				}else if($get_service_padding == 2 ){
					$padding_before_value = 0;
					$padding_after_value = 1;
				}else if($get_service_padding == 3 ){
					$padding_before_value = 1;
					$padding_after_value = 1;
				}else {
					$padding_before_value = 0;
					$padding_after_value = 0;
				}
				if($branch_aval_slots == ""){
					
					return $this->createErrorResponse("The given service or staff based slots not available.", 405);


				}else{
										$start_datetime = date_create($start_date);
										$start_date = date_format($start_datetime,"d-m-Y");
										
								
										$staff_ids = implode(",",$get_staff1 );
										$matrix3_Result=  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date, 'service_id' => $service1_id, 'service_duration' => $get_service_duration[0]->duration, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'time_slots' => $branch_aval_slots ));
									
									
						}			
								}else{
										return $this->createErrorResponse("The Staff is not available for this service.", 406);

								}				
								}else{
										return $this->createErrorResponse("The given participants count is grater than the allowed participants.", 407);
																			
								}
								}else{
										return $this->createErrorResponse("The Service no of participants is Full.", 408);


								}
								}else{
										return $this->createErrorResponse("The given booking date is past or blocked future date.", 409);

								}
								

			return $matrix3_Result;
		
	}
	
	
	
	public function getMatrix4_Result($participants,$branch1_id, $service1_id, $staff1_id, $start_date,$end_date, $repetition_type, $timezone_id, $meetingtype_id){

		$event_date = $start_date;
		$event_end_date = $end_date;
		$event_repetition_type = $repetition_type;

		$date_calculation = "";
		switch ($event_repetition_type) {
			case "1":
			$date_calculation = " +1 day";
			break;
		case "2":
			$date_calculation = " +1 week";
			break;
		case "3":
			$date_calculation = " +1 month";
			break;
		default:
			$date_calculation = "none";
		}

		$dateArray[] =  $event_date;

		$day = strtotime($event_date);
		$to = strtotime($event_end_date);

		while( $day <= $to ) 
		{
			$day = strtotime(date("Y-m-d", $day) . $date_calculation);
			if($day <= $to)
			$dateArray[] = date("Y-m-d" , $day);
		}


		//here make above array as key in $a array
		$start_date = $dateArray;
		$j = 1;
		for($i=0; $i < count($start_date); $i++ )
		{
			$get_service_no_of_booking = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('participants_allowed');
		
			$get_staff1 = explode(",",$staff1_id);
			$staff_flag = 1;
		
		
		$get_booking_time_period = explode("-",$this->getBookingTimePeriod($branch1_id));
		
		$booking_time_from = $get_booking_time_period[0];
		
		$booking_time_till = $get_booking_time_period[1];	
		
		$booking_date = strtotime($start_date[$i]);
		
		$get_service_duration = DB::select( DB::raw("SELECT duration FROM provider_biz_service WHERE service_id = '$service1_id'" ));

		$breaks_time = $this->get_breaks_time($branch1_id);
		
		@$block_argument_count = @$get_service_duration[0]->duration / @$breaks_time;
		
		if(empty($block_argument_count)){
			$block_argument_count = 0;
		}else{
			$block_argument_count = $block_argument_count;
		}
		
		$today_date = strtotime(date('Y-m-d'));
		
			
		if($booking_time_from >= $booking_date && $booking_date >= $today_date ){
			
			if($get_service_no_of_booking != 0 ){
				
				if($get_service_no_of_booking >= $participants){
					
					if($get_staff1[0] != ""){ 
		
				$branch_aval_slots = $this->getProviderAvaliableTimeSlots($branch1_id,$service1_id,$get_staff1,$start_date[$i],$staff_flag);
 
				//if($branch_aval_slots == ""){
					
					//$matrix4_Result [] =  array('status'=> 'false', 'message' =>'The given service or staff based slots not available.', 'content('.$start_date[$i].')'=>'(Busy)' );

				//}else{
					
					$get_service_padding = DB::table('provider_biz_service')->where('service_id', $service1_id)->value('padding_time_when');

					if($get_service_padding == 1 ){
						$padding_before_value = 1;
						$padding_after_value = 0;
					}else if($get_service_padding == 2 ){
						$padding_before_value = 0;
						$padding_after_value = 1;
					}else if($get_service_padding == 3 ){
						$padding_before_value = 1;
						$padding_after_value = 1;
					}else {
						$padding_before_value = 0;
						$padding_after_value = 0;
					}
					
						$start_datetime = date_create($start_date[$i]);
						$start_date_format = date_format($start_datetime,"d-m-Y");

						$staff_ids = implode(",",$get_staff1 );

						//$matrix4_Result[] =  array('status'=> 'true', 'message' =>'success','content'=> array('date' =>$start_date_format, 'service_id' => $service1_id, 'service_duration' => $get_service_duration[0]->duration, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'time_slots' => $branch_aval_slots ));					
						
						
						if($branch_aval_slots){
						
						$branch_aval_slots_list[] =  array('date'.$j =>$start_date_format,  'time_slots'.$j => $branch_aval_slots );					
						
							
						}
						$j = $j+1;
					//}	
				
			}/* else{
						$matrix4_Result[] =  array('status'=> 'false','message' =>'The Staff is not available for thi service. ', 'content('.$start_date[$i].')'=>'(Busy)' );
				}				
					}else{
							$matrix4_Result[] =  array('status'=> 'false','message' =>'The given participants count is grater than the allowed participants. ','content('.$start_date[$i].')'=>'(Busy)' );
					}
						}else{
								$matrix4_Result[] =  array('status'=> 'false','message' =>'The Service no of participants is Full. ','content('.$start_date[$i].')'=>'(Busy)' );
						}
							}else{
									$matrix4_Result[] =  array('status'=> 'false','message' =>'The given booking date is past or blocked future date. ', 'content('.$start_date[$i].')'=>'(Busy)' );
							}	 */				
			
} 
}
}
}
		if(@$branch_aval_slots_list){
		$matrix4_Result =  array('status'=> 'true', 'message' =>'success','content'=> array('service_id' => $service1_id, 'service_duration' => $get_service_duration[0]->duration, 'staff_id'=>$staff_ids, 'no_of_participants' => $get_service_no_of_booking, 'slots_to_be_blocked' => $block_argument_count, 'padding_before_value' => @$padding_before_value, 'padding_after_value' => @$padding_after_value, 'slots' => $branch_aval_slots_list ));					
		}else{
			
			$matrix4_Result =  array('status'=> 'false', 'content'=>'(Busy)' );
		}
	return $matrix4_Result;
	
}

	
}	
?>