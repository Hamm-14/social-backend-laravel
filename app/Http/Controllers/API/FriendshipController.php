<?php

namespace App\Http\Controllers\API;

use App\Models\Friendship;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowersInfo;
use App\Http\Resources\FollowingInfo;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function followUser(Request $request) {
        $request->validate([
            "from_user" => 'required|integer',
            "to_user" => 'required|integer',
        ]);

        $friendship = new Friendship([
            'from_user' => $request->from_user,
            'to_user' => $request->to_user,
        ]);

        $fromUser = User::find($request->from_user);

        $fromUser->friendships()->save($friendship);

        return ['message' => 'Successfully followed'];
    }

    public function fetchUserFollowing(Request $req) {
        $req->validate([
            'user_id' => 'required|integer'
        ]);

        $userFollowings = User::find($req->user_id)->friendships;

        return FollowingInfo::collection($userFollowings);
    }

    
    public function fetchUserFollowers(Request $req) {
        $req->validate([
            'user_id' => 'required|integer'
        ]);

        $userFollowers = Friendship::where('to_user', $req->user_id)->get();

        return FollowersInfo::collection($userFollowers); 
    }
}
