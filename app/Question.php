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

    const QU_OPTION_SEARCH_SKILLS = 6;
    const QU_OPTION_POST_SKILLS = 7;
    
    const QU_SKILLS_WANTED = 8;
    const QU_SKILLS_POSTED = 9;

    const QU_PEOPLE_SKILLS_WANTED = 10;

    const QU_TANKS_EDD = 10;

    // static public function getContent(){
    //     $questions = [];
    //     $questions[QU_INIT_CONV]
    // }


}
