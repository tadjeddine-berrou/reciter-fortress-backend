<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mutashabih extends Model
{
    use HasFactory;
    protected $fillable = ["chapter_id","chapter_name","code_v1","group_id","order","text_imlaei_simple","text_uthmani"];


    public function verses(): BelongsToMany
    {
        return $this->belongsToMany(Verse::class);
    }

    public function words(): BelongsToMany
    {
        return $this->belongsToMany(Word::class);
    }
}
