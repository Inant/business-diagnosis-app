<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageGenerator\ImageGenService;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('frontoffice.generator.landing-page');
    }

}
