<?php

use App\Http\Controllers\Pages\AboutUsPageController;
use App\Http\Controllers\Pages\HomePageController;
use App\Http\Controllers\Pages\Posts\PostsIndexController;
use App\Http\Controllers\Pages\Posts\PostsShowController;
use App\Http\Controllers\Tools\FaviconConverterController;
use App\Http\Controllers\Tools\FaviconGeneratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePageController::class)->name('home');
Route::get('/about-us', AboutUsPageController::class)->name('about-us');
Route::get('/tutorials', PostsIndexController::class)->name('posts.index');
Route::get('/tutorials/{post:slug}', PostsShowController::class)->name('posts.show');
Route::get('/tools/favicon-converter', FaviconConverterController::class)->name('tools.favicon-converter');
Route::get('/tools/favicon-generator', FaviconGeneratorController::class)->name('tools.favicon-generator');
