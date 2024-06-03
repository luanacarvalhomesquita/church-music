<?php

namespace App\Http\Controllers\Repertoire;

use App\Http\Controllers\Controller;
use App\Repositories\RepertoireRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RepertoireIndexController extends Controller
{
    /**
     * @param RepertoireRepository $repertoires
     * @return void
     */
    public function __construct(
        protected readonly RepertoireRepository $repertoires
    ) {
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $loggedUserId = auth()->id();
        $fields = $request->all();

        $repertoires = $this->repertoires->getAll(
            $loggedUserId,
            $fields['page'],
            $fields['size']
        );

        return $this->responseJson(
            status: Response::HTTP_OK,
            data: $repertoires
        );
    }
}
