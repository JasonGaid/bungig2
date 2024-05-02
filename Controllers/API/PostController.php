<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                // Modify the image path to reflect the public URL
                $imagePath = 'storage/' . $imagePath;
            }

            // Create a new post
            $post = new Post();
            $post->fill($validatedData);
            $post->image = $imagePath; // Save modified image path
            $post->save();

            // Return success response with the URL of the image
            return response()->json(['message' => 'Post created successfully', 'image_url' => asset($imagePath)], 201);
        } catch (\Exception $e) {
            // Return error response if something went wrong
            return response()->json(['message' => 'Failed to create post', 'error' => $e->getMessage()], 500);
        }
    }
    public function show($id)
{
    try {
        $category = Category::findOrFail($id);
        return response()->json($category);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Category not found', 'error' => $e->getMessage()], 404);
    }
}

public function update(Request $request, Post $post)
{
    $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation rules for the image file
    ]);

    // Update the post with the new data
    $post->update([
        'title' => $request->title,
        'content' => $request->content,
        'category_id' => $request->category_id,
        // Add any other fields to update here
    ]);

    if ($request->hasFile('image')) {
        if ($post->image) {
            Storage::delete($post->image);
        }        $imagePath = $request->file('image')->store('images', 'public');
        $post->image = $imagePath;
    }
    $post->save();

    return response()->json(['message' => 'Post updated successfully'], 200);
}


    public function delete(Post $post)
    {
        try {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            // Return error response if something went wrong
            return response()->json(['message' => 'Failed to delete post', 'error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->get(); // Fetch posts in descending order of creation
        return response()->json($posts);
    }
}
