<?php

namespace App\Http\Controllers;

use App\Models\MutashabihsGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Mutashabih;
use App\Models\Verse;
use App\Models\Word;

//        $validated = $request->validate([
//            'chapter_id' => 'required|integer',
//            'chapter_name'=> 'required|string|max:100',
//            'code_v1'=> 'required|string|max:100',
//            'order'  => 'required|integer',
//            'text_imlaei_simple'  => 'required|string',
//            'text_uthmani' => 'required|string',
//            'verses.*' => [
//                'chapter_id' => 'required|integer',
//                'hizb_number' => 'required|integer',
//                'juz_number' => 'required|integer',
//                'page_number' => 'required|integer',
//                'rub_el_hizb_number' => 'required|integer',
//                'verse_id' => 'required|integer',
//                'verse_number' => 'required|integer',
//                'text_imlaei_simple' => 'required|string',
//                'text_uthmani' => 'required|string',
//            ],
//            'words.*' => [
//                'chapter_id' => 'required|integer',
//                'char_type_name' => 'required|string',
//                'code_v1' => 'required|string|max:20',
//                'page_number' => 'required|integer',
//                'line_number' => 'required|integer',
//                'location' => 'required|string|max:20',
//                'position' => 'required|integer',
//                'offset_right_percent' => 'required|numeric',
//                'verse_id' => 'required|integer',
//                'verse_number' => 'required|integer',
//                'text' => 'required|string',
//                'text_uthmani' => 'required|string',
//                'word_id' => 'required|integer',
//                'width_percent' => 'required|numeric',
//            ],
//        ]);

class MutashabihController extends Controller
{
    public function create (MutashabihsGroup $group, Request $request)
    {
        //Validate the Request
        $validated = $request->validate([
            'chapter_id' => 'required|integer',
            'chapter_name' => 'required|string|max:100',
            'code_v1' => 'required|string|max:100',
            'order' => 'required|integer',
            'text_imlaei_simple' => 'required|string',
            'text_uthmani' => 'required|string',
            'verses.*.chapter_id' => 'required|integer',
            'verses.*.hizb_number' => 'required|integer',
            'verses.*.juz_number' => 'required|integer',
            'verses.*.page_number' => 'required|integer',
            'verses.*.rub_el_hizb_number' => 'required|integer',
            'verses.*.verse_id' => 'required|integer',
            'verses.*.verse_number' => 'required|integer',
            'verses.*.text_imlaei_simple' => 'required|string',
            'verses.*.text_uthmani' => 'required|string',
            'words.*.chapter_id' => 'required|integer',
            'words.*.char_type_name' => 'required|string',
            'words.*.code_v1' => 'required|string|max:20',
            'words.*.page_number' => 'required|integer',
            'words.*.line_number' => 'required|integer',
            'words.*.location' => 'required|string|max:20',
            'words.*.position' => 'required|integer',
            'words.*.offset_right_percent' => 'required|numeric',
            'words.*.verse_id' => 'required|integer',
            'words.*.verse_number' => 'required|integer',
            'words.*.text' => 'required|string',
            'words.*.text_uthmani' => 'required|string',
            'words.*.word_id' => 'required|integer',
            'words.*.width_percent' => 'required|numeric',
        ]);

        DB::beginTransaction();
        // create the mutashabih
        /**
         * @var $mutashabih Mutashabih
         */
        $mutashabih=  $group->mutashabih()->create([
            'chapter_id' => $validated['chapter_id'],
            'chapter_name'=> $validated['chapter_name'],
            'code_v1'=> $validated['code_v1'],
            'order'  => $validated['order'],
            'text_imlaei_simple'  => $validated['text_imlaei_simple'],
            'text_uthmani' => $validated['text_uthmani'],
        ]);

        // create the verses if they not exists before

        $verses = collect($validated['verses'])->map(fn($verseData) => Verse::query()->firstOrCreate([ 'verse_id' => $verseData['verse_id']], $verseData));
        $mutashabih->verses()->sync($verses->pluck('id'));

        $words = collect($validated['words'])
            ->map(
                fn($wordData) => Word::query()
                    ->firstOrCreate([
                        'word_id' => $wordData['word_id']
                    ], $wordData)
            );

        $mutashabih->words()->sync($words->pluck('id'));
        DB::commit();
        return $mutashabih->toJson(JSON_PRETTY_PRINT,200);
    }

    public function get(Mutashabih $mutashabih)
    {
        $mutashabih->load(['verses', 'words']);
        return response()->json([
            'data' => $mutashabih,
        ]);
    }

    public function getAll(MutashabihsGroup $group)
    {
        $mutashabihs = $group->mutashabih()->get();

        $response = [];
        foreach($mutashabihs as $mutashabih){
            $mutashabihs->load(['verses', 'words']);

            //$verses = $mutashabih->verses()->get();
            //$words=$mutashabih->words()->get();

            $response[] = $mutashabih;
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
