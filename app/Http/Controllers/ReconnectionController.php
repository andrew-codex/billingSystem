<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class ReconnectionController extends Controller
{
    public function index(){
     $regions = Cache::rememberForever('regions_all', function () {
        return DB::table('regions')->orderBy('name')->get();
    });

    $provinces = Cache::rememberForever('provinces_all', function () {
        return DB::table('provinces')->orderBy('name')->get();
    });



    $linemen = LineMan::orderBy('first_name')->get();


        return view('pages.reconnection', compact('regions', 'provinces', 'linemen'));
    }
}
