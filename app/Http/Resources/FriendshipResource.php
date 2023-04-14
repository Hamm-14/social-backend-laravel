<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
 
class FriendshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'to_user' => new UserResource($this->toUser),
            'from_user' => new UserResource($this->fromUser),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}