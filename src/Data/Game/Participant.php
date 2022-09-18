<?php

namespace ProjectZero4\RiotApi\Data\Game;

use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\Pure;
use ProjectZero4\RiotApi\Data\Spell;
use ProjectZero4\RiotApi\Models\Champion;
use ProjectZero4\RiotApi\Models\Summoner;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

use function ProjectZero4\RiotApi\iconPath;

class Participant extends Data
{
    public function __construct(
        public int $id,
        public int $assists,
        public int $baronKills,
        public int $bountyLevel,
        public int $champExperience,
        public int $champLevel,
        public int $championId,
        public string $championName,
        public int $championTransform,
        public int $consumablesPurchased,
        public int $damageDealtToBuildings,
        public int $damageDealtToObjectives,
        public int $damageDealtToTurrets,
        public int $damageSelfMitigated,
        public int $deaths,
        public int $detectorWardsPlaced,
        public int $doubleKills,
        public int $dragonKills,
        public int $firstBloodAssist,
        public int $firstBloodKill,
        public int $firstTowerAssist,
        public int $firstTowerKill,
        public int $gameEndedInEarlySurrender,
        public int $gameEndedInSurrender,
        public int $goldEarned,
        public int $goldSpent,
        public string $individualPosition,
        public int $inhibitorKills,
        public int $inhibitorTakedowns,
        public int $inhibitorsLost,
        public int $item0,
        public int $item1,
        public int $item2,
        public int $item3,
        public int $item4,
        public int $item5,
        public int $item6,
        public int $itemsPurchased,
        public int $killingSprees,
        public int $kills,
        public string $lane,
        public int $largestCriticalStrike,
        public int $largestKillingSpree,
        public int $largestMultiKill,
        public int $longestTimeSpentLiving,
        public int $magicDamageDealt,
        public int $magicDamageDealtToChampions,
        public int $magicDamageTaken,
        public int $neutralMinionsKilled,
        public int $nexusKills,
        public int $nexusLost,
        public int $nexusTakedowns,
        public int $objectivesStolen,
        public int $objectivesStolenAssists,
        public int $pentaKills,
        public int $physicalDamageDealt,
        public int $physicalDamageDealtToChampions,
        public int $physicalDamageTaken,
        public int $profileIcon,
        public string $puuid,
        public int $quadraKills,
        public string $riotIdName,
        public string $riotIdTagLine,
        public string $role,
        public int $sightWardsBoughtInGame,
        public int $spell1Casts,
        public int $spell2Casts,
        public int $spell3Casts,
        public int $spell4Casts,
        public int $summoner1Casts,
        public int $summoner1Id,
        public int $summoner2Casts,
        public int $summoner2Id,
        public string $summonerId,
        public int $summonerLevel,
        public string $summonerName,
        public int $teamEarlySurrendered,
        public int $team_id,
        public string $teamPosition,
        public int $timeCCingOthers,
        public int $timePlayed,
        public int $totalDamageDealt,
        public int $totalDamageDealtToChampions,
        public int $totalDamageShieldedOnTeammates,
        public int $totalDamageTaken,
        public int $totalHeal,
        public int $totalHealsOnTeammates,
        public int $totalMinionsKilled,
        public int $totalTimeCCDealt,
        public int $totalTimeSpentDead,
        public int $totalUnitsHealed,
        public int $tripleKills,
        public int $trueDamageDealt,
        public int $trueDamageTaken,
        public int $turretKills,
        public int $turretTakedowns,
        public int $turretsLost,
        public int $unrealKills,
        public int $visionScore,
        public int $visionWardsBoughtInGame,
        public int $wardsKilled,
        public int $wardsPlaced,
        public int $win,
        public int $game_id,
        public Carbon $created_at,
        public Carbon $updated_at,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Spell::class)]
        #[MapInputName('summoner1Id')]
        public Spell $spell1,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Spell::class)]
        #[MapInputName('summoner2Id')]
        public Spell $spell2,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Champion::class)]
        #[MapInputName('championId')]
        public Champion $champion,
        #[WithCast(\ProjectZero4\RiotApi\Data\Casts\Summoner::class)]
        #[MapInputName('summonerName')]
        public Summoner $summoner,
    ) {}


    #[Pure] public function itemUrl(int $itemIndex): string
    {
        return iconPath("item/{$this->{"item$itemIndex"}}.png");
    }

    #[Pure] public function spellUrl(int $spellIndex): string
    {
        return iconPath("spell/{$this->{"summoner{$spellIndex}Id"}}.png");
    }

}