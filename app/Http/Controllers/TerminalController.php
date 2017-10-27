<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Terminal;
use App\Question;
use App\User;
use App\Skill;
use Carbon\Carbon;


class TerminalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Terminal::all();
        if($all){
            $all->load('users.skills');
        }
        return $all;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terminal = Terminal::find($id);
        if($terminal){
            $terminal->load('users.skills');
        }
        return $terminal;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function chatBot(Request $request)
     {
        
        switch ($request->question_id){
            case Question::QU_WHATS_YOUR_NAME: return $this->setUserName($request);break;
            case Question::QU_HOW_MANY_HOURS:  return $this->setHours($request);break;
            case Question::QU_WHAT_PLACE:      return $this->setPlace($request);break;
            case Question::QU_MAKE_SMILE:      return $this->setPhoto($request);break;
            case Question::QU_HOW_CAN_I_HELP:  return $this->makeChoice($request);break;
            case Question::QU_SKILLS_WANTED:   return $this->selectSkills($request);break;
            case Question::QU_SKILLS_POSTED:   return $this->postSkills($request);break;
            case Question::QU_PEOPLE_SKILLS_WANTED:   return $this->chooseUser($request);break;
            case Question::QU_TANKS_EDD:   return $this->endChat($request);break;
            // case Question::QU_HOW_CAN_I_HELP:  return $this->setPlace($request);break;
        }
        return $this->initChatBot($request);
     }

    
    private function initChatBot(Request $request){
        $user = new User();
        $terminal = Terminal::find(1); //Terminal::find($request->terminal_id);

        $user->save();
        $user->terminals()->attach($terminal->id,  ['start_time' => Carbon::now()]);
        
        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            'key'      => Question::QU_WHATS_YOUR_NAME,
            'key_name' => "QU_WHATS_YOUR_NAME",
            'type'     => "TEXT",
            'bubbles'  => [
                [
                    'content' => "Salut, je suis Edd! Je suis là pour faciliter les échanges et les rencontres dans cet espace. Comment t'appelles tu ?"
                ]

            ]
        ];

    }

    private function setUserName(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        $user->username = $request->response_data;//String
        $user->save();
        
        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            'key'      => Question::QU_HOW_MANY_HOURS,
            'key_name' => "QU_HOW_MANY_HOURS",
            'type'     => "PROGRESS",
            'bubbles'  => [
                [
                    'content' => "Bienvenue à toi $user->username !"
                ],
                [
                    'content' => "Combien de temps resteras tu dans cet espace ?"
                ]
            ]
        ];
    }
    
    private function setHours(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        $user->updateUserStopTime($request->response_data);//Integer nbMinutes

        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_WHAT_PLACE,
            "key_name" => "QU_WHAT_PLACE",
            'type'     => "SELECT",
            'options' => [
                [
                    'option_id'=> 1,
                    'name' => 'Zone A',
                ],
                [
                    'option_id'=> 2,
                    'name' => 'Zone B',
                ],
                [
                    'option_id'=> 3,
                    'name' => 'Zone C',
                ],
                // '1 er étage',
                // '2 ème étage',
                // 'Table 1',
                // 'Table 2',
                // 'Space X',
            ],
            'bubbles'  => [
                [
                    'content' => "$request->response_data minutes ! Vraiment pas mal !",
                ],
                [
                    'content' => "Dans quelle zone peut-on te trouver ?",
                ]
            ]
        ];
    }
    
    private function setPlace(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        $user->updateUserPlace($request->response_data);//String name place
        
        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_MAKE_SMILE,
            "key_name" => "QU_MAKE_SMILE",
            'type' => "PICTURE",
            'bubbles'  => [
                [
                    'content' => "C'est noté ! Et maintenant...",
                ],
                [
                    'content' => "Fais nous ton plus beau sourire !",
                ]
            ]
        ];
    }

    private function setPhoto(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_HOW_CAN_I_HELP,
            "key_name" => "QU_HOW_CAN_I_HELP",
            'type'     => "SELECT",
            'options' => [
                [
                    'option_id'=> Question::QU_OPTION_SEARCH_SKILLS,
                    'name' => 'Je recherche des compétences',
                ],
                [
                    'option_id'=> Question::QU_OPTION_POST_SKILLS,
                    'name' => 'Je partage mes compétences',
                ],
            ],
            'bubbles'  => [
                [
                    'content' => "Ta photo est enregistrée dans l'appareil jusqu'à que tu t'en ailles",
                ]
                ,[
                    'content' => "Passons aux choses sérieuses, comment puis-je t'aider ?",
                ]
            ]
        ];
    }
    
    private function makeChoice(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        if($request->response_data == Question::QU_OPTION_SEARCH_SKILLS ){

            return [
                'user'        => $user,
                'terminal'    => $terminal,
                'session'     => $user->currentTerminal()->pivot,
                "key"         => Question::QU_SKILLS_WANTED,
                "key_name"    => "QU_SKILLS_WANTED",
                'type'        => "SEARCH",
                'uri'         => 'api/searchCompetences',
                'name_search' => 'search_skill',
                'bubbles'  => [
                    [
                        'content' => "Quelle compétence cherches-tu ? Tape quelques lettres pour trouver ce dont tu as besoin !",
                    ]
                ]
            ];

        }
        else if($request->response_data == Question::QU_OPTION_POST_SKILLS){

            return [
                'user'              => $user,
                'terminal'          => $terminal,
                'session'           => $user->currentTerminal()->pivot,
                "key"               => Question::QU_SKILLS_POSTED,
                "key_name"          => "QU_SKILLS_POSTED",
                'type'              => "SEARCH",
                'uri'               => 'api/searchCompetences',
                'name_search_field' => 'search_skill',
                'bubbles'  => [
                    [
                        'content' => "Quelle compétences a tu ? Tape quelques lettres pour trouver ce dont tu es fait !",
                    ]
                ]
            ];

        }

        
    }

    public function selectSkills(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);

        // return 

        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_PEOPLE_SKILLS_WANTED,
            "key_name" => "QU_PEOPLE_SKILLS_WANTED",
            'type'     => "SELECT_USER",
            'options'  => $terminal->getUsersAvailableBySkills($request->response_data),
            'bubbles'  => [
                [
                    'content' => "Voici une liste de personnes qui peuvent t'interesser !",
                ]
            ]
            
        ];


    }
    
    public function chooseUser(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);

        $userToTalk = User::find($request->response_data);
        $userToTalk->session = $userToTalk->currentTerminal()->pivot;
        $placeToBe = $userToTalk->session->place;

        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_TANKS_EDD,
            "key_name" => "QU_TANKS_EDD",
            'type'     => "SELECT",
            'options' => [
                [
                    'option_id'=> Question::QU_TANKS_EDD,
                    'name' => 'Merci Edd',
                ],
            ],
            'bubbles'  => [
                [
                    'content' => "Voici $user->username ! tu peux aller lui parler, il se trouve à cet endroit : $placeToBe",
                ]
            ]
        ];
    }

    public function endChat(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);

        $userToTalk = User::find($request->response_data);
        $userToTalk->session = $userToTalk->currentTerminal()->pivot;
        $placeToBe = $userToTalk->session->place;

        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            'type'     => "END",
            'bubbles'  => [
                [
                    'content' => "Pas de problème $user->username, je gère les bails !",
                ]
            ]
        ];
    }
    
    public function postSkills(Request $request){
        
    }

    public function searchCompetences(Request $request){
        return Skill::where('name','LIKE',"%$request->search_skill%")->limit(5)->get();
    }

    


}
