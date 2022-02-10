<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;

class PostController extends Controller
{
    
    public function index(){

    	return response()->json(Post::all());
    }

    public function store(Request $request){
     
     $validator = Validator::make($request->all(),[
                'title' => 'required',
                'description' => 'required',
    	]);

    	if($validator->fails()){
    		return response()->json(['status_code' => 400, 'message' => 'Bad Request']);
    	}

    	// $post = new Post();
    	// $post->title = $request->title;
    	// $post->description = $request->description;
 
    	// // $data = $post->save();
           
           $input = $request->all();
           $data = Post::create($input);

    	return response()->json([
       'status_code' => 200,
       'data' => $data,
       'message'  =>  'post create Succesfully !'
    	]);

    }

    public function show($id){
         
    if($data = Post::find($id)){
     return response()->json($data);
    }else{
      return response()->json(['status_code' => 400, 'message' => 'Bad Request']);
    }
}

    public function update(Request $request, $id){
       $data = Post::find($id);
       if($data){
       	$input = $request->all();
       	$data->update($input);

       	return response()->json([
       		'status_code' => 200,
       		'data' => Post::find($id),
       		'message' => 'Post Update Succesfully !'
       	]);

       }else{
       	return response()->json(['status_code' => 400, 'message' => 'Data not found']);
       }
  
    }

    public function destroy($id){

    if($data = Post::find($id)){
    	$data->delete();
     return response()->json(['status_code' => 200, 'message' => "Your data deleted"]);
    }else{
      return response()->json(['status_code' => 400, 'message' => 'Bad Request']);
    }
    }
}
