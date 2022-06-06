<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Events::orderBy('created_at', "DESC")->paginate(10);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function main($userid)
    {
        return Events::where('user_id', $userid)->latest()->take(3)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($userid)
    {
        return Events::where('user_id', $userid)->latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'take_photo' => 'required',
            'take_location' => 'required',
            'take_time' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        return Events::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Events::with('audiences')->find($id);
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
        $event = Events::find($id);
        $event->update($request->all());
        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Events::find($id)->delete();
    }

    ////////////////////////////////////////////////


    public function token($token)
    {
        return Events::with('author')->where('token', 'like', "%".$token."%")->get()->first();
    }

}
