<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameRelationshipForEachPlayer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable('Tournament') and Schema::connection('mysql_champ')->hasTable('player_relations') ) {
            $tournaments = \App\Models\Championship\Relation\PlayerRelation::where('relation_type', '=', \App\Models\Championship\Tournament::class)->get();
            foreach ($tournaments as $key => $tournament) {
                $the_tournament = \App\Models\Championship\Tournament::where('id', '=', $tournament->relation_id)->first();
                if ($the_tournament == null) {
                    $tournament->delete();
                } else {
                    $p_id = $tournament->player_id;
                    $g_id = $the_tournament->game_id;
                    $relation = new \App\Models\Championship\Relation\PlayerRelation();
                    $relation->player_id = $p_id;
                    $relation->relation_id = $g_id;
                    $relation->relation_type = \App\Models\Championship\Game::class;
                    $exists = \App\Models\Championship\Game::find($g_id)->hasPlayerID($p_id);
                    if (!$exists) {
                        $relation->save();
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
