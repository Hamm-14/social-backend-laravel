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
    /**
     * follow a user
     */
    public function followUser(Request $request) {
        $request->validate([
            "from_user" => 'required|integer',
            "to_user" => 'required|integer',
        ]);

        $friendship = Friendship::where(['from_user' => $request->from_user, 'to_user' => $request->to_user])->first();

        if($friendship){
            return ['message' => 'Already followed'];
        }

        $friendship = new Friendship([
            'from_user' => $request->from_user,
            'to_user' => $request->to_user,
        ]);

        $fromUser = User::find($request->from_user);

        $fromUser->friendships()->save($friendship);

        return ['message' => 'Successfully followed'];
    }

    /**
     * Unfollow a user
     */
    public function unFollowUser(Request $request) {
        $request->validate([
            "from_user" => 'required|integer',
            "to_user" => 'required|integer',
        ]);

        $user = User::find($request->from_user);

        $user->friendships()->where('from_user', $request->from_user)->where('to_user', $request->to_user)->delete();

        return ['message' => 'Successfully unfollowed'];
    }

    /**
     * get all user followings
     */
    public function fetchUserFollowing(Request $req) {
        $req->validate([
            'userId' => 'required|integer'
        ]);

        $userFollowings = User::find($req->userId)->friendships;

        return FollowingInfo::collection($userFollowings);
    }

    /**
     * get all user followers
     */
    public function fetchUserFollowers(Request $req) {
        $req->validate([
            'userId' => 'required|integer'
        ]);

        $userFollowers = Friendship::where('to_user', $req->userId)->get();

        return FollowersInfo::collection($userFollowers); 
    }
}
