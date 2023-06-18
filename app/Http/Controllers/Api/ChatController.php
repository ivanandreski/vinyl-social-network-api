<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    public function sendMessage(User $user, Request $request): Message
    {
        $sender = Auth::user();

        $message = new Message();
        $message->sender_id = $sender->id;
        $message->receiver_id = $user->id;
        $message->body = $request->body;
        $message->read = false;
        $message->save();

        return $message;
    }

    public function getMessagesForUser(User $user)
    {
        $requestUser = Auth::user();

        $senderId = $requestUser->id;
        $receiverId = $user->id;

        $data = Message::query()
            ->where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return response(['data' => $data], Response::HTTP_OK);
    }

    public function getUserChats() {
        $requestUser = Auth::user();
        $data = [];
        foreach($requestUser->friends as $friendJoin) {
            $friend = $friendJoin->friend;
            $senderId = $requestUser->id;
            $receiverId = $friend->id;
            $data[$friend->id . "!##!" . $friend->getFullName()] = Message::query()
                ->where(function ($query) use ($senderId, $receiverId) {
                    $query->where('sender_id', $senderId)
                        ->where('receiver_id', $receiverId);
                })
                ->orWhere(function ($query) use ($senderId, $receiverId) {
                    $query->where('sender_id', $receiverId)
                        ->where('receiver_id', $senderId);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response(sizeof($data) == 0 ? '{}' : $data, Response::HTTP_OK);
    }

    /**
     * The stream source.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnreadMessages()
    {
        $user = Auth::user();
        $unreadMessages = Message::where('receiver_id', $user->id)
        ->where('read', '!=', true);

        $data = [];
        foreach($unreadMessages as $message) {
            if(!array_key_exists($message->sender_id, $data)) {
                $data[$message->sender_id] = [];
            }
            $data[$message->sender_id][] = $message;
        }

        return response(['data' => $data], Response::HTTP_OK);
    }
}
