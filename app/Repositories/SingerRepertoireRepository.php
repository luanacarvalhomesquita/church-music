<?php

namespace App\Repositories;

use App\Models\Singer;
use App\Models\SingerRepertoire;
use Illuminate\Database\Eloquent\Collection;

class SingerRepertoireRepository
{
    /**
     * @param SingerRepertoire $singerRepertoire
     * @param Singer $singers
     * @return void
     */
    public function __construct(
        protected readonly SingerRepertoire $singerRepertoire,
        protected readonly Singer $singers
    ) {
    }

    public function bindSingers(array $receivedSingers, int $repertoireId): bool
    {
        if (!$receivedSingers) {
            return true;
        }

        $foundNamesOfSingers = $this->getNamesOfSingers($receivedSingers);

        if (!$foundNamesOfSingers) {
            return false;
        }

        foreach ($receivedSingers as $singer) {
            $this->singerRepertoire->firstOrCreate([
                'repertoire_id' => $repertoireId,
                'singer_id' => $singer['id'],
                'singer_name' => $foundNamesOfSingers[$singer['id']],
            ]);
        }

        return true;
    }

    private function getNamesOfSingers(array $receivedSingers): array
    {
        $receivedSingersId = array_filter(array_column($receivedSingers, 'id'));

        $foundSingers = $this->singers
            ->whereIn('id', $receivedSingersId)
            ->get(['id', 'name']);

        return $this->getFormattedNamesWithKeyMusicId($foundSingers);
    }

    private function getFormattedNamesWithKeyMusicId(Collection $singers): array
    {
        $formatedNamesWithKeyMusicId = [];

        foreach ($singers as $singer) {
            $formatedNamesWithKeyMusicId[$singer->id] = $singer->name;
        }

        return $formatedNamesWithKeyMusicId;
    }
}
