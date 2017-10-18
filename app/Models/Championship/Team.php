<?php

namespace App\Models\Championship;

use App\Http\Requests\TeamRequest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Championship\Relation\PlayerRelationable;
use App\Models\Championship\Relation\PlayerRelation;

/**
 * Class Team
 * @package App\Model\Championship
 */
class Team extends Model
{
    use PlayerRelationable;
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['name', 'emblem', 'captain', 'tournament_id','updated_by','updated_on'];

    /**
     * set uo boot
     */
    public static function boot()
    {
        parent::boot();

        // when deleted
        static::deleting(function ($team) {
            $hasTable = false;
            if(\Schema::connection('mysql_champ')->hasTable('player_relations')) {
                $hasTable = true;
            }
            if($hasTable) {
                if (PlayerRelation::where([
                    ["relation_id", "=", $team->id],
                    ["relation_type", "=", Team::class],
                ])->exists()
                ) {
                    PlayerRelation::where([
                        ["relation_id", "=", $team->id],
                        ["relation_type", "=", Team::class],
                    ])->delete();
                }
            }
        });
    }

    /**
     * Get game which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->tournament()->first()->game;
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class,'tournament_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getTournamentAttribute()
    {
        return $this->tournament()->first();
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function playerRelation()
    {
        return $this->players();
    }

    public function players()
    {
        $relationship = PlayerRelation::where('relation_id', '=', $this->id)
            ->where('relation_type', '=', Team::class)->pluck('player_id');
        return Player::whereIn('id', $relationship);

    }

    public function getPlayersAttribute()
    {
        return $this->players()->get();
    }

    /**
     * Get team captain
     * @return bool|object
     * @throws \Exception
     */
    public function captain()
    {
        if (!$this->getAttribute('captain')) {
            return false;
        }
        return Player::findOrFail($this->getAttribute('captain'));
    }

    /**
     * Get true if team is full or false if you can still add players
     *
     * @return boolean
     */
    public function isTeamFull(){

        $maxPlayers = $this->tournament()->select('max_players')->first()->toArray();
        $teamCount = $this->players()->count();

        return $maxPlayers["max_players"] <= $teamCount; //if team is full this will eval to true, otherwise will eval to false
    }
    /**
     * Get true if team is not full or false if you can't add players
     *
     * @return boolean
     */
    public function isTeamNotFull(){

        $maxPlayers = $this->tournament()->select('max_players')->first()->toArray();
        $teamCount = $this->players()->count();
        return $maxPlayers["max_players"] > $teamCount; //if team is full this will eval to true, otherwise will eval to false
    }
    /**
     * @param $id
     * @return mixed
     */
    static public function whereId($id)
    {
        if (Team::where('id', '=', intval($id))->exists()) {
            return Team::where('id', '=', intval($id))->first();
        }
        return null;
    }
    /**
     * @param $name
     * @return mixed
     */
    static public function whereName($name)
    {
        if (Team::where('name','=', $name)->exists()) {
            return Team::where('name','=', $name)->first();
        }
        return null;
    }
}
