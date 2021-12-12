<?php

namespace App\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FindPostCode
{
    private $lon;
    private $lat;
    private bool $valid;
    private Collection $detail;

    public function __construct($long, $lat)
    {
        $this->lon = $long;
        $this->lat = $lat;

        $cache_remember = (int) config('estateagent.cache_remember');

        $response = Cache::remember('nearest_postcodes:'.$this->lon.':'.$this->lat, $cache_remember, function () {
            $http = Http::get(config('estateagent.zip_api.url'), ['lon' => $this->lon, 'lat' => $this->lat]);

            return [
                'status' => $http->status(),
                'data' => $http->collect()->get('result'),
            ];
        });

        $this->valid = $response['status'] === 200;
        $this->detail = collect($response['data']);
    }

    public function nearest()
    {
        return collect($this->detail->first());
    }
}
