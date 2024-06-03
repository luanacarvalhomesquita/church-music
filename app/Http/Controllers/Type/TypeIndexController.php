<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Http\Requests\Type\TypePaginationRequest;
use App\Repositories\TypeRepository;
use Illuminate\Http\Response;

class TypeIndexController extends Controller
{
    public function __construct(protected readonly TypeRepository $types)
    {
    }

    public function __invoke(TypePaginationRequest $request)
    {
        $loggedUserId = auth()->id();
        $pagination = $request->all();
   
        $types = $this->types->getAllForPage(
            $loggedUserId,
            $pagination['page'],
            $pagination['size']
        );

        return $this->responseJson(status: Response::HTTP_OK, data: $types);
    }
}
