<?php
namespace App\Http\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            "id" => $post->id,
            "title" => $post->title,
            "description" => $post->description,
            "status" => $post->status,
            "user_id" => $post->user_id,
            "created_at" => $post->created_at,
            "updated_at" => $post->updated_at,
        ];
    }
}
