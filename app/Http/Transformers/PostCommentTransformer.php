<?php
namespace App\Http\Transformers;

use App\Models\PostComment;
use League\Fractal\TransformerAbstract;

class PostCommentTransformer extends TransformerAbstract
{
    public function transform(PostComment $comment)
    {
        return [
            "id" => $comment->id,
            "comment" => $comment->comment,
            "user_id" => $comment->user_id,
            "post_id" => $comment->post_id,
            "created_at" => $comment->created_at,
            "updated_at" => $comment->updated_at,
        ];
    }
}
