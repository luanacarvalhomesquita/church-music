<?php

namespace App\Http\Controllers\Repertoire;

use App\Http\Controllers\Controller;
use App\Http\Requests\Repertoire\RepertoireRequest;
use App\Repositories\RepertoireRepository;
use Illuminate\Http\Response;

class RepertoireUpdateController extends Controller
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
     * @param RepertoireRequest $request
     * @param int $repertoireId
     * @return Response
     */
    public function __invoke(RepertoireRequest $request, int $repertoireId)
    {
        $loggedUserId = auth()->id();
        $fields = $request->all();

        $validNameAndTitle = $this->repertoires->checkUniqueDateAndTitle(
            $loggedUserId,
            $request['title'],
            $request['date'],
            $repertoireId
        );

        if (!$validNameAndTitle) {
            return $this->responseJson(
                message: 'This title with this date already exists.',
                status: Response::HTTP_CONFLICT
            );
        }

        $this->repertoires->update(
            $loggedUserId,
            $repertoireId,
            $fields['title'],
            $fields['date']
        );

        return $this->responseJson();
    }
}
