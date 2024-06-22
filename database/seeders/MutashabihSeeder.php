<?php

namespace Database\Seeders;

use App\Models\Mutashabih;
use App\Models\MutashabihsGroup;
use App\Models\Verse;
use App\Models\Word;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class MutashabihSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = require_once __DIR__."/dataset/mutashabih-groups.php";
        $mutashabihs = require_once __DIR__."/dataset/mutashabihs.php";
        $verses = require_once __DIR__."/dataset/verses.php";
        $words = require_once __DIR__."/dataset/words.php";

        echo "[+] Words...\n";
        $mappedWords = collect($words)->mapWithKeys(fn(array $data): array => [
            $data['id'] => Word::query()->firstOrCreate(['word_id' => $data['word_id'], ], collect($data)->except('id')->toArray()),
        ]);

        echo "[+] Verses...\n";
        $mappedVerses = collect($verses)->mapWithKeys(fn(array $data): array => [
            $data['id'] => Verse::query()->firstOrCreate(['verse_id' =>$data['verse_id'], ], collect($data)->except('id')->toArray()),
        ]);

        echo "[+] Groups...\n";
        $mappedGroups = collect($groups)->mapWithKeys(fn($data): array => [
            $data['id'] => MutashabihsGroup::query()->firstOrCreate(['name' => $data['name'], ]),
        ]);

        echo "[+] Mutashabihs...\n";
        collect($mutashabihs)->each(fn(array $data) => $this->createMutashabih($data, $mappedGroups, $mappedVerses, $mappedWords));
    }

    private function createMutashabih(array $data, Collection $mappedGroups, Collection $mappedVerses, Collection $mappedWords): void
    {
        /**
         * @var $mushabihsGroup MutashabihsGroup
         */
        $mushabihsGroup = $mappedGroups[$data['groupId']];

        /**
         * @var $mushabih Mutashabih
         */
        $mushabih = $mushabihsGroup
                ->mutashabih()
                ->create(
                    collect($data)
                        ->except(['id', 'groupId', 'words_ids', 'verses_ids'])
                        ->toArray()
                );
        $mushabih->words()->sync($mappedWords->only($data['words_ids'])->pluck('id'));
        $mushabih->verses()->sync($mappedVerses->only($data['verses_ids'])->pluck('id'));
    }
}
