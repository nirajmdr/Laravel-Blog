<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
         $this->middleware('auth',['except' =>['index','show']]);
     }
    public function index()
    {
        //$post = Post::all(); Here Post is name of Model
        //$post = Post::orderBy('created_at', 'desc')->take(1)->get();
        $post = Post::orderBy('created_at', 'desc')->paginate(5);

        return view('posts.index')->with('posts', $post);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          'title' => 'required',
          'body' => 'required',
          'cover_image' => 'image|nullable|max:1999'
        ]);

        //File Upload
        if($request->hasFile('cover_image')){
          //Get File name with Extension
          $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
          // Get filename only
          $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

          //Get extension only
          $extension = $request->file('cover_image')->getClientOriginalExtension();

          //File name to store
          $fileNameToStore = $filename.'_'.time().'.'.$extension;

          //Upload image

          $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore); //php artisan storage:link
        } else {
          $fileNameToStore = 'noimage.jpg';
        }
        //Create POSTS
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();
        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post =  Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        //Check for correct user
        if(auth()->user()->id !== $post->user_id){
          return redirect('/posts')->with('error', 'Unauthorized Access');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'title' => 'required',
        'body' => 'required'
      ]);
      //File Upload
      if($request->hasFile('cover_image')){
        //Get File name with Extension
        $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
        // Get filename only
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //Get extension only
        $extension = $request->file('cover_image')->getClientOriginalExtension();

        //File name to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;

        //Upload image

        $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore); //php artisan storage:link
      }
      //Update POSTS
      $post = Post::find($id);
      $post->title = $request->input('title');
      $post->body = $request->input('body');
      if($request->hasFile('cover_image')){
        $post->cover_image = $fileNameToStore;
      }
      $post->save();
      return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        //Check for correct user
        if(auth()->user()->id !== $post->user_id){
          return redirect('/posts')->with('error', 'Unauthorized Access');
        }

        if($post->cover_image != 'noimage.jpg'){
          //Delete image
          Storage::delete('public/cover_images/'.$post->cover_image);
        }
        $post->delete();
        return redirect('/posts')->with('success', 'Post Deleted');
    }
}
