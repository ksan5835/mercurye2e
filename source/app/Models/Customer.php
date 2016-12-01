<?php 

namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
  
class Customer extends Model
{
     public $table = "customer"; 
     protected $fillable = ['first_name', 'last_name', 'email','password','mobile','bookdate','timeinterval1','timezone_id','timeinterval2','branch_id','service_id','type','staff_id','vstart_time','vend_time','provider_email','user_id','start_time','end_time','start_time2','end_time2','branch1_id','branch2_id','service1_id','service2_id','staff1_id','staff2_id','user_email','start_date','frequency','participants'];
     
}
