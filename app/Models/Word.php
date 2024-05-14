<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Word extends Model
{
    use HasFactory;
    protected $fillable = ["chapter_id","char_type_name","code_v1","line_number",'location',"offset_right_percent","page_number","position","text","text_uthmani","verse_id","verse_number","width_percent","word_id"];

    public function mutashabihs(): BelongsToMany
    {
        return $this->belongsToMany(Mutashabih::class);
    }

}
