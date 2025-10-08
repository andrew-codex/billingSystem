<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:groups',
            'description' => 'nullable|string'
        ]);

        Group::create($request->all());

        return redirect()->back()->with('success', 'Group created successfully!');
    }

    public function edit(Group $group)
    {
        return response()->json($group);
    }

    public function updateGroup(Request $request, Group $group)
    {
        $request->validate([
            'group_name' => 'required|string|max:255|unique:groups,group_name,' . $group->id,
            'description' => 'nullable|string'
        ]);

        $group->update($request->all());

        return redirect()->back()->with('success', 'Group updated successfully!');
    }

    public function destroyGroup(Group $group)
    {
        if ($group->linemen()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete group with assigned line men'
            ]);
        }

        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully'
        ]);
    }
}