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
        
        $node = Terminal::create(['name' => 'Node Bordeaux','password' => 'motdepasse']);
        $bu   = Terminal::create(['name' => 'BU Talence','password' => 'motdepasse']);

        $bram = User::create(['first_name' => 'Bram','last_name' => 'Bram Van Osta', 'password' => 'motdepasse']);
        $manu = User::create(['first_name' => 'Manu','last_name' => 'Patrois', 'password' => 'motdepasse']);

        foreach(Skill::all() as $skill){
            $manu->skills()->save($skill);
            $bram->skills()->save($skill);
        }

        $dateStart = Carbon::now();
        $dateEnd = $dateStart->addHour();


        $bram->terminals()->attach($node->id,['start_time' => $dateStart, 'stop_time' =>$dateEnd ]);
        $manu->terminals()->attach($bu->id,['start_time' => $dateStart, 'stop_time' =>$dateEnd ]);
        
        
        
        // Question::create();



    }
}
