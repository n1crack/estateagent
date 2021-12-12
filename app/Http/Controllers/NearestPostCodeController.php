<?php

namespace App\Http\Controllers;

use App\Utils\FindPostCode;
use Illuminate\Http\Request;

class NearestPostCodeController extends Controller
{

    public function __invoke(Request $request)
    {
        $lon = $request->get('lon');
        $lat = $request->get('lat');
        $findPostCodes = new FindPostCode($lon, $lat);

        return $findPostCodes->nearest();
    }
}
