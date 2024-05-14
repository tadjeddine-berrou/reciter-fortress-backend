<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Verse extends Model
{
    use HasFactory;
    protected $fillable = ["chapter_id","hizb_number","juz_number","page_number","rub_el_hizb_number","text_imlaei_simple","text_uthmani","verse_id","verse_number"];

    public function mutashabihs(): BelongsToMany
    {
        return $this->belongsToMany(Mutashabih::class);
    }
}
