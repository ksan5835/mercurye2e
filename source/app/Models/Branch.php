<?php 

namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
  
class Branch extends Model
{     
	 public $table = "provider_biz_branch";
     protected $fillable = ['branch_id', 'branch_name', 'branch_phone','branch_landline','branch_email','branch_fax','branch_url','branch_address1'
	 ,'branch_address2','branch_city_id','branch_zipcode','biz_id','provider_id','is_active','timezone_id','currency_id','create_systemuser_id','update_systemuser_id','service_id'];
     
}
