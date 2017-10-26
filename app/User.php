<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];


    public function skills(){
        return $this->belongsToMany('App\Skill');
    }

    public function terminals(){
        return $this->belongsToMany('App\Terminal', 'user_terminal','user_id','terminal_id')->withPivot("start_time","stop_time","place");
    }

    public function currentTerminal(){
        return $this->terminals()->latest()->first();
    }

    public function updateUserStopTime($nbMinutes){
        $startTime = $this->currentTerminal()->pivot->start_time;
        $stopTime = Carbon::parse($startTime);
        $stopTime->addMinutes($nbMinutes);

        $this->terminals()->updateExistingPivot($this->currentTerminal()->id, ['stop_time' => $stopTime ]);
    }

    public function updateUserPlace($place){
        $this->terminals()->updateExistingPivot($this->currentTerminal()->id, ['place' => $place ]);
    }


}
