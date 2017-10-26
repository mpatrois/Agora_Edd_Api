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
        }
        return $this->initChatBot($request);
     }

    
    private function initChatBot(Request $request){
        $user = new User();
        $terminal = Terminal::find(1);
        // $terminal = Terminal::find($request->terminal_id);

        $user->save();
        $user->terminals()->attach($terminal->id,  ['start_time' => null, 'stop_time' => null, 'place' => '2ème étage' ]);

        return [
            'user'     => $user,
            'terminal' => $terminal,
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
            'key'      => Question::QU_HOW_MANY_HOURS,
            'bubbles'  => [
                [
                    'type' => "PROGRESS",
                    'content' => "Combien de temps restes tu ?"
                ]
            ]
        ];
    }
    
    private function setPlace(Request $request){
        $user = new User($request->all());

        $user->terminals()->attach( $terminal->id,['start_time' => Carbon::create() ] );
        

        return [
            'user' => $user,
            "nextQuestion" => Question::find(Question::QU_WHAT_PLACE),
            "answers" => [
                'En haut',
                'Table 3',
                'Au bar'
            ]
        ];
    }

    private function setHours(Request $request){
        $user = new User($request->all());

        $user->terminals()->attach( $terminal->id,['start_time' => Carbon::create() ] );

        return [
            'user' => $user,
            "nextQuestion" => Question::find(Question::QU_WHAT_PLACE),
            "answers" => [
                'En haut',
                'Table 3',
                'Au bar'
            ]
        ];
    }

}
