<?php

use App\Http\Controllers\Pages\AboutUsPageController;
use App\Http\Controllers\Pages\HomePageController;
use App\Http\Controllers\Pages\Posts\PostsIndexController;
use App\Http\Controllers\Pages\Posts\PostsShowController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePageController::class)->name('home');
Route::get('/about-us', AboutUsPageController::class)->name('about-us');
Route::get('/articles', PostsIndexController::class)->name('posts.index');
Route::get('/articles/{post:slug}', PostsShowController::class)->name('posts.show');
