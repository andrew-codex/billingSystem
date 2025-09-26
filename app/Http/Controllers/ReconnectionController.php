<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class ReconnectionController extends Controller
{
    public function index(){


    $availableCount = LineMan::where('availability', 1)->count();

    $linemen = LineMan::orderBy('first_name')->get();


   


    
    





        return view('pages.reconnection', compact( 'linemen', 'availableCount'));
    }
}
