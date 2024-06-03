<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Repositories\TypeRepository;

class TypeDestroyController extends Controller
{
    public function __construct(protected readonly TypeRepository $types)
    {
    }

    public function __invoke(int $typeId)
    {
        $loggedUserId = auth()->id();

        $this->types->delete($typeId, $loggedUserId);

        return $this->responseJson();
    }
}
