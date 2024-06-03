<?php

namespace App\Repositories;

use App\Models\Type;

class TypeRepository
{
    public function __construct(protected readonly Type $types)
    {
    }

    public function delete(int $typeId, int $loggedUserId): void
    {
        $this->types->where('user_id', $loggedUserId)->where('id', $typeId)->delete();
    }

    public function update(int $loggedUserId, int $typeId, string $typeName): bool
    {
        $validName = $this->checkUniqueTypeName($loggedUserId, $typeName, $typeId);

        if (!$validName) {
            return false;
        }

        $this->types
            ->where('user_id', $loggedUserId)
            ->where('id', $typeId)
            ->update(['name' => $typeName]);

        return true;
    }

    public function getAllForPage(int $loggedUserId, int $page, int $size): array
    {
        $types =  $this->types->where('user_id', $loggedUserId)->orderBy('name', 'asc');

        $total = count($types->get());
        $types = $types->forPage($page, $size)->get(['id', 'name']);
        
        return [
            'types' => $types,
            'total' => $total,
        ];
    }

    public function create(int $loggedUserId, string $typeName): bool
    {
        $isValid = $this->checkUniqueTypeName($loggedUserId, $typeName);

        if (!$isValid) {
            return false;
        }

        $this->types->create([
            'name' => $typeName,
            'user_id' => $loggedUserId,
        ]);

        return true;
    }

    public function checkType(int $loggedUserId, ?int $typeId = null): bool
    {
        if (!$typeId) {
            return true;
        }

        $hasType = $this->getById($typeId, $loggedUserId);

        return $hasType ? true : false;
    }
    
    private function getById(int $typeId, int $loggedUserId): ?array
    {
        $type = $this->types
            ->where('user_id', $loggedUserId)
            ->where('id', $typeId)
            ->first();

        if (!$type) {
            return null;
        }

        return ['type' => $type];
    }

    private function checkUniqueTypeName(int $loggedUserId, string $typeName, int $typeId = null): bool
    {
        $existType = $this->types->where('name', $typeName)->where('user_id', $loggedUserId)->first();

        if (!$existType) {
            return true;
        }

        if ($this->isSelfUpdate($typeId, $existType->id)) {
            return true;
        }

        return false;
    }

    private function isSelfUpdate(?int $typeId = null, ?int $outhertypeId = null): bool
    {
        return $typeId && ($outhertypeId === $typeId);
    }
}
