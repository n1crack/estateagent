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
                'data' => collect($http->collect()->get('result')),
            ];
        });

        $this->valid = $response['status'] === 200;
        $this->detail = collect($response['data'][0]);



        $address = new Address;
        $address->valid =  true;
        $address->zip = $this->detail->get('postcode');
        $address->detail = collect([
            'latitude' => $this->detail->get('latitude'),
            'longitude' => $this->detail->get('longitude'),
        ]);

        $this->detail['appointment'] = Distance::detail($address);
    }

    public function nearest()
    {
        return $this->detail;
    }
}
