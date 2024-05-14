<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\MutashabihsGroup;

class MutashabihsGroupController extends Controller
{
    public function get ($id=null){

        if ($id){
            $group = MutashabihsGroup::find($id);
            if (!$group) return response()->json([
                'message' => 'This Group of Mutashabih don\'t exist',
                ],404);
            return $group->toJson(JSON_PRETTY_PRINT);
        }
        return MutashabihsGroup::all()->toJson(JSON_PRETTY_PRINT);
    }

    public function create (Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:mutashabihs_groups|max:50',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => [$validator->errors()->first('name')],
            ],404);       
        }

        $group= MutashabihsGroup::create($request->all());

        return $group->toJson(JSON_PRETTY_PRINT,200);

    }
    public function delete ($id){

        $group = MutashabihsGroup::find($id);
        if (!$group)  return response()->json([
                'message' => 'This group don\'t exist',
                 ],404);

        $group->delete();
        return response()->json([
                'message' => 'Deleted with success',
                ],200);
     }
}
