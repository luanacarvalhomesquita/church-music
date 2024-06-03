<?php

namespace App\Http\Controllers\Repertoire;

use App\Http\Controllers\Controller;
use App\Repositories\RepertoireRepository;
use Illuminate\Http\Response;

class RepertoireShowController extends Controller
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
     * @param int $repertoireId
     * @return Response
     */
    public function __invoke(int $repertoireId)
    {
        $loggedUserId = auth()->id();

        $repertoire = $this->repertoires->getById(
            $repertoireId,
            $loggedUserId
        );

        if (!$repertoire) {
            return $this->responseJson(
                message: 'This repertoire not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return $this->responseJson(
            data: $repertoire,
            status: Response::HTTP_OK
        );
    }
}
