<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');

        broadcast(new MessageSent(Auth::user(), $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}


