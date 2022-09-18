<?php

namespace ProjectZero4\RiotApi\Collections;

use ProjectZero4\RiotApi\RiotApiCollection;

class ParticipantCollection extends RiotApiCollection
{
    public function teams(): ParticipantCollection
    {
        return $this->groupBy('teamId');
    }
}