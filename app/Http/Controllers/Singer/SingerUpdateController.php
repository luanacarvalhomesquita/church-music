<?php

namespace App\Http\Controllers\Singer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Singer\SingerRequest;
use App\Repositories\SingerRepository;
use Illuminate\Http\Response;

class SingerUpdateController extends Controller
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
     * @param SingerRequest  $request
     * @param int $singerId
     * @return Response
     */
    public function __invoke(SingerRequest $request, int $singerId)
    {
        $loggedUserId = auth()->id();

        $validName = $this->singers->checkUniqueSingerName(
            $loggedUserId,
            $request->name,
            $singerId
        );

        if (!$validName) {
            return $this->responseJson(
                message: 'This name already exists.',
                status: Response::HTTP_CONFLICT
            );
        }

        $this->singers->update(
            $singerId,
            $request->name,
            $loggedUserId
        );

        return $this->responseJson();
    }
}
