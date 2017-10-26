<?php

use Illuminate\Database\Seeder;

use App\Skill;
use App\Terminal;
use App\User;
use App\Question;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::create(['name' => 'Photoshop',]);
        Skill::create(['name' => 'Développement Web',]);
        Skill::create(['name' => 'Droit des affaires',]);
        Skill::create(['name' => 'Physique-Chimie',]);
        Skill::create(['name' => 'Mathématiques',]);
        Skill::create(['name' => 'C++',]);
        Skill::create(['name' => 'Vente de te-shi sisi',]);
        
        $node = Terminal::create(['name' => 'Node Bordeaux','password' => 'motdepasse']);
        $bu   = Terminal::create(['name' => 'BU Talence','password' => 'motdepasse']);

        $bram = User::create(['username' => 'Bram Van Osta', 'password' => 'motdepasse']);
        $manu = User::create(['username' => 'Manu Patrois', 'password' => 'motdepasse']);

        foreach(Skill::all() as $skill){
            $manu->skills()->save($skill);
            $bram->skills()->save($skill);
        }

        $dateStart = Carbon::create()->toDateTimeString();
        $dateEnd = Carbon::create()->addHour(2)->toDateTimeString();

        $bram->terminals()->attach($node->id,['start_time' => $dateStart, 'stop_time' => $dateEnd, 'place' => 'Table 3' ]);
        $manu->terminals()->attach($bu->id,  ['start_time' => $dateStart, 'stop_time' => $dateEnd, 'place' => '2ème étage' ]);
        
        // const QU_WHATS_YOUR_NAME = 1;
        // const QU_HOW_MANY_HOURS  = 2;
        // const QU_WHAT_PLACE = 3;
        // const QU_MAKE_SMILE = 4;
        // const QU_HOW_CAN_I_HELP = 5;

        // Question::create([
        //     'id' => Question::QU_WHATS_YOUR_NAME,
        //     'content' => "Salut, je suis Edd! Comment t'appelles tu ?"
        // ]);
        
        // Question::create([
        //     'id' => Question::QU_HOW_MANY_HOURS,
        //     'content' => "Combien de temps restes tu ?"
        // ]);
        
        // Question::create([
        //     'id' => Question::QU_WHAT_PLACE,
        //     'content' => "A quel endroit ?"
        // ]);
        
        // Question::create([
        //     'id' => Question::QU_MAKE_SMILE,
        //     'content' => "Fais nous ton plus beau sourire !"
        // ]);

        // Question::create([
        //     'id' => Question::QU_HOW_CAN_I_HELP,
        //     'content' => "Comment puis-je t'aider ?"
        // ]);

    }
}
