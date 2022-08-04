<?php

namespace ProjectZero4\RiotApi\Data;

use Spatie\LaravelData\Data;

class ParticipantAggregate extends Data
{
    public function __construct(
        public ?int $kills,
        public ?int $deaths,
        public ?int $assists,
        public ?int $wins,
        public ?int $games,
    ) {}


    public function kda(): string
    {
        if ($this->deaths === 0) {
            return "Perfect";
        }

        if (!$this->hasData()) {
            return "N/A";
        }

        return $this->format(($this->kills + $this->assists) / $this->deaths);
    }

    public function winRate(): string
    {
        if ($this->games === 0) {
            return "100";
        }

        if (!$this->hasData()) {
            return "N/A";
        }

        return $this->format((100 * $this->wins) / $this->games);
    }

    protected function hasData(): bool
    {
        return ($this->games ?? 0) !== 0;
    }

    public function losses(): ?int
    {
        if (!$this->hasData()) {
            return 0;
        }
        return $this->format($this->games - $this->wins);
    }

    protected function format(float $number, int $precision = 2): string
    {
        return number_format(round($number, $precision), $precision);
    }
}