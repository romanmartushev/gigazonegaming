<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IndividualPlayer
 * @package App\Model\Championship
 */
class IndividualPlayer extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['username', 'email', 'phone', 'game_id','updated_by','updated_on'];

    /**
     * Get game the individual player want to be in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game');
    }
}
