<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Http\Requests\Type\TypeRequest;
use App\Repositories\TypeRepository;
use Illuminate\Http\Response;

class TypeUpdateController extends Controller
{
    public function __construct(protected readonly TypeRepository $types)
    {
    }

    public function __invoke(TypeRequest $request, int $typeId)
    {
        $loggedUserId = auth()->id();

        $isUpdated = $this->types->update($loggedUserId, $typeId, $request->name);

        if (!$isUpdated) {
            return $this->responseJson(
                message: trans('response-errors.duplicate_name'),
                status: Response::HTTP_CONFLICT
            );
        }

        return $this->responseJson();
    }
}
