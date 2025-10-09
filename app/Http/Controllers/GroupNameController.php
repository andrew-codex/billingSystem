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
                return back()->with('error', 'Cannot delete group with assigned line men.');
            }

            $group->delete();

            return back()->with('success', 'Group deleted successfully.');
        }

    public function updateGroupAssignment(Request $request)
    {
        $request->validate([
            'lineman_ids' => 'required|array',
            'lineman_ids.*' => 'exists:line_men,id',
            'new_group_id' => 'nullable|exists:groups,id',
        ]);

        LineMan::whereIn('id', $request->lineman_ids)
            ->update(['group_id' => $request->new_group_id]);

        $groupName = $request->new_group_id ? Group::find($request->new_group_id)->name : 'No Group';
        
        return back()->with('success', 'Linemen assigned to ' . $groupName . ' successfully.');
    }

    
    public function changeGroup(Request $request, $id)
    {
        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $lineman = LineMan::findOrFail($id);
        $oldGroup = $lineman->group ? $lineman->group->name : 'No Group';
        
        $lineman->update([
            'group_id' => $request->group_id
        ]);

        $newGroup = $request->group_id ? Group::find($request->group_id)->name : 'No Group';

        return back()->with('success', $lineman->first_name . ' moved from ' . $oldGroup . ' to ' . $newGroup);
    }

    
    public function getByGroup($groupId = null)
    {
        if ($groupId) {
            $linemen = LineMan::where('group_id', $groupId)->with('group')->get();
            $group = Group::find($groupId);
            return response()->json([
                'linemen' => $linemen,
                'group' => $group
            ]);
        } else {
            $linemen = LineMan::whereNull('group_id')->get();
            return response()->json([
                'linemen' => $linemen,
                'group' => null

            ]);
        }
    }
    
}
