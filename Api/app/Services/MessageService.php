<?php

namespace App\Services;

use App\Exceptions\GeneralExceptionCatch;
use App\Http\Resources\MessageResource;
use App\MessageInterface;
use App\Models\Messages;
use Illuminate\Support\Facades\Auth;

class MessageService implements MessageInterface
{
    public function showUser()
    {
        try {
            $message = Messages::with(['senderMessage', 'receiverMessage'])->where('sender_id', Auth::user()->idUser)
                ->orWhere('receiver_id', Auth::user()->idUser)
                ->get();

            if (!$message) {
                return response()->json(["message" => "Message not found"], 404);
            }
            return new MessageResource($message);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: show user');
        }
    }

    public function show(string $id)
    {
        try {
            $message = Messages::with(['senderMessage', 'receiverMessage'])->where('idMessage', $id)->first();
            if (!$message) {
                return response()->json(["message" => "Message not found"], 404);
            }
            return new MessageResource($message);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: show');
        }
    }

    public function store(array $data)
    {
        try {
            Messages::create($data);
            return response()->json(["message" => "success"], 204);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: store');
        }
    }
}
