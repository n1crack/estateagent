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
        $cache_remember = (int) config('estateagent.cache_remember');

        $response = Cache::remember('distance:'.$address->zip(), $cache_remember, function () {
            $distance_api_url = config('estateagent.distance_api.url').'?key='.config('estateagent.distance_api.key');

            $http = Http::post($distance_api_url, [
                'points' => [
                    // estate agent lng and lat
                    [
                        config('estateagent.lng'),
                        config('estateagent.lat'),
                    ],
                    // appointment lng and lat
                    [
                        $this->address->getLongitude(),
                        $this->address->getLatitude(),
                    ],
                ],
                'calc_points' => false,
                'instructions' => false,
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
        $appointment_time = (int) config('estateagent.appointment_time');

        return $date->addSeconds($appointment_time)->addMilliseconds($this->time());
    }

}
