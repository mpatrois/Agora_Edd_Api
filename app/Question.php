<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    const QU_INIT_CONV = 0;
    const QU_WHATS_YOUR_NAME = 1;
    const QU_HOW_MANY_HOURS  = 2;
    const QU_WHAT_PLACE = 3;
    const QU_MAKE_SMILE = 4;
    const QU_HOW_CAN_I_HELP = 5;

    // static public function getContent(){
    //     $questions = [];
    //     $questions[QU_INIT_CONV]
    // }


}
