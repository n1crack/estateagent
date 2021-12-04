<?php

namespace App\Utils;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Distance
{
    /**
     * @var Address
     */
    private $address;
    /**
     * @var bool
     */
    private $valid;

    /**
     * @param  Address  $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;

        $response = Cache::remember('distance:'.$address->zip(), (int) env('API_CACHE_REMEMBER'), function () {
            $http = Http::get(env('API_DISTANCE_URL'), [
                'lat1' => env('REAL_ESTATE_LAT'),
                'lng1' => env('REAL_ESTATE_LNG'),
                'lat2' => $this->address->getLatitude(),
                'lng2' => $this->address->getLongitude(),
                'token' => '04',
            ]);

            return [
                'status' => $http->status(),
                'data' => collect($http->collect()->get('paths'))->first(),
            ];
        });

        $this->valid = $response['status'] === 200;
        $this->detail = collect($response['data']);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    public function length()
    {
        return $this->detail->get('distance');
    }

    public function time()
    {
        return $this->detail->get('time');
    }

    public function minDate(CarbonImmutable $date)
    {
        return $date->subMilliseconds($this->time());
    }

    public function maxDate(CarbonImmutable $date)
    {
        return $date->addSeconds((int) env('APPOINTMENT_TIME'))->addMilliseconds($this->time());
    }

}
