<?php

namespace App\Http\Controllers;
use App\Http\Requests\Match\StoreRequest;
use \App\Models\Match;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Match[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    const pending = 0;
    const accepted = 1;
    const rejected = -1;

    public static function getMatches()
    {
        return [
            self::pending => 'Отправлено',
            self::accepted => 'Принято',
            self::rejected => 'Отказано',
        ];
    }

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

        $response = [
            "message" => "лайк удален"
        ];

        return response($response, 201);
    }

    /**
     * search for a id
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        $iLiked = Match::query()->select('target_user_id')->where('user_id', $user_id)->where('status', 0)->get();

       return User::query()->whereIn('id', $iLiked)->get();

    }

    public function searchSecond(Request $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        $likedMe = Match::query()->select('user_id')->where('target_user_id', $user_id)->where('status', 0)->get();

        return User::query()->whereIn('id', $likedMe)->get();
    }

    public function like(StoreRequest $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        $data = $request->validated();

        $data['user_id'] = $user_id;
        $data['target_user_id'] = $request->target_user_id;

        $match = new Match(array(
            'user_id'=>$data['user_id'],
            'target_user_id' => $data['target_user_id'],
            'status' => '0'
        ));
        $match->save();

        $response = [
            "message" => "лайкнуто"
        ];
        return response($response, 201);
    }

    public function theMatched(Request $request, $target_id, $status)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        Match::create([
            'user_id' => $user_id,
            'target_user_id' => $target_id,
            'status' => $status,
        ]);
        $response = [
            "message" => "Статус отправлен"
        ];

        return response($response, 201);
    }

    public function changeStatus(Request $request, $target_id, $status)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        Match::query()->where('user_id', $target_id)->where('target_user_id', $user_id)->update(['status' => $status]);

        $response = [
            "message" => "Статус отправлен"
        ];

        return response($response, 201);
    }

    public function showMatched(Request $request)
    {
        $user_token = $request->authorization;

        $user = PersonalAccessToken::findToken($user_token);

        $user_id = User::query()->where('id', $user->tokenable_id)->value('id');

        return Match::query()->with('match')->orwhere('user_id', $user_id)->orWhere('target_user_id',$user_id)
            ->where('status', 1)->get();

    }

}
