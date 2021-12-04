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

        $response = Cache::remember('address:'.$this->zip, (int) env('API_QUERY_REMEMBER'), function () {
            $http = Http::get(env('API_POSTCODES_URL').$this->zip);

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
