<?php

namespace App\Http\Controllers;
use \App\Models\Match;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Match[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Match::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Match::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Match::find($id);
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
        Match::destroy($id);
    }

    /**
     * search for a id
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function search($user_id)
    {
       return Match::query()->where('user_id', $user_id)->get();
    }

    public function searchSecond($user_id)
    {
        return Match::query()->where('target_user_id', $user_id)->get();
    }

    public function theMatched($user_id)
    {
        return dd(Match::query()->where('user_id', $user_id)->get());
    }

}
