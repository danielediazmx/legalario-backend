<?php

namespace App\Http\Controllers\Api;

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
        $posts = Post::where('status', Post::ACTIVE)->get();
        return fractal($posts, new PostTransformer())->respond(200, [], JSON_PRETTY_PRINT);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            //Validated
            $validatePost = Validator::make($request->all(),
                [
                    'title' => 'required',
                    'description' => 'required',
                    'user_id' => 'required|integer'
                ]);

            if ($validatePost->fails()) throw new \Exception($validatePost->errors());

            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'status' => 0
            ]);

            return fractal($post, new PostTransformer())->respond(200, [], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            //Validated
            $validatePost = Validator::make($request->all(),
                [
                    'title' => 'required',
                    'description' => 'required',
                ]);

            if ($validatePost->fails()) throw new \Exception($validatePost->errors());

            $post = Post::findOrFail($id);
            if ($post && $post->user_id == Auth::user()->id) {
                $post->title = $request->title;
                $post->description = $request->description;
                $post->save();
            } else {
                throw new \Exception("Invalid post");
            }

            return fractal($post, new PostTransformer())->respond(200, [], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function disablePost($id)
    {
        $post = Post::where(['id' => $id, 'user_id' => Auth::user()->id])->first();
        if ($post) {
            $post->status = Post::INACTIVE;
            $post->save();
        }

        return response()->json(['status' => (bool)$post]);
    }
}
