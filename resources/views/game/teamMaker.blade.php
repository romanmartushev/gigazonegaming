
@extends('game.base')

@section('css')
@endsection
@section('content')

    <label for="game_sort" style="width:180px; text-align:right;">Pick a Game :</label>
    <select name="game_sort" id="game_sort" style="width:350px; text-align:left;">

        <option> --- </option>
        @foreach($games as $g)
            <option id="t_option{{$g['game_id']}}" value="{{$g['game_id']}}" class="gameSelector"
                    @if(isset($sorts) and isset($sorts->game_sort) and ($g['game_id'] == $sorts->game_sort or $g['game_name'] == $sorts->game_sort)) selected="selected" @endif
            >{{$g['game_name']}}</option>
        @endforeach
    </select>
    <br />
    <label for="tournament_sort" style="width:180px; text-align:right;">Tournament: </label>
    <select name="tournament_sort" id="tournament_sort" style="width:350px; text-align:left;">
        <option> --- </option>
        @foreach($tournaments as $g)
            <option id="t_option{{$g['game_id']}}_{{$g['tournament_id']}}"  max_players="{{$g['max_players']}}" value="{{$g['tournament_id']}}" class="fa"
                    @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['tournament_id'] == $sorts->tournament_sort or $g['tournament_name'] == $sorts->tournament_sort)) selected="selected" @endif
            >{{$g['tournament_name']}} ({{$g['max_players']}} players)</option>
        @endforeach
    </select>
    <input type="submit" value="Create Team for Selected Tournament"  id="team_creator_btn" class="btn btn-primary">
    <br />
    <label for="team_sort" style="width:180px; text-align:right;">Filter by Team: </label>
    <select name="team_sort" id="team_sort" style="width:350px; text-align:left;">
        <option> --- </option>
        @foreach($teams as $g)
            @if($g['team_max_players'] > $g['team_count'])
            <option id="t_option{{$g['tournament_id']}}_{{$g['team_id']}}" value="{{$g['team_id']}}" tournament="{{$g['tournament_id']}}" team="{{$g['team_id']}}" needs_players="{{$g['team_max_players']-$g['team_count']}}"
                    @if(isset($sorts) and isset($sorts->tournament_sort) and ($g['team_id'] == $sorts->tournament_sort or $g['team_name'] == $sorts->tournament_sort)) selected="selected" @endif
            >{{$g['team_name']}} ({{$g['team_count']}} of {{$g['team_max_players']}})</option>
            @endif
        @endforeach
    </select>
    <input type="submit" value="Fill selected Team" id="team_fill_btn" class="btn btn-primary">
    <br />
    <div id="team_selected">
        {{ Form::open(array('id' => "teamFiller", 'action' => array('Backend\Manage\IndividualPlayersController@teamFill'))) }}
        <input name="_method" type="hidden" value="PUT">
        <div id="teamFilling"></div>
        <div id='submit_fill_team' class='btn btn-danger col-sm-12 hidden'>Save Team</div>
        {{ Form::close() }}
        {{ Form::open(array('id' => "teamCreator", 'action' => array('Backend\Manage\IndividualPlayersController@teamCreate'))) }}
        <input name="_method" type="hidden" value="POST">
        <div id="teamCreating"></div>
        {{ Form::close() }}
    </div>
    <br />
    <div id="player_list" class="col-sm-12">
    @foreach($individualPlayers as $k => $player)
        @if(!$player['team_id'])
            <div class="btn disabled btn-success player_buttons" style="width:330px;"
                    tournament="{{$player['tournament_id']}}"
                    player="{{$player['player_id']}}"
                    id="i_{{$player['player_id']}}_t_{{$player['tournament_id']}}"
            >{{$player['player_username']}}</div>
        @endif
    @endforeach
    </div>
    <br />
@endsection
@section('js-sheet')
    <script type="text/javascript" src="/app/content/js/teamMaker.js"></script>
@endsection