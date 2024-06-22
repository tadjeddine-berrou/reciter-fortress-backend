<?php

namespace App\Http\Controllers;

use App\Models\Mutashabih;
use Validator;
use Illuminate\Http\Request;
use App\Models\MutashabihsGroup;

class MutashabihsGroupController extends Controller
{
    public function list()
    {
        return response()->json(
            MutashabihsGroup::withCount('mutashabih')->get()
        );
    }

    public function get(MutashabihsGroup $group){
        return response()->json($group);
    }

    public function create(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|unique:mutashabihs_groups',
        ]);

        $group= MutashabihsGroup::query()->create($validated);
        return response()->json($group,200);
    }

    public function update (MutashabihsGroup $group, Request $request){

        $validated = $request->validate([
            'name' => 'required|string|unique:mutashabihs_groups',
        ]);

        $group->update($validated);
        return response()->json($group,200);
    }

    public function delete (MutashabihsGroup $group){
        $group->delete();
        return response()->json([
            'message' => 'Deleted with success',
        ],200);
     }
}
