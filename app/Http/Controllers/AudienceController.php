<?php

namespace App\Http\Controllers;

use App\Models\Audiences;
use Illuminate\Http\Request;

class AudienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Audiences::with('event', 'user')->orderBy('created_at', "DESC")->paginate(10);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function main($userid)
    {
        return Audiences::with('event', 'user')->where('user_id', $userid)->latest()->take(3)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($userid)
    {
        return Audiences::with('event', 'user')->where('user_id', $userid)->orderBy('created_at', "DESC")->paginate(10);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listevent($eventid)
    {
        return Audiences::with('user')->where('events_id', $eventid)->orderBy('created_at', "DESC")->paginate(10);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eventaudiencescount($eventid)
    {
        return Audiences::with('user')->where('events_id', $eventid)->count();
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
            'user_id' => 'required',
            'events_id' => 'required',
            'token' => 'required'
        ]);

        Audiences::updateOrCreate([
            'user_id' => $request->user_id,
            'events_id' => $request->events_id
        ],[
            'entry_date' => $request->entry_date,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'message' => $request->message,
            'token' => $request->token,
            'saved' => $request->saved,
            'user_id' => $request->user_id,
            'events_id' => $request->events_id,
            'photoUrl' => $request->photoUrl
        ]);

        $ret = Audiences::with('event', 'user')->where([
            'user_id' => $request->user_id,
            'events_id' => $request->events_id
        ])->get()->first();

        return $ret;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImage(Request $request)
    {
        $request->validate([
            'file' => 'nullable|mimes:png,jpg,jpeg|max:8096'
        ]);

        $photoUrlName = null;
        if($request->hasFile('file')){
            $photoUrlName = md5(time()).'.'.$request->file('file')->getClientOriginalExtension();
            $request->file->move(public_path('photos'), $photoUrlName);
        }

        return $photoUrlName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Audiences::find($id);
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
        $audience = Audiences::find($id);
        $audience->update($request->all());
        return $audience;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Audiences::find($id)->delete();
    }



    ///////////////////////////////////////////////
    public function token($token)
    {
        return Audiences::where('token', 'like', $token)->get();
    }
}
