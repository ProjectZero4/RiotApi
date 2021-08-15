<?php

namespace App\packages\ProjectZero4\RiotApi;

use Illuminate\Support\Collection;
use ProjectZero4\RiotApi\Models\Cacheable;

class RiotApiCollection extends Collection
{


    public function isOutdated(): bool
    {
        $renewCache = false;
        if ($this->isEmpty()) {
            $renewCache = true;
        }
        foreach ($this as $cacheable) {
            /**
             * @var Cacheable $cacheable
             */
            if ($cacheable->isOutdated()) {
                $renewCache = true;
                break;
            }
        }
        return $renewCache;
    }
}
