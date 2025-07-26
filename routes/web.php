<?php

use App\Http\Controllers\PostDashboardController;
use App\Models\Post;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'title' => 'Codeqiu',
        'nav' => 'Beranda',
        'isi' => 'Halaman Beranda dhanni sultan'
    ]);
});

// Route::get('/blog', function () {
//     return view('blog', [
//         'title' => 'Halaman Blog',
//         'nav' => 'Blog',
//         'isi' => 'Halaman Blog dhanni sultan'
//     ]);
// });

Route::get('/posts', function () {
    $posts = Post::latest()->filter(request(['keyword', 'category', 'author']))->paginate(5)->withQueryString();

    return view('posts', [
        'title' => 'Halaman Blog',
        'nav' => 'Blog',
        'isi' => 'Our Blog',
        'posts' => $posts
    ]);
});

Route::get('/posts/{post:slug}', function (Post $post) {
    return view('post', [
        'title' => 'Halaman Single Blog',
        'nav' => 'Single Post',
        'isi' => 'Halaman Single Post Slug dhanni sultan',
        'post' => $post
    ]);
});

Route::get('/about', function () {
    return view('about', [
        'title' => 'Halaman Tentang',
        'nav' => 'Tentang',
        'isi' => 'Halaman Tentang dhanni sultan'
    ]);
});

Route::get('/contact', function () {
    return view('contact', [
        'title' => 'Halaman Kontak',
        'nav' => 'Kontak',
        'isi' => 'Halaman Kontak dhanni sultan'
    ]);
});

// Dashboard Backend
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', [PostDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::post('/dashboard', [PostDashboardController::class, 'store'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard/create', [PostDashboardController::class, 'create'])->middleware(['auth', 'verified']);

// Route::delete('/dashboard/{post:slug}', [PostDashboardController::class, 'destroy'])->middleware(['auth', 'verified']);

// Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show'])->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [PostDashboardController::class, 'store']);
    Route::get('/dashboard/create', [PostDashboardController::class, 'create']);
    Route::delete('/dashboard/{post:slug}', [PostDashboardController::class, 'destroy']);
    Route::get('/dashboard/{post:slug}/edit', [PostDashboardController::class, 'edit']);
    Route::patch('/dashboard/{post:slug}', [PostDashboardController::class, 'update']);
    Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show']);
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload', [ProfileController::class, 'upload']);
});

require __DIR__.'/auth.php';
