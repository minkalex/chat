<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        if ($request->hasHeader('X-Requested-With')) {
            return Auth::user()->chats()->with('users')->get();
        } elseif (Auth::check()) {
            $chats = Auth::user()->chats;
            $users = User::all();
            return view('chat')
                ->with(compact('chats', 'users'));
        } else {
            return redirect()->route('login');
        }
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
     * @param  StoreChatRequest  $request
     * @return void
     */
    public function store(StoreChatRequest $request): void
    {
        if (empty($request->title)) {
            if (count($request->usersId) > 1) {
                $lastChatId = Chat::all()->last()->id;
                $request['title'] = 'chat #'.$lastChatId + 1;
            }
        }
        $result = Chat::create($request->except(['usersId']));
        $arData = [];
        foreach ($request->usersId as $userId) {
            $arData[] = [
                'user_id' => $userId,
                'chat_id' => $result['id'],
                'created_at' => date('c'),
                'updated_at' => date('c')
            ];
        }
        $arData[] = [
            'user_id' => $request->created_by,
            'chat_id' => $result['id'],
            'created_at' => date('c'),
            'updated_at' => date('c')
        ];
        DB::table('chat_user')->insert($arData);
    }

    /**
     * Display the specified resource.
     *
     * @param  Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateChatRequest  $request
     * @param  Chat  $chat
     * @return void
     */
    public function update(UpdateChatRequest $request, Chat $chat): void
    {
        if ('edit' === $request->action) {
            $chat->title = $request->title;
            $chat->save();
            $usersFromDb = DB::table('chat_user')
                ->select('id', 'user_id')
                ->where('chat_id', $chat->id)
                ->where('user_id', '<>', Auth::user()->id)
                ->get()
                ->toArray();
            $newUsersIds = $request->usersId;
            unset($newUsersIds[array_search(Auth::user()->id, $newUsersIds)]);
            $newUsersIds = array_values($newUsersIds);
            foreach ($usersFromDb as $index => $userFromDb) {
                if (in_array($userFromDb->user_id, $newUsersIds)) {
                    unset($usersFromDb[$index]);
                    unset($newUsersIds[array_search($userFromDb->id, $newUsersIds)]);
                }
            }
            $newUsersIds = array_values($newUsersIds);
            $usersFromDb = array_values($usersFromDb);
            $arrDataToInsert = [];
            foreach ($newUsersIds as $index => $newUserId) {
                if (!empty($usersFromDb)) {
                    $objUserFromDb = $usersFromDb[$index];
                    DB::table('chat_user')
                        ->where('id', $objUserFromDb->id)
                        ->update(['user_id' => $newUserId, 'updated_at' => date('c')]);
                    unset($usersFromDb[$index]);
                } else {
                    $arrDataToInsert[] = [
                        'user_id' => $newUserId,
                        'chat_id' => $chat->id,
                        'created_at' => date('c'),
                        'updated_at' => date('c')
                    ];
                }
            }
            if (!empty($arrDataToInsert)) {
                DB::table('chat_user')->insert($arrDataToInsert);
            }
            if (!empty($usersFromDb)) {
                $usersFromDb = array_values($usersFromDb);
                foreach ($usersFromDb as $userFromDb) {
                    DB::table('chat_user')->where('id', $userFromDb->id)->delete();
                }

            }
        } elseif ('leave' === $request->action) {
            DB::table('chat_user')
                ->where('chat_id', $chat->id)
                ->where('user_id', $request->userId)
                ->delete();
            $chat->updated_at = date('c');
            $chat->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Chat  $chat
     * @return void
     */
    public function destroy(Chat $chat): void
    {
        $chat->messages()->delete();
        DB::table('chat_user')
            ->where('chat_id', $chat->id)
            ->delete();
        $chat->delete();
    }

    /**
     *
     * @param  Request  $request
     * @param  Chat  $chat
     */
    public function getParticipants(Request $request, Chat $chat)
    {
        if ($request->hasHeader('X-Requested-With')) {
            return $chat->users;
        }
        return [];
    }
}
