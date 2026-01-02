<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Abstracts\Http\Controller;

class Landing extends Controller
{
    public function index()
    {
        return view('double-entry::landing.index');
    }
}
