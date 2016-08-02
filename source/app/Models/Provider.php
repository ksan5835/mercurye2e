<?php 

namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
  
class Provider extends Model
{     
	 public $table = "provider";
     protected $fillable = ['provider_id', 'provider_email','availdate','timezone_id','bookdate','branch_id'];
     
}
