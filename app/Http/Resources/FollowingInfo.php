<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use Illuminate\Http\Resources\Json\JsonResource;
 
class FollowingInfo extends JsonResource
{
    function __construct(Friendship $model)
    {
        parent::__construct($model);
    }
    public function toArray(Request $request)
    {
        return [
            'following' => new UserResource($this->toUser),
      ];
    }
}