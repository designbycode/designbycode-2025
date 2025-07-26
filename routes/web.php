<?php

use App\Livewire\Pages\AboutUsPage;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\Posts\PostsIndex;
use App\Livewire\Pages\Posts\PostsShow;
use App\Livewire\Tools\ImageConverter;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/about-us', AboutUsPage::class)->name('about-us');
Route::get('/articles', PostsIndex::class)->name('posts.index');
Route::get('/articles/{post:slug}', PostsShow::class)->name('posts.show');

Route::get('tools/image-converter', ImageConverter::class)->name('image-converter');
