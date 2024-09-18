<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // show all posts
        $posts = Post::all();

        //return $this->sendResponse($posts,'All Posts here');
        $response = [
            'success' => true,
            'message' => "All posts data",
            'data' => [
                'posts' => $posts
            ]
        ];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        //validate the post data
        $validatePost = Validator::make(
            $req->all(),[
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',

            ]);

        //if the validation fails then gives error
        if($validatePost->fails()){
            $errormessage = $validatePost->errors()->all();
            return $this->sendError('Validation Error',$errormessage,401);
        }

        $img = $req->file('image');
        $exten = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $exten;
        $img->move(public_path('uploads'), $imageName);

        //if the authentication pass then store the data
        $post = Post::create([
            'title' => $req->title,
            'description' => $req->description,
            'image' => $imageName,
        ]);

        //if the post data is inserted successfully
        return $this->sendResponse($post,'Post Created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where('id',$id)->first();


        if (!$data) {
            $errormessage = '';
            return $this->sendError('Post not found',$errormessage,404);
        }

        //if the post data is fetched successfully
        return $this->sendResponse($data,'Your Post is here');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        //validate the post data
        $validatePost = Validator::make($req->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif',
        ]);
    
        if ($validatePost->fails()) {
            return $this->sendError('Validation Error', $validatePost->errors()->all(), 401);
        }

        //get the post data
        $postData = Post::select('id','image')->where('id',$id)->get();
        //delete the post image if exist
        if($req->hasFile('image') != ''){
            $path = public_path() . '/uploads/';

            if($postData[0]->image !='' && $postData[0]->image != null ){
                $old_file = $path .'/'. $postData[0]->image;
                if(file_exists($old_file)){
                    unlink($old_file);
                }
            }

            $img = $req->image;
            $exten = $img->getClientOriginalExtension();
            $imageName = time(). '.' .$exten;
            $img->move(public_path(). '/uploads',$imageName);
        }else{
            $imageName = $postData[0]->image;
        }

        

        //if the authentication pass then store the data
        $post = Post::where(['id' => $id])->update([
            'title' => $req->title,
            'Description' => $req->description,
            'image' => $imageName,
        ]);

        //if the post data is inserted successfully
        return $this->sendResponse($post,'Post Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //first delete the image
        $post = Post::select('image')->where('id',$id)->first();

        if (!$post) {
            // Post not found
            $errormessage = '';
            return $this->sendError('Post not found',$errormessage,404);
        }

        if($post->image){
            $filePath = public_path('uploads/' . $post->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $post = Post::where('id', $id)->delete();

        return $this->sendResponse($id,'Post Deleted successfully');
    }
}
