<?php

namespace App\Repositories;

use App\Models\Music;
use App\Models\Singer;
use App\Models\SingerMusic;

class SingerMusicRepository
{
    /**
     * @param SingerMusic $singerMusic
     * @param Music $music
     * @param Singer $singer
     * 
     * @return void
     */
    public function __construct(
        protected readonly SingerMusic $singerMusic,
        protected readonly Music $music,
        protected readonly Singer $singer
    ) {
    }

    /**
     * @param int $loggedUserId
     * @param int $musicId
     * @param array $singers
     * 
     * @return void
     */
    public function storeAll(
        int $loggedUserId,
        int $musicId,
        array $singers
    ): void {
        
        foreach ($singers as $singer) {
            $this->store(
                $loggedUserId,
                $musicId,
                $singer['singer_id'],
                $singer['singer_tone']
            );
        }
    }

    /**
     * @param int $loggedUserId
     * @param int $musicId
     * @param int $singerId
     * @param string $musicTone
     * 
     * @return void
     */
    private function store(
        int $loggedUserId,
        int $musicId,
        int $singerId,
        ?string $musicTone = null
    ): void {
        $isValid = $this->checkSingerFromUser($loggedUserId, $singerId);

        if (!$isValid) {
            return;
        }

        $this->singerMusic->create([
            'user_id' => $loggedUserId,
            'music_id' => $musicId,
            'singer_id' => $singerId,
            'tone' => $musicTone,
        ]);
    }

    /**
     * @param int $loggedUserId
     * @param int $singerId
     * 
     * @return bool
     */
    private function checkSingerFromUser(
        int $loggedUserId,
        int $singerId
    ): bool {
        $singerMusic = $this->singer
            ->where('user_id', $loggedUserId)
            ->where('id', $singerId)
            ->first(['id']);

        if (!$singerMusic) {
            return false;
        }

        return true;
    }
}
