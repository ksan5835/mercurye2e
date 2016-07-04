<?php

namespace App\Http\Controllers;


use App\Models\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class AppointmentController extends Controller{
  
  
    public function index(){
  
        $CustomersList  = Appointment::all();
  
        return response()->json($CustomersList);
  
    }
  
    /*public function getBook($id){
  
        $Book  = Book::find($id);
  
        return response()->json($Book);
    }
  
    public function createBook(Request $request){
  
        $Book = Book::create($request->all());
  
        return response()->json($Book);
  
    }
  
    public function deleteBook($id){
        $Book  = Book::find($id);
        $Book->delete();
 
        return response()->json('deleted');
    }
  
    public function updateBook(Request $request,$id){
        $Book  = Book::find($id);
        $Book->title = $request->input('title');
        $Book->author = $request->input('author');
        $Book->isbn = $request->input('isbn');
        $Book->save();
  
        return response()->json($Book);
    }*/
  
}
