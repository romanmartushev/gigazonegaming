<?php

namespace App\Http\Controllers\Api\Championship;

use App\Http\Controllers\Controller;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\View;

class PrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game.print');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Game $game
     * @return \Illuminate\Http\Response
     */
    public function printGame(Game $game)
    {
        $playerList = $game->getPlayersInfoBy(['game'=>$game->id, 'order_by'=>'player_username']);
        return View::make('game.print')->with('playerList', $playerList);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Tournament $tournament
     * @return \Illuminate\Http\Response
     */
    public function printTournament(Tournament $tournament)
    {
        $playerList = $tournament->getPlayersInfoBy(['tournament'=>$tournament->id, 'order_by'=>'player_username']);
        return View::make('game.print')->with('playerList', $playerList);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function printTeam(Team $team)
    {
        $playerList = $team->getPlayersInfoBy(['team'=>$team->id, 'order_by'=>'player_username']);
        return View::make('game.print')->with('playerList', $playerList);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Player $player
     * @return \Illuminate\Http\Response
     */
    public function printPlayer(Player $player)
    {
//        $playerList = $player->getPlayersInfoBy(['player'=>$player->id]);
        $playerList = $player->getPlayersInfoBy(['player'=>$player->id, 'order_by'=>'player_username']);
        return View::make('game.print')->with('playerList', $playerList);
    }


}
