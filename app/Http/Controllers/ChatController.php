<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        return view('chat');
    }

    public function sendMessage(Request $request): array
    {
        $message = $request->input('message');
        broadcast(new MessageSent(Auth::user(), $message))->toOthers();
        return ['status' => 'Message Sent!'];
    }
}