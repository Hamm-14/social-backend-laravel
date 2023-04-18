<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
 
class PostResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'description' => $this->description,
            'post_pic' => $this->post_pic,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}