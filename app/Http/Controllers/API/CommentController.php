<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;


class CommentController extends Controller
{
     /**
     * Create a new comment
     */
    public function create(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'userId' => 'required|integer',
            'postId' => 'required|integer',
        ]);

        $post = Post::find($request->postId);

        if(!$post) {
            return Response(["message" => "Post not found"],404);
        }

        $comment = Comment::create([
            'description' => $request->description,
            'user_id' => $request->userId,
            'post_id' => $request->postId
        ]);

        $post->comments()->save($comment);

        return $comment;
    }

     /**
     * fetch post comments
     */
    public function comments(Request $request)
    {
        $request->validate([
            'postId' => 'required|integer'
        ]);

        $post = Post::find($request->postId);

        if(!$post) {
            return Response(["message" => "Post not found"],404);
        }

        $comments = Comment::where('post_id',$request->postId)->get();

        return $comments;
    }

      /**
     * update comment
     */
    public function update(Request $request)
    {
        $request->validate([
            'commentId' => 'required|integer',
            'description' => 'required'
        ]);

        $comment = Comment::find($request->commentId);

        if(!$comment) {
            return Response(["message" => "Comment not found"],404);
        }
 
        $comment->description = $request->description;
         
        $comment->save();

        return $comment;
    } 

    public function delete(Request $request)
    {
        $request->validate([
            'commentId' => 'required|integer',
        ]);

        $comment = Comment::find($request->commentId);

        if(!$comment) {
            return Response(["message" => "Comment not found"],404);
        }

        CommentLike::where('comment_id', $request->commentId)->delete();
        
        $comment->delete();

        return Response(['message' => 'Comment deleted successfully']);
    }

    public function toggleLike(Request $request) 
    {
        $request->validate([
            'commentId' => 'required|integer',
            'userId' => 'required|integer'
        ]);

        $comment = Comment::find($request->commentId);

        if(!$comment) {
            return Response(["message" => "Comment not found"],404);
        }

        $user = User::find($request->userId);

        if(!$user) {
            return Response(["message" => "User not found"],404);
        }

        $commentLike = CommentLike::where(['comment_id' => $request->commentId, 'user_id' => $request->userId])->first();

        if($commentLike){
            $commentLike->delete();
            return Response(["message" => "Comment dislilked successfully"]);
        }

        $commentLike = CommentLike::create([
            "user_id" => $request->userId,
            "comment_id" => $request->commentId,
        ]);

        $comment->commentLikes()->save($commentLike);
        
        return Response(["message" => "Comment liked successfully"]);
    }
}
