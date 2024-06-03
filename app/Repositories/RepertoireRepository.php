<?php

namespace App\Repositories;

use App\Models\Repertoire;

class RepertoireRepository
{
    public function __construct(
        protected readonly Repertoire $repertoire
    ) {
    }

    public function delete(
        int $repertoireId,
        int $loggedUserId
    ): void {
        $this->repertoire
            ->where('user_id', $loggedUserId)
            ->where('id', $repertoireId)
            ->delete();
    }

    public function update(
        int $loggedUserId,
        int $repertoireId,
        string $title,
        string $date
    ): void {
        $this->repertoire
            ->where('user_id', $loggedUserId)
            ->where('id', $repertoireId)
            ->update([
                'user_id' => $loggedUserId,
                'title' => $title,
                'date' => $date,
            ]);
    }

    public function getById(int $repertoireId, int $loggedUserId): ?array
    {
        $repertoire = $this->repertoire
            ->where('user_id', $loggedUserId)
            ->where('id', $repertoireId)
            ->first(['id', 'title', 'date']);

        if (!$repertoire) {
            return null;
        }

        $musics = $repertoire->musics()->get();
        $singers = $repertoire->singers()->get();

        return [
            'repertoire' => $repertoire,
            'musics' => $musics,
            'singers' => $singers,
        ];
    }

    public function getAll(
        int $loggedUserId,
        ?int $page = 1,
        ?int $size = 10
    ): array {
        $repertoires = $this->repertoire
            ->where('user_id', $loggedUserId)
            ->orderBy('date', 'asc')
            ->orderBy('title', 'asc');

        $total = count($repertoires->get());
        $repertoires = $repertoires
            ->forPage($page, $size)
            ->get([
                'id',
                'title',
                'date',
            ]);

        $formattedRepertoires = $this->formatRepertoireWithSingersAndMuscic(
            $repertoires
        );

        return [
            'repertoires' => $formattedRepertoires,
            'page' => $page,
            'size' => $size,
            'total' => $total,
        ];
    }

    private function formatRepertoireWithSingersAndMuscic($repertoires)
    {
        $formattedRepertoires = [];

        foreach ($repertoires as $repertoire) {
            $formattedRepertoires[] = array_merge(
                json_decode(json_encode($repertoire), true),
                ['musics' => $repertoire->musics()->get()],
                ['singers' => $repertoire->singers()->get()],
            );
        }

        return $formattedRepertoires;
    }

    public function create(
        int $loggedUserId,
        string $title,
        string $date
    ): ?int {
        $isValid = $this->checkUniqueDateAndTitle(
            $loggedUserId,
            $title,
            $date
        );

        if (!$isValid) {
            return null;
        }

        $repertoire = $this->repertoire->create([
            'user_id' => $loggedUserId,
            'title' => $title,
            'date' => $date,
        ]);

        return $repertoire->id;
    }

    public function checkUniqueDateAndTitle(
        int $loggedUserId,
        string $title,
        string $date,
        ?int $repertoireId = null
    ): bool {
        $existRepertoire = $this->repertoire
            ->where('user_id', $loggedUserId)
            ->where('date', $date)
            ->where('title', $title)
            ->first();

        if (!$existRepertoire) {
            return true;
        }

        if ($this->isSelfUpdate($repertoireId, $existRepertoire->id)) {
            return true;
        }

        return false;
    }

    private function isSelfUpdate(
        ?int $repertoireId = null,
        ?int $outherRepertoireId = null
    ): bool {
        return $repertoireId && ($outherRepertoireId === $repertoireId);
    }
}
