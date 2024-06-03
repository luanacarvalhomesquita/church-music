<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseJson(
        ?string $message = '',
        ?int $status = Response::HTTP_NO_CONTENT,
        ?array $data = []
    ): JsonResponse {
        $data = [
            'message' => $message,
            'code' => $status,
            'data' => $data,
        ];

        return response()->json(
            data: $data,
            status: $status,
        );
    }
}
