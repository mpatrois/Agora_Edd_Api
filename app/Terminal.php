<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Terminal extends Model
{
    protected $hidden = [
        'password', 'created_at', 'updated_at'
    ];
    
    // public function users()
    // {
    //     return $this->belongsToMany('App\Terminal', 'user_terminal','user_id','terminal_id')->withPivot("start_time","stop_time","place");
    // }
    
    public function getUsersAvailableBySkills($idSkill)
    {
        
        $usersList = DB::table('users')
            ->select('users.*')
            ->join('user_terminal','user_terminal.user_id','=','users.id')
            ->join('skill_user','user_terminal.user_id','=','skill_user.user_id')
            // ->join(skills on skill_user.skill_id = skills.id)
            ->where('user_terminal.terminal_id',$this->id)
            ->where('skill_user.skill_id',  $idSkill)
            ->groupBy('users.id')
            ->get();
        
        
        $users = User::hydrate($usersList->toArray());

        foreach ($users as $user) {
            $user->session = $user->currentTerminal()->pivot;
            $user->option_id = $user->id;
            $stopTime = Carbon::parse($user->session->stop_time);
            $user->leavingPlaceIn =  $stopTime->diffForHumans();
        }

        return $users->load('skills');

    }
 

}
