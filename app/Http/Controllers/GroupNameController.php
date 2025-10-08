<?php

namespace App\Http\Controllers;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupNameController extends Controller
{
    public function storeGroup(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:groups,group_name',
            'description' => 'nullable|string|max:1000',
        ]);

        Group::create([
            'group_name' => $request->group_name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Group created successfully.');
    }
    
}
