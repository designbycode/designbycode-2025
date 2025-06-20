<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomePageController extends Controller
{

    /**
     * @return View
     */
    public function __invoke(): View
    {
        return view('pages.home');
    }
}
