<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Consumer;
use Illuminate\Http\Request;
use App\Models\ConsumerMeterHistory;

class DashboardController extends Controller
{
    public function index(){

        $totalStaff= User::where('role', '!=' ,'admin')->count();
        $activeStaff = User::where('status', 'active')->count();
        $inactiveStaff = User::where('status', 'inactive')->count();

        $totalConsumers = Consumer::count();
        $activeConsumers = User::where('status', 'active')->count();
        $inactiveConsumers = User::where('status', 'inactive')->count();

    $recentHistories = ConsumerMeterHistory::with(['meter', 'changedBy'])
        ->latest()
        ->take(5)
        ->get();



        return view('pages.dashboard', compact('activeStaff' , 'inactiveStaff',
         'totalStaff','totalConsumers', 'activeConsumers', 'inactiveConsumers', 'recentHistories'));
    } 
}
