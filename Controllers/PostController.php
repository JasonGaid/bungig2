<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // Max 2MB image file
        ]);

        $data = $request->only(['user_id', 'title', 'content', 'category_id']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $imageName);
            $data['image'] = $path;
        }

        $post = Post::create($data);

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // Max 2MB image file
        ]);

        $data = $request->only(['title', 'content', 'category_id']);

        if ($request->hasFile('image')) {
            // Delete existing image if any
            if ($post->image) {
                Storage::delete($post->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $imageName);
            $data['image'] = $path;
        }

        $post->update($data);

        return response()->json($post->fresh(), 200);
    }

    public function delete(Post $post)
    {
        if ($post->image) {
            Storage::delete($post->image);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
