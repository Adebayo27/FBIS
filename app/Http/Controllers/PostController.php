<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getPosts()
    {
        $posts = Post::with('user')->paginate(10);
        return response()->json([
            'status' => 'success',
            'posts' => $posts,
        ]);
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'post' => 'required|string|max:255',
        ]);

        $post = Post::create([
            'post' => $request->post,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'post' => $post,
        ]);
    }

    public function getSinglePost($id)
    {
        $post = Post::find($id);
        return response()->json([
            'status' => 'success',
            'post' => $post,
        ]);
    }

    public function updatePost(Request $request, $id)
    {
        $request->validate([
            'post' => 'required|string|max:255',
        ]);

        $post = Post::find($id);
        if(!is_null($post)){
            if($post->user_id == $request->user()->id){
                $post->post = $request->post;
                $post->save();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Post updated successfully',
                    'post' => $post,
                ]); 
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Can\'t update this post',
            ], 401);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found',
            ], 404);
        }

        
    }

    public function destroyPost(Request $request, $id)
    {
        $post = Post::find($id);
        if(!is_null($post)){
            if($post->user_id == $request->user()->id){
                $post->isDeleted = true;
                $post->save();
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Can\'t delete post',
                ], 401);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully',
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found',
            ], 404);
        }
        
        

        
    }

    public function getComments(Request $request, $id){
        $post = Post::find($id);
        if(is_null($post)){
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found',
            ], 404); 
        }

        $comments = Comment::where(['post_id' => $post->id])->paginate(20);
        return response()->json([
            'status' => 'success',
            'message' => 'Comment fetched successfully',
            'comments' => $comments
        ]);
    }
}
