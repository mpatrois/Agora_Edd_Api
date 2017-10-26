<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Terminal;
use App\Question;
use App\User;
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
            'bubbles'  => [
                [
                    'type' => "TEXT",
                    'content' => "Salut, je suis Edd! Comment t'appelles tu ?"
                ]

            ]
        ];

    }

    private function setUserName(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        $user->fill($request->all());
        $user->save();
        
        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            'key'      => Question::QU_HOW_MANY_HOURS,
            'bubbles'  => [
                [
                    'type' => "PROGRESS",
                    'content' => "Combien de temps restes tu ?"
                ]
            ]
        ];
    }
    
    private function setHours(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        $user->updateUserStopTime($request->nb_minutes);

        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_WHAT_PLACE,
            'bubbles'  => [
                [
                    'type' => "SELECT",
                    'content' => "A quel endroit ?",
                    'options' => [
                        '1 er étage',
                        '2 ème étage',
                        'Table 1',
                        'Table 2',
                    ]
                ]
            ]
        ];
    }
    
    private function setPlace(Request $request){
        $user = User::find($request->user_id);
        $terminal = Terminal::find($request->terminal_id);
        
        $user->updateUserPlace($request->place);
        
        return [
            'user'     => $user,
            'terminal' => $terminal,
            'session'  => $user->currentTerminal()->pivot,
            "key"      => Question::QU_MAKE_SMILE,
            'bubbles'  => [
                [
                    'type' => "TEXT",
                    'content' => "Fais nous ton plus beau sourire !",
                    'options' => []
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
            'bubbles'  => [
                [
                    'type' => "SELECT",
                    'content' => "Comment puis-je t'aider ?",
                    'options' => [
                        'Je recherche des compétences',
                        'Je propose mes compétences',
                    ]
                ]
            ]
        ];
    }

    


}
