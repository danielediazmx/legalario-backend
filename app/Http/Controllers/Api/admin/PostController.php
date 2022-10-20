<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Transformers\PostTransformer;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function switchStatus($post_id, $status)
    {
        var_dump($post_id);
        $post = Post::where(['id' => $post_id])->first();
        if ($post) {
            $post->status = $status;
            $post->save();
        }

        return response()->json(['status' => (bool)$post]);
    }
}
