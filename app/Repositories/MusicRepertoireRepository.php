<?php

namespace App\Repositories;

use App\Models\Music;
use App\Models\MusicRepertoire;
use Illuminate\Database\Eloquent\Collection;

class MusicRepertoireRepository
{
    /**
     * @param MusicRepertoire $musicRepertoire
     * @param Music $musics
     * @return void
     */
    public function __construct(
        protected readonly MusicRepertoire $musicRepertoire,
        protected readonly Music $musics
    ) {
    }

    public function bindMusics(array $receivedMusics, int $repertoireId): bool
    {
        if (!$receivedMusics) {
            return true;
        }

        $foundNamesOfMusics = $this->getNamesOfMusics($receivedMusics);

        if (!$foundNamesOfMusics) {
            return false;
        }

        foreach ($receivedMusics as $music) {
            $this->musicRepertoire->firstOrCreate([
                'repertoire_id' => $repertoireId,
                'music_id' => $music['id'],
                'music_name' => $foundNamesOfMusics[$music['id']],
                'tone' => $music['tone'] ?? null,
            ]);
        }

        return true;
    }

    private function getNamesOfMusics(array $receivedMusics): array
    {
        $receivedMusicsId = array_filter(array_column($receivedMusics, 'id'));

        $foundMusics = $this->musics
            ->whereIn('id', $receivedMusicsId)
            ->get(['id', 'name']);

        return $this->getFormattedNamesWithKeyMusicId($foundMusics);
    }

    private function getFormattedNamesWithKeyMusicId(Collection $musics): array
    {
        $formatedNamesWithKeyMusicId = [];

        foreach ($musics as $music) {
            $formatedNamesWithKeyMusicId[$music->id] = $music->name;
        }

        return $formatedNamesWithKeyMusicId;
    }
}
