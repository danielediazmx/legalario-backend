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
    /**
     * return all posts
     */
    public function index()
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $status = $_GET['status'] ?? null;

        $posts = Post::whereStatus($status)->whereCreatedBetween($startDate, $endDate)->get();
        return fractal($posts, new PostTransformer())->respond(200, [], JSON_PRETTY_PRINT);
    }

    public function switchStatus($post_id, $status)
    {
        $post = Post::where(['id' => $post_id, 'status' => $status])->first();
        if ($post) {
            $post->status = $status;
            $post->save();
        }

        return response()->json(['status' => (bool)$post]);
    }
}
