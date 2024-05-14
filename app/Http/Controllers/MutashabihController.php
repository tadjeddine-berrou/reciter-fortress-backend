<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Mutashabih;
use App\Models\Verse;
use App\Models\Word;

class MutashabihController extends Controller
{
    public function create (Request $request)
    {
        //Validate the Request

        $validator_mutashabih = Validator::make($request->input('mutashabih'), [
            'chapter_id' => 'required|integer',
            'chapter_name'=> 'required|string|max:50',
            'code_v1'=> 'required|string|max:50',
            'group_id' => 'required|integer|exists:mutashabihs_groups,id',
            'order'  => 'required|integer',
            'text_imlaei_simple'  => 'required|string',
            'text_uthmani' => 'required|string',
         ]);
         $validator_verses=[];

        foreach($request->input('verses') as $verse)
        {
            //dd($verse);
            array_push( $validator_verses,Validator::make($verse , [
                'chapter_id' => 'required|integer',
                'hizb_number'=> 'required|integer',
                'juz_number'=> 'required|integer',
                'page_number'=> 'required|integer',
                'rub_el_hizb_number' => 'required|integer',
                'verse_id'  => 'required|integer',
                'verse_number'  => 'required|integer',
                'text_imlaei_simple'  => 'required|string',
                'text_uthmani' => 'required|string',
             ])
            );
        }

        $validator_words=[];

        foreach($request->input('words') as $word)
        {
            array_push( $validator_words,Validator::make($word , [
                'chapter_id' => 'required|integer',
                'char_type_name'=> 'required|string',
                'code_v1'=> 'required|string|max:20',
                'page_number'=> 'required|integer',
                'line_number' => 'required|integer',
                'location'  => 'required|string|max:20',
                'position'  => 'required|string|max:20',
                'offset_right_percent'  => 'required|integer',
                'verse_id'  => 'required|integer',
                'verse_number'  => 'required|integer',
                'text'  => 'required|string',
                'text_uthmani' => 'required|string',
                'word_id'  => 'required|integer',
                'width_percent'  => 'required|integer',
             ])
            );
        }

        // check validator errors of mutashabih pyload
        if($validator_mutashabih->fails())
            return  response()->json([
              'Mutashabihs Table Error' => [$validator_mutashabih->errors()->first()],
             ],404);  

        // check validator errors of Verses pyload
        foreach($validator_verses as $validator_verse){
            if($validator_verse->fails())
                return  response()->json([
                  'Verses Table Error' => [$validator_verse->errors()->first()],
                 ],404);       
        }

        // check validator errors of Words pyload
        foreach($validator_words as $validator_word){
            if($validator_word->fails())
                return  response()->json([
                  'Words Table Error' => [$validator_word->errors()->first()],
                 ],404);       
        }

        // create the mutashabih
        $mutashabih= Mutashabih::create($request->input('mutashabih'));

        // create the verses if they not exists before
        $verses=[];
        foreach($request->input('verses') as $verse_req){
            $verse_check= Verse::where('verse_id', $verse_req['verse_id'])->first();
            if(!$verse_check) {
                $verse = Verse::create($verse_req);
                array_push($verses, $verse);
                $mutashabih->verses()->attach($verse->id);
            }
            else {
               $mutashabih_verse_exists=$mutashabih->verses()->wherePivot('verse_id', $verse_check->id)->exists();
               if(!$mutashabih_verse_exists) $mutashabih->verses()->attach($verse_check->id);
            }
                
        }

        // create the words if they not exists before
        $words=[];
        foreach($request->input('words') as $word_req){
            $word_check= Word::where('word_id', $word_req['word_id'])->first();
            if(!$word_check) {
                $verse = Word::create($word_req);
                array_push($words, $word);
                $mutashabih->words()->attach($word->id);
            }
            else{
               $mutashabih_word_exists=$mutashabih->words()->wherePivot('word_id', $word_check->id)->exists();
               if(!$mutashabih_word_exists) $mutashabih->words()->attach($word_check->id);
            }
        }

        return $mutashabih->toJson(JSON_PRETTY_PRINT,200);
    }

    public function get($id_group,$id=null)
    {
        if ($id){
            $mutashabih=Mutashabih::find($id);

            //check if the mutashabih exist
            if (!$mutashabih) return response()->json([
                'message' => 'This Mutashabih don\'t exist',
                ],404);

            $verses=$mutashabih->verses()->get();
            $words=$mutashabih->words()->get();

            return response()->json(['Mutashabih' => $mutashabih,"Verses" => $verses, "Words" => $words]);
        }

        $mutashabihs = Mutashabih::all()->where("group_id",$id_group);
        
        $response = [];
        foreach($mutashabihs as $mutashabih){
            $verses = $mutashabih->verses()->get();
            $words=$mutashabih->words()->get();

            array_push($response ,['Mutashabih' => $mutashabih,"Verses" => $verses, "Words" => $words]);
        }
        return response()->json($response);
    }

    public function delete ($id_group,$id=null)
    {
        $mutashabih = Mutashabih::find($id);
        if (!$mutashabih)  return response()->json([
                'message' => 'This Mutashabih don\'t exist',
                ],404);
        
        $mutashabih->verses()->detach();
        $mutashabih->words()->detach();
        $mutashabih->delete();
        return response()->json([
                'message' => 'Deleted with success',
            ],200);
    }
}
