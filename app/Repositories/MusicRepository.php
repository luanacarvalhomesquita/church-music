<?php

namespace App\Repositories;

use App\Models\Music;
use App\Models\Singer;

class MusicRepository
{
    public function __construct(
        protected readonly Music $music,
        protected readonly Singer $singer
    ) {
    }

    public function delete(int $musicId, int $loggedUserId): void
    {
        $this->music->where('user_id', $loggedUserId)->where('id', $musicId)->delete();
    }

    public function update(
        int $loggedUserId,
        int $musicId,
        string $musicName,
        ?string $description = null,
        ?string $mainVersion = null,
        ?bool $played = true,
        ?int $typeId = null
    ): void {
        $this->music
            ->where('user_id', $loggedUserId)
            ->where('id', $musicId)
            ->update([
                'name' => $musicName,
                'description' => $description,
                'main_version' => $mainVersion,
                'played' => $played,
                'type_id' => $typeId,
            ]);
    }

    public function getById(int $musicId, int $loggedUserId): ?array
    {
        $music = $this->music
            ->leftJoin('types', 'music.type_id', '=', 'types.id')
            ->where('music.user_id', $loggedUserId)
            ->where('music.id', $musicId)
            ->first([
                'music.id as music_id',
                'music.name as music_name',
                'music.main_version',
                'music.description',
                'music.played',
                'types.name as type_name',
                'music.type_id',
            ]);

        if (!$music) {
            return null;
        }

        return ['music' => $music];
    }

    public function getAll(
        int $loggedUserId,
        int $page,
        int $size,
        ?string $name,
        ?int $typeId,
    ): array {
        $musics = $this->music
            ->leftJoin('types', 'music.type_id', '=', 'types.id')
            ->where('music.user_id', $loggedUserId);

        if ($name) {
            $musics = $this->music->where('music.name', 'LIKE', "%{$name}%");
        }

        if ($typeId) {
            $musics = $this->music->where('types.id', 'LIKE', "%{$typeId}%");
        }

        $total = count($musics->get());
        $musics = $musics
            ->orderBy('music.created_at', 'DESC')
            ->forPage($page, $size)
            ->get([
                'music.id as music_id',
                'music.name as music_name',
                'music.description',
                'music.main_version',
                'types.name as type_name',
                'music.played',
            ]);

        return [
            'musics' => $musics,
            'total' => $total,
        ];
    }

    public function create(
        int $loggedUserId,
        string $musicName,
        ?string $description = null,
        ?string $mainVersion = null,
        ?bool $played = true,
        ?int $typeId = null
    ): ?int {
        $isValid = $this->checkUniqueMusicName($loggedUserId, $musicName);

        if (!$isValid) {
            return null;
        }

        $music = $this->music->create([
            'name' => $musicName,
            'user_id' => $loggedUserId,
            'description' => $description,
            'main_version' => $mainVersion,
            'played' => $played,
            'type_id' => $typeId,
        ]);

        return $music->id;
    }

    public function checkUniqueMusicName(
        int $loggedUserId,
        string $musicName,
        int $musicId = null
    ): bool {
        $existMusic = $this->music
            ->where('name', $musicName)
            ->where('user_id', $loggedUserId)
            ->first();

        if (!$existMusic) {
            return true;
        }

        if ($this->isSelfUpdate($musicId, $existMusic->id)) {
            return true;
        }

        return false;
    }

    private function isSelfUpdate(?int $musicId = null, ?int $outherMusicId = null): bool
    {
        return $musicId && ($outherMusicId === $musicId);
    }
}
