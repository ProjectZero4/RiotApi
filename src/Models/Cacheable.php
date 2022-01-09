<?php


namespace ProjectZero4\RiotApi\Models;

use Carbon\Carbon;

/**
 * Trait Cacheable
 * @package ProjectZero4\RiotApi\Models
 * @property-read Carbon updated_at
 */
trait Cacheable
{
    /**
     * @param int $cacheTime
     * @return bool
     */
    public function isOutdated(int $cacheTime = 0): bool
    {
        if (!$this->exists) {
            return true;
        }
        if ($cacheTime === 0) {
            $cacheTime = static::$cacheTime ?? 0;
            if ($cacheTime === 0) {
                return false;
            }
        }
        return $this->updated_at->addSeconds($cacheTime)->isPast();
    }
}
