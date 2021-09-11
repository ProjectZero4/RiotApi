<?php


namespace ProjectZero4\RiotApi\Models\Game;

use ProjectZero4\RiotApi\Models\Base;
use ProjectZero4\RiotApi\RiotApiCollection;
use ProjectZero4\RiotApi\Models;

/**
 * Class Summoner
 * @package ProjectZero4\RiotApi\Models
 * @property-read int internalKey
 * @property string id
 * @property string accountId
 * @property string puuid
 * @property string name
 * @property int profileIconId
 * @property int revisionDate
 * @property int summonerLevel
 * @property string nameKey
 * @property RiotApiCollection<Models\ChampionMastery> masteries
 * @property RiotApiCollection<Models\League> leagues
 */
class Queue extends GameBase
{
}
