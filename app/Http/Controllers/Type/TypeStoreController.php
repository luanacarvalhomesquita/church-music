<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Http\Requests\Type\TypeRequest;
use App\Repositories\TypeRepository;
use Illuminate\Http\Response;

class TypeStoreController extends Controller
{
    public function __construct(
        protected readonly TypeRepository $types
    ) {
    }

    public function __invoke(TypeRequest $request)
    {
        $loggedUserId = auth()->id();
        $typeName = $request->all()['name'];

        $created = $this->types->create(
            $loggedUserId,
            $typeName
        );

        if (!$created) {
            return $this->responseJson(
                status: Response::HTTP_CONFLICT,
                message: trans('response-errors.duplicate_name')
            );
        }

        return $this->responseJson(status: Response::HTTP_CREATED);
    }
}
