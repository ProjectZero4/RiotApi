<?php

namespace ProjectZero4\RiotApi\Endpoints;


use ProjectZero4\RiotApi\Endpoints\Endpoint;

class Status extends Endpoint
{
    const CURRENT_VERSION = 'v4';

    const ENDPOINT = 'lol/status/{version}/platform-data';

    protected int $cacheTime = 60;

    public function getStatus()
    {
        return $this->sendRequest($this->buildUrl(''))['maintenances'];
    }
}
