<?php


namespace ProjectZero4\RiotApi\Models\Game;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\Pure;
use ProjectZero4\RiotApi\Models\Champion;
use ProjectZero4\RiotApi\RiotApiCollection;
use function ProjectZero4\RiotApi\iconPath;

/**
 * Class Participant
 * @package ProjectZero4\RiotApi\Models
 *
 * @property int assists
 * @property int baronKills
 * @property int bountyLevel
 * @property int champExperience
 * @property int champLevel
 * @property int championId
 * @property string championName
 * @property int championTransform
 * @property int consumablesPurchased
 * @property int damageDealtToBuildings
 * @property int damageDealtToObjectives
 * @property int damageDealtToTurrets
 * @property int damageSelfMitigates
 * @property int deaths
 * @property int detectorWardsPlaced
 * @property int doubleKills
 * @property int dragonKills
 * @property bool firstBloodAssist
 * @property bool firstBloodKill
 * @property bool firstTowerAssist
 * @property bool firstTowerKill
 * @property bool gameEndedInEarlySurrender
 * @property bool gameEndedInSurrender
 * @property int goldEarned
 * @property int goldSpent
 * @property string individualPosition
 * @property int inhibitorKills
 * @property int inhibitorTakedowns
 * @property int inhibitorsLost
 * @property int item0
 * @property int item1
 * @property int item2
 * @property int item3
 * @property int item4
 * @property int item5
 * @property int item6
 * @property int itemsPurchased
 * @property int killingSprees
 * @property int kills
 * @property string lane
 * @property int largestCriticalStrike
 * @property int largestKillingSpree
 * @property int largestMultiKill
 * @property int longestTimeSpentLiving
 * @property int magicDamageDealt
 * @property int magicDamageDealtToChampions
 * @property int magicDamageTaken
 * @property int neutralMinionsKilled
 * @property int nexusKills
 * @property int nexusLost
 * @property int nexusTakedowns
 * @property int objectivesStolen
 * @property int objectivesStolenAssists
 * @property int participantId
 * @property int pentaKills
 * @property int physicalDamageDealt
 * @property int physicalDamageDealtToChampions
 * @property int physicalDamageTaken
 * @property int profileIcon
 * @property string puuid
 * @property int quadraKills
 * @property ?string riotIdName
 * @property ?string riotIdTagline
 * @property string role
 * @property int sightWardsBoughtInGame
 * @property int spell1Casts
 * @property int spell2Casts
 * @property int spell3Casts
 * @property int spell4Casts
 * @property int summoner1Casts
 * @property int summoner1Id
 * @property int summoner2Casts
 * @property int summoner2Id
 * @property string summonerId
 * @property int summonerLevel
 * @property int summonerName
 * @property bool teamEarlySurrendered
 * @property int teamId
 * @property string teamPosition
 * @property int timeCCingOthers
 * @property int timePlayed
 * @property int totalDamageDealt
 * @property int totalDamageDealtToChampions
 * @property int totalDamageShieldedOnTeammates
 * @property int totalDamageTaken
 * @property int totalHeal
 * @property int totalHealsOnTeammates
 * @property int totalMinionsKilled
 * @property int totalTimeCCDealt
 * @property int totalTimeSpentDead
 * @property int totalUnitsHealed
 * @property int tripleKills
 * @property int trueDamageDealt
 * @property int trueDamageTaken
 * @property int turretKills
 * @property int turretTakedowns
 * @property int turretsLost
 * @property int unrealKills
 * @property int visionScore
 * @property int visionWardsBoughtInGame
 * @property int wardsKilled
 * @property int wardsPlaced
 * @property bool win
 * @property int game_id
 *
 * @property-read RiotApiCollection perks
 * @property-read RunePage runePage
 * @property-read Team team
 */
class Participant extends GameBase
{
    protected $fillable = [
        'assists',
        'baronKills',
        'bountyLevel',
        'champExperience',
        'champLevel',
        'championId',
        'championName',
        'championTransform',
        'consumablesPurchased',
        'damageDealtToBuildings',
        'damageDealtToObjectives',
        'damageDealtToTurrets',
        'damageSelfMitigated',
        'deaths',
        'detectorWardsPlaced',
        'doubleKills',
        'dragonKills',
        'firstBloodAssist',
        'firstBloodKill',
        'firstTowerAssist',
        'firstTowerKill',
        'gameEndedInEarlySurrender',
        'gameEndedInSurrender',
        'goldEarned',
        'goldSpent',
        'individualPosition',
        'inhibitorKills',
        'inhibitorTakedowns',
        'inhibitorsLost',
        'item0',
        'item1',
        'item2',
        'item3',
        'item4',
        'item5',
        'item6',
        'itemsPurchased',
        'killingSprees',
        'kills',
        'lane',
        'largestCriticalStrike',
        'largestKillingSpree',
        'largestMultiKill',
        'longestTimeSpentLiving',
        'magicDamageDealt',
        'magicDamageDealtToChampions',
        'magicDamageTaken',
        'neutralMinionsKilled',
        'nexusKills',
        'nexusLost',
        'nexusTakedowns',
        'objectivesStolen',
        'objectivesStolenAssists',
        'participantId',
        'pentaKills',
        'perks',
        'physicalDamageDealt',
        'physicalDamageDealtToChampions',
        'physicalDamageTaken',
        'profileIcon',
        'puuid',
        'quadraKills',
        'riotIdName',
        'riotIdTagline',
        'role',
        'sightWardsBoughtInGame',
        'spell1Casts',
        'spell2Casts',
        'spell3Casts',
        'spell4Casts',
        'summoner1Casts',
        'summoner1Id',
        'summoner2Casts',
        'summoner2Id',
        'summonerId',
        'summonerLevel',
        'summonerName',
        'teamEarlySurrendered',
        'teamId',
        'teamPosition',
        'timeCCingOthers',
        'timePlayed',
        'totalDamageDealt',
        'totalDamageDealtToChampions',
        'totalDamageShieldedOnTeammates',
        'totalDamageTaken',
        'totalHeal',
        'totalHealsOnTeammates',
        'totalMinionsKilled',
        'totalTimeCCDealt',
        'totalTimeSpentDead',
        'totalUnitsHealed',
        'tripleKills',
        'trueDamageDealt',
        'trueDamageTaken',
        'turretKills',
        'turretTakedowns',
        'turretsLost',
        'unrealKills',
        'visionScore',
        'visionWardsBoughtInGame',
        'wardsKilled',
        'wardsPlaced',
        'win',
        'rune_page_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:sP',
        'updated_at' => 'datetime:Y-m-d\TH:i:sP',
    ];

    protected function convertAttributes(array $attributes): array
    {
        unset($attributes['teamId'], $attributes['perks'], $attributes['participantId']);
        return $attributes;
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function runePage(): HasOne
    {
        return $this->hasOne(RunePage::class);
    }

    public function game(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, Team::class);
    }

    public function champion()
    {
        return $this->belongsTo(Champion::class, 'championId', 'key');
    }
}
