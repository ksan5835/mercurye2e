<?php 

namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
  
class Appointment extends Model
{
     
     protected $fillable = ['first_name', 'last_name', 'email','password','mobile'];
     
}
