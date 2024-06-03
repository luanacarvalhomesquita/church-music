<?php

namespace App\Http\Controllers\Repertoire;

use App\Http\Controllers\Controller;
use App\Repositories\RepertoireRepository;
use Illuminate\Http\Response;

class RepertoireDestroyController extends Controller
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

        $this->repertoires->delete(
            $repertoireId,
            $loggedUserId
        );

        return $this->responseJson();
    }
}
