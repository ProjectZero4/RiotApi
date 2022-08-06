<?php


namespace ProjectZero4\RiotApi\Models;


use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;
use function ProjectZero4\RiotApi\championPath;
use function ProjectZero4\RiotApi\riotApi;

/**
 * Class Champion
 * @package ProjectZero4\RiotApi\Models
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
class Champion extends Base
{
    use Cacheable;

    protected $table = "champions";

    protected $primaryKey = "internalKey";

    protected $guarded = ['internalKey'];

    protected $appends = [
        'defaultIconUrl',
        'defaultLoadingUrl',
        'defaultSplashUrl',
        'defaultTileUrl',
    ];

    #[Pure] public function getDefaultIconUrlAttribute(): string
    {
        return $this->iconUrl();
    }

    #[Pure] public function iconUrl(): string
    {
        $version = riotApi()->getCurrentPatch();
        return "https://ddragon.leagueoflegends.com/cdn/$version/img/champion/$this->id.png";
    }

    #[Pure] public function getDefaultLoadingUrlAttribute(): string
    {
        return $this->loadingUrl();
    }

    #[Pure] public function loadingUrl(int $skinId = 0): string
    {
        return championPath("loading/{$this->id}_$skinId.jpg");
    }

    #[Pure] public function getDefaultSplashUrlAttribute(): string
    {
        return $this->splashUrl();
    }
    #[Pure] public function splashUrl(int $skinId = 0): string
    {
        return championPath("splash/{$this->id}_$skinId.jpg");
    }

    #[Pure] public function getDefaultTileUrlAttribute(): string
    {
        return $this->tileUrl();
    }

    #[Pure] public function tileUrl(int $skinId = 0): string
    {
        return championPath("tile/{$this->id}_{$skinId}.jpg");
    }
}
