<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrownoutScheduling extends Controller
{
    public function index(){
        return view ('pages.brownoutScheduling');
    }
}
