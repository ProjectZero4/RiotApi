<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use ProjectZero4\RiotApi\Data\Spectator\Participant;

use function ProjectZero4\RiotApi\riotApi;
use function ProjectZero4\RiotApi\spellUrl;

class ParticipantResource extends JsonResource
{
    /** @var Participant */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    #[ArrayShape([
        'champion' => "array",
        'summoner' => "array",
        'spell1Url' => "string",
        'spell2Url' => "string",
        'stats' => "array",
        'tags' => "array"
    ])] public function toArray($request): array
    {
        $participant = $this->resource;
        $api = riotApi();
        return [
            'champion' => $participant->champion->toArray(),
            'summoner' => $participant->summoner->toArray(),
            'spell1Url' => $api->getSpellByKey($participant->spell1Id)->imageUrl(),
            'spell2Url' => $api->getSpellByKey($participant->spell2Id)->imageUrl(),
            'stats' => [],
            'tags' => [],
        ];
    }
}
