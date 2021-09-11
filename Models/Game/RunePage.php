<?php


namespace ProjectZero4\RiotApi\Models\Game;

use ProjectZero4\RiotApi\Models\Base;
use ProjectZero4\RiotApi\Models;

/**
 * Class Team
 * @package ProjectZero4\RiotApi\Models\Game
 * @property integer primary_style_id
 * @property integer primary_selection_1_id
 * @property integer primary_selection_2_id
 * @property integer primary_selection_3_id
 * @property integer primary_selection_4_id
 * @property integer secondary_style_id
 * @property integer secondary_selection_1_id
 * @property integer secondary_selection_2_id
 * @property integer defense_id
 * @property integer flex_id
 * @property integer offense_id
 */
class RunePage extends GameBase
{
    protected $fillable = [
        'primary_style_id',
        'primary_selection_1_id',
        'primary_selection_2_id',
        'primary_selection_3_id',
        'primary_selection_4_id',
        'secondary_style_id',
        'secondary_selection_1_id',
        'secondary_selection_2_id',
        'defense_id',
        'flex_id',
        'offense_id',
    ];

    protected function convertAttributes(array $attributes): array
    {
        if (!isset($attributes['statPerks'])) {
            return $attributes;
        }
        $converted = [];
        $statPerks = $attributes['statPerks'];
        foreach ($statPerks as $key => $perk) {
            $converted["{$key}_id"] = $perk;
        }
        $styles = $attributes['styles'];
        foreach ($styles as $style) {
            $prefix = match ($style['description']) {
                'primaryStyle' => 'primary',
                'subStyle' => 'secondary',
                default => '',
            };
            $converted["{$prefix}_style_id"] = $style['style'];
            foreach ($style['selections'] as $key => $perk) {
                $converted["{$prefix}_selection_" . ++$key . "_id"] = $perk['perk'];
            }
        }
        return $converted;
    }

}
