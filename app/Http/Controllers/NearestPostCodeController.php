<?php

namespace App\Http\Controllers;

use App\Utils\FindPostCode;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NearestPostCodeController extends Controller
{

    public function __invoke(Request $request)
    {
        $lon = $request->get('lon');
        $lat = $request->get('lat');
        try {
            $findPostCodes = new FindPostCode($lon, $lat);
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json(['error' => 'Cannot find the location.'], Response::HTTP_BAD_REQUEST));
        }

        return $findPostCodes->nearest();
    }
}
