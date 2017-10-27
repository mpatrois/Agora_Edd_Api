<?php

use Illuminate\Database\Seeder;

use App\Skill;
use App\Terminal;
use App\User;
use App\Question;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    private function avatarUrl($gender)
    {
        if($gender=="male"){
            return "https://randomuser.me/api/portraits/men/".rand(1,30).".jpg";
        }else{
            return "https://randomuser.me/api/portraits/women/".rand(1,30).".jpg";
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::create(['name' => 'Social Media Marketing',]);
        Skill::create(['name' => 'Développement Web',]);
        Skill::create(['name' => 'Droit des affaires',]);
        Skill::create(['name' => 'Développement mobile',]);
        Skill::create(['name' => 'Cloud and Distributed Computing',]);
        Skill::create(['name' => 'Recruiting',]);
        Skill::create(['name' => 'Computer Graphics & Animation',]);
        // Skill::create(['name' => 'Storage Systems & Management',]);
        // Skill::create(['name' => 'Algorithm design',]);
        Skill::create(['name' => 'Création graphique',]);
        Skill::create(['name' => 'Web Marketing',]);
        Skill::create(['name' => 'Web Design',]);
        // Skill::create(['name' => 'Expert SEO',]);
        Skill::create(['name' => 'Management',]);
        // Skill::create(['name' => 'Personnal Branding',]);
        
        Terminal::create(['name' => 'Node Bordeaux','password' => 'motdepasse']);
        Terminal::create(['name' => 'BU Talence','password' => 'motdepasse']);

        

        User::create(['username' => 'Bram Van Osta', 'password' => 'motdepasse','avatar' => $this->avatarUrl("male") , 'gender' => 'male' ]);
        User::create(['username' => 'Manu Patrois', 'password' => 'motdepasse','avatar' => $this->avatarUrl("male") , 'gender' => 'male' ]);
        User::create(['username' => 'Valentin Dupond', 'password' => 'motdepasse','avatar' => $this->avatarUrl("male") , 'gender' => 'male' ]);
        User::create(['username' => 'Yann Bertrand', 'password' => 'motdepasse','avatar' => $this->avatarUrl("male") , 'gender' => 'male' ]);
        User::create(['username' => 'Florence Dubosc', 'password' => 'motdepasse','avatar' => $this->avatarUrl('female') , 'gender' => 'female' ]);
        User::create(['username' => 'Yannick Tamers-Lachiaine', 'password' => 'motdepasse','avatar' => $this->avatarUrl("male") , 'gender' => 'male' ]);
        User::create(['username' => 'Cristhopher Wallace', 'password' => 'motdepasse','avatar' => $this->avatarUrl("male") , 'gender' => 'male' ]);

        $faker = Faker\Factory::create();

        for ($i=0; $i < 2 ; $i++) {
            Terminal::create(['name' => $faker->company, 'password' => 'motdepasse']);
        }

        for ($i=0; $i < 140 ; $i++) {
            $gender = rand(0,1) == 1 ? 'male' : 'female';
            User::create(['username' => $faker->name($gender), 'password' => 'motdepasse','avatar' => $this->avatarUrl($gender), 'gender' => $gender ] );
        }

        foreach(User::all() as $user){

            $randTerminal = Terminal::orderBy(DB::raw('RAND()'))->first();

            $dateStart = Carbon::now();
            $dateEnd = Carbon::create()->addHour(2)->toDateTimeString();

            $user->terminals()->attach($randTerminal->id,['start_time' => $dateStart, 'stop_time' => $dateEnd, 'place' => 'Zone jaune' ]);
            
            for ($i=0; $i < rand(1,2) ; $i++) { 
                $randSkill = Skill::orderBy(DB::raw('RAND()'))->first();
                $user->skills()->save($randSkill);
            }


        }

        // $dateStart = Carbon::now();
        // $dateEnd = Carbon::create()->addHour(2)->toDateTimeString();

        // $bram->terminals()->attach($node->id,['start_time' => $dateStart, 'stop_time' => $dateEnd, 'place' => 'Table 3' ]);
        // $manu->terminals()->attach($bu->id,  ['start_time' => $dateStart, 'stop_time' => $dateEnd, 'place' => '2ème étage' ]);
        
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
