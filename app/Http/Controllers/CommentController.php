<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createComment(Request $request){
        $request->validate([
            'comment' => 'required|string|max:255',
            'post_id' => 'required|int|max:255',
        ]);
        $post = Post::find($request->post_id);
        if(is_null($post)){
            return response()->json([
                'status' => 'error',
                'message' => 'post not found',
            ], 403);  
        }
        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => $request->user()->id,
            'post_id' => $request->post_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'comment created successfully',
            'todo' => $comment,
        ]);
    }

    public function destroyComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        if(!is_null($comment)){
            if($comment->user_id == $request->user()->id){
                $comment->isDeleted = true;
                $comment->save();
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Can\'t delete comment',
                ], 401);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'comment deleted successfully',
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'comment not found',
            ], 404);
        }

        
    }
}
