<?php 

namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
  
class Customer extends Model
{
     
     protected $fillable = ['first_name', 'last_name', 'email','password','mobile','bookdate','timeinterval1','timezone_id','timeinterval2','branch_id','service_id','type','staff_id'];
     
}
