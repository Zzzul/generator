<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return view('profile');
    }
}
