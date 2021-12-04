<?php


namespace ProjectZero4\RiotApi\Exceptions;


use Exception;

class RateLimitException extends Exception
{
    public int $waitTime;

}
