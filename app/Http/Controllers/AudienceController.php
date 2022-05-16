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

        return Audiences::create($request->all());
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