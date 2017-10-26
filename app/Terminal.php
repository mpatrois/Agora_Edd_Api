<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $hidden = [
        'password', 'created_at', 'updated_at'
    ];
    // public function users()
    // {
    //     return $this->belongsToMany('App\User', 'user_terminal','user_id','terminal_id');
    // }

 

}
