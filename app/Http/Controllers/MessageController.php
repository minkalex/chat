<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return
     */
    public function index(Request $request)
    {
        if ($request->hasHeader('X-Requested-With')) {
            $chat = Chat::find($request->chat_id);
            if (null !== $chat) {
                $arrMessages = $chat->messages;
                $arrUsers = User::all();
                foreach ($arrMessages as $index => $arrMessage) {
                    foreach ($arrUsers as $arrUser) {
                        if ($arrUser->id === $arrMessage->user_id) {
                            $arrMessages[$index]['user_fullname'] = $arrUser->full_name;
                        }
                    }
                    $arrMessages[$index]['formatted_date'] = date('d.m.Y H:i', strtotime($arrMessage['created_at']));
                    if (null !== $arrMessage->replied_to) {
                        foreach ($arrMessages as $message) {
                            if ($arrMessage->replied_to === $message->id) {
                                $arrMessages[$index]['replied_text'] = $message->text;
                                $arrMessages[$index]['replied_author'] = $message->text;
                            }
                        }
                    }
                }
                return $arrMessages;
            }
        }
        return [];
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
     * @param  StoreMessageRequest  $request
     * @return
     */
    public function store(StoreMessageRequest $request)
    {
        Message::create($request->all());
        $chat = Chat::find($request->chat_id);
        $chat->updated_at = date('c');
        $chat->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateMessageRequest  $request
     * @param  Message  $message
     * @return void
     */
    public function update(UpdateMessageRequest $request, Message $message): void
    {
        $message->text = $request->text;
        $message->save();
        $chat = Chat::find($request->chat_id);
        $chat->updated_at = date('c');
        $chat->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Message  $message
     * @return void
     */
    public function destroy(Message $message): void
    {
        $message->delete();
    }
}
