<?php

namespace App\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Address
{
    private $valid;
    private $detail;
    private $zip;

    /**
     * @param $zip
     */
    public function __construct($zip)
    {
        $this->zip = str_replace(' ', '', $zip);

        $cache_remember = (int) config('estateagent.cache_remember');

        $response = Cache::remember('address:'.$this->zip,  $cache_remember, function () {
            $http = Http::get(config('estateagent.zip_api.url').$this->zip);

            return [
                'status' => $http->status(),
                'data' => $http->collect()->get('result'),
            ];
        });

        $this->valid = $response['status'] === 200;
        $this->detail = collect($response['data']);
    }

    /**
     * @return string
     */
    public function zip(): string
    {
        return $this->zip;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return Collection
     */
    public function detail(): Collection
    {
        return $this->detail;
    }


    public function getLongitude()
    {
        return $this->detail->get('longitude');
    }

    public function getLatitude()
    {
        return $this->detail->get('latitude');
    }
}
