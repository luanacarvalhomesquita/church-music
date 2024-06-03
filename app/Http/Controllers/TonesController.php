<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TonesController extends Controller
{
    /**
     * Display a listing of the tones.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->responseJson(
            status: Response::HTTP_OK,
            data: Music::TONES,
        );
    }
}
