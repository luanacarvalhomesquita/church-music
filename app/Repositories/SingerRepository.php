<?php

namespace App\Repositories;

use App\Models\Singer;

class SingerRepository
{
    /**
     * @param Singer $singer
     * @return void
     */
    public function __construct(
        protected readonly Singer $singer
    ) {
    }

    public function delete(
        int $singerId,
        int $loggedUserId
    ): void {
        $this->singer
            ->where('user_id', $loggedUserId)
            ->where('id', $singerId)
            ->delete();
    }

    public function update(
        int $singerId,
        string $singerName,
        int $loggedUserId
    ): void {
        $this->singer
            ->where('user_id', $loggedUserId)
            ->where('id', $singerId)
            ->update(['name' => $singerName]);
    }

    public function getById(int $singerId, int $loggedUserId): ?array
    {
        $singer = $this->singer
            ->where('user_id', $loggedUserId)
            ->where('id', $singerId)
            ->first(['id', 'name']);

        if (!$singer) {
            return null;
        }

        /** TODO: buscar o nome do ritmo da musica */
        $musics = $singer->musics()->orderBy('name', 'asc')->get();

        return [
            'singer' => $singer,
            'musics' => $musics,
        ];
    }

    public function getAllForPage(int $loggedUserId, ?int $page, ?int $size): array
    {
        $singers = $this->singer->where('user_id', $loggedUserId)->orderBy('name', 'asc');

        $total = count($singers->get());
        $singers = $singers->forPage($page ?? 1, $size ?? 10)->get(['id', 'name']);

        return [
            'singers' => $singers,
            'total' => $total,
        ];
    }

    public function create(int $loggedUserId, string $singerName): bool
    {
        $isValid = $this->checkUniqueSingerName($loggedUserId, $singerName);

        if (!$isValid) {
            return false;
        }

        $this->singer->create([
            'name' => $singerName,
            'user_id' => $loggedUserId,
        ]);

        return true;
    }

    public function checkUniqueSingerName(
        int $loggedUserId,
        string $singerName,
        int $singerId = null
    ): bool {
        $existSinger = $this->singer
            ->where('name', $singerName)
            ->where('user_id', $loggedUserId)
            ->first();

        if (!$existSinger) {
            return true;
        }

        if ($this->isSelfUpdate($singerId, $existSinger->id)) {
            return true;
        }

        return false;
    }

    private function isSelfUpdate(
        ?int $singerId = null,
        ?int $outherSingerId = null
    ): bool {
        return $singerId && ($outherSingerId === $singerId);
    }
}
