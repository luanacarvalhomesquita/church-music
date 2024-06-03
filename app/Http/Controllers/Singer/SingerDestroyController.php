<?php

namespace App\Http\Controllers\Singer;

use App\Http\Controllers\Controller;
use App\Repositories\SingerRepository;
use Illuminate\Http\Response;

class SingerDestroyController extends Controller
{
    /**
     * @param SingerRepository $singers
     * @return void
     */
    public function __construct(
        protected readonly SingerRepository $singers
    ) {
    }

    /**
     * @param int $singerId
     * @return Response
     */
    public function __invoke(int $singerId)
    {
        $loggedUserId = auth()->id();

        $this->singers->delete(
            $singerId,
            $loggedUserId
        );

        return $this->responseJson();
    }
}
