<?php

use App\Livewire\AboutUsPage;
use App\Livewire\HomePage;
use App\Livewire\Posts\PostShow;
use App\Livewire\Posts\PostsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/about-us', AboutUsPage::class)->name('about-us');
Route::get('/tutorials', PostsIndex::class)->name('posts.index');
Route::get('/tutorials/{post:slug}', PostShow::class)->name('posts.show');
