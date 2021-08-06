<?php


namespace App\packages\ProjectZero4\RiotApi\Models;


use Illuminate\Database\Eloquent\Model;
use ProjectZero4\RiotApi\Models\Cacheable;

class Champion extends Model
{
    use Cacheable;

    protected $table = "champions";

}
