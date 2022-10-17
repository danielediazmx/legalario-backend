<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\PostCommentTransformer;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            //Validated
            $validateComment = Validator::make($request->all(),
                [
                    'comment' => 'required',
                    'user_id' => 'required|integer',
                    'post_id' => 'required|integer'
                ]);

            if ($validateComment->fails()) throw new \Exception($validateComment->errors());

            $post = Post::find($request->post_id);
            if (!$post) throw new \Exception("Post not found.");

            $comment = PostComment::create([
                'comment' => $request->comment,
                'user_id' => $request->user_id,
                'post_id' => $request->post_id
            ]);

            return fractal($comment, new PostCommentTransformer())->respond(200, [], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
