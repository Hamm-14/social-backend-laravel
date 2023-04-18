<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
     /**
     * Create a new post
     */
    public function create(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'userId' => 'required|integer',
        ]);

        $post = Post::create([
            'description' => $request->description,
            'user_id' => $request->userId,
        ]);

        $user = User::find($request->userId);

        $user->posts()->save($post);

        return $post;
    }

     /**
     * fetch all posts
     */
    public function allPosts(Request $request)
    {
        $posts = Post::all();

        return PostResource::collection($posts);
    } 

     /**
     * fetch user posts
     */
    public function userPosts(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer'
        ]);

        $posts = Post::where('user_id',$request->userId)->get();

        return $posts;
    }

    public function update(Request $request)
    {
        $request->validate([
            'postId' => 'required|integer',
            'description' => 'required'
        ]);

        $post = Post::find($request->postId);

        if(!$post) {
            return Response(["message" => "Post not found"],404);
        }
 
        $post->description = $request->description;
         
        $post->save();

        return $post;
    } 

    public function delete(Request $request)
    {
        $request->validate([
            'postId' => 'required|integer',
        ]);

        $post = Post::find($request->postId);

        if(!$post) {
            return Response(["message" => "Post not found"],404);
        }

        PostLike::where('post_id', $request->postId)->delete();
        
        $post->delete();

        return Response(['message' => 'Post deleted successfully']);
    }

    public function toggleLike(Request $request) 
    {
        $request->validate([
            'postId' => 'required|integer',
            'userId' => 'required|integer'
        ]);

        $post = Post::find($request->postId);

        if(!$post) {
            return Response(["message" => "Post not found"],404);
        }

        $user = User::find($request->userId);

        if(!$user) {
            return Response(["message" => "User not found"],404);
        }

        $postLike = PostLike::where(['post_id' => $request->postId, 'user_id' => $request->userId])->first();

        if($postLike){
            $postLike->delete();
            return Response(["message" => "Post dislilked successfully"]);
        }

        $postLike = PostLike::create([
            "user_id" => $request->userId,
            "post_id" => $request->postId,
        ]);

        $post->postLikes()->save($postLike);
        
        return Response(["message" => "Post liked successfully"]);
    }

      /**
     * Upload post_pic
     */
    public function uploadPic(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:1048',
            'postId' => 'required|integer',
        ]);

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('images/post'),$imageName);

        $post = Post::find($request->postId);

        $post->post_pic = $imageName;

        $post->save();

        return Response(["message" => 'Image uploaded successfully']);
    }
}
