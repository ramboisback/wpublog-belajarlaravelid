<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PostDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePostDashboardRequest;
use App\Http\Requests\UpdatePostDashboardRequest;

class PostDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = PostDashboard::latest()->filter(request(['keyword', 'category', 'author']))->paginate(7)->withQueryString();
        // return view('dashboard', [
        //     'posts' => $posts
        // ]);

        // $posts = Post::latest()->where('author_id', Auth::user()->id)->paginate(7);
        // return view('dashboard', [
        //     'posts' => $posts
        // ]);

        $posts = Post::latest()->where('author_id', Auth::user()->id);

        if (request('keyword')) {
            $posts->where('title', 'like', '%' . request('keyword') . '%');
        }
        
        return view('dashboard.index', [
            'posts' => $posts->paginate(7)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StorePostDashboardRequest $request)
    public function store(Request $request)
    {
        // validation
        // $request->validate([
        //     'title' => 'required|max:255|unique:posts',
        //     'category_id' => 'required|exists:categories,id',
        //     'body' => 'required'
        // ]);

        Validator::make($request->all(), [
            'title' => 'required|unique:blog_posts|min:4|max:255',
            'category_id' => 'required',
            'body' => 'required|min:50'
        ], [
            'title.required' => 'Field :attribute harus diisi',
            'category_id.required' => 'Pilih salah satu :attribute',
            'body.required' => ':attribute tidak boleh kosong',
            'body.min' => ':attribute minimal 50 karakter',

            // 'title.required' => 'Judul harus diisi',
            // 'title.unique' => 'Judul sudah ada',
            // 'title.min' => 'Judul minimal 4 karakter',
            // 'title.max' => 'Judul maksimal 255 karakter',
            // 'category_id.required' => 'Kategori harus dipilih',
            // 'category_id.exists' => 'Kategori tidak valid',
            // 'body.required' => 'Isi konten harus diisi'
        ], [
            'title' => 'Judul',
            'category_id' => 'Kategori',
            'body' => 'Isi Konten'
        ])->validate();

        Post::create([
                'title' => $request->title,
                'author_id' => Auth::user()->id,
                'category_id' => $request->category_id,
                'slug' => Str::slug($request->title),
                'body' => $request->body,
        ]);

        return redirect('/dashboard')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check if the post belongs to the authenticated user
        if ($post->author_id !== Auth::user()->id) {
            return redirect('/dashboard')->with('error', 'Unauthorized access!');
        }

        return view('dashboard.edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validation

        $request->validate([
            'title' => 'required|max:255|unique:blog_posts,title' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'body' => 'required'
        ]);

        // Validator::make($request->all(), [
        //     'title' => 'required|min:4|max:255|unique:blog_posts,title' . $post->id,
        //     'category_id' => 'required',
        //     'body' => 'required'
        // ], [
        //     'title.required' => 'Field :attribute harus diisi',
        //     'category_id.required' => 'Pilih salah satu :attribute',
        //     'body.required' => ':attribute tidak boleh kosong',
        // ], [
        //     'title' => 'Judul',
        //     'category_id' => 'Kategori',
        //     'body' => 'Isi Konten'
        // ])->validate();
        
        // Update Post
        $post->update([
                'title' => $request->title,
                'author_id' => Auth::user()->id,
                'category_id' => $request->category_id,
                'slug' => Str::slug($request->title),
                'body' => $request->body
        ]);

        // Redirect
        return redirect('/dashboard')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/dashboard')->with('success', 'Post deleted successfully!');
    }
}
