<?php

use App\Livewire\Pages\AboutUsPage;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\Posts\PostShow;
use App\Livewire\Pages\Posts\PostsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/about-us', AboutUsPage::class)->name('about-us');
Route::get('/tutorials', PostsIndex::class)->name('posts.index');
Route::get('/tutorials/{post:slug}', PostShow::class)->name('posts.show');
