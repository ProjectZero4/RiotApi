<?php


namespace App\packages\ProjectZero4\RiotApi\Models;


use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;
use ProjectZero4\RiotApi\Models\Cacheable;
use function ProjectZero4\RiotApi\championPath;

/**
 * Class Champion
 * @package App\packages\ProjectZero4\RiotApi\Models
 * @property-read int internalKey
 * @property string id
 * @property int key
 * @property string name
 * @property string title
 * @property object image
 * @property object skins
 * @property string lore
 * @property string blurb
 * @property object allytips
 * @property object enemytips
 * @property string partype
 * @property object info
 * @property object stats
 * @property object spells
 * @property object passive
 */
class Champion extends Model
{
    use Cacheable;

    protected $table = "champions";

    protected $primaryKey = "internalKey";

    protected $guarded = ['internalKey'];

    #[Pure] public function iconUrl(): string
    {
        return championPath("icon/{$this->id}.png");
    }

    #[Pure] public function loadingUrl(int $skinId = 0): string
    {
        return championPath("loading/{$this->id}_{$skinId}.png");
    }
    #[Pure] public function splashUrl(int $skinId = 0): string
    {
        return championPath("splash/{$this->id}_{$skinId}.png");
    }
    #[Pure] public function tileUrl(int $skinId = 0): string
    {
        return championPath("tile/{$this->id}_{$skinId}.png");
    }


}
