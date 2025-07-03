<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Livewire\Attributes\Url;

class Chat extends Component
{
    public static string $layout = 'layouts.app';
    public $users;
    public $newMessage = '';
    public $messages = [];
    public $searchTerm = '';
    public $loginID;
    #[Url]
    public $selectedUserId = null;

    protected $rules = [
        'newMessage' => 'required|string|max:1000'
    ];

    protected $validationMessages = [
        'newMessage.required' => 'Message cannot be empty',
        'newMessage.max' => 'Message is too long (max 1000 characters)'
    ];

    public function mount()
    {
        $this->loginID = Auth::id();
        $this->loadUsers();
        $this->messages = [];
    }

    public function loadUsers()
    {
        $this->users = User::where('id', '!=', Auth::id())
            ->when($this->searchTerm, function($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->searchTerm.'%')
                      ->orWhere('email', 'like', '%'.$this->searchTerm.'%');
                });
            })
            ->orderBy('name')
            ->get();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->loadMessages();
        $this->dispatch('chat-selected');
    }

    public function loadMessages()
    {
        if (!$this->selectedUserId) return;
        $selectedUser = User::find($this->selectedUserId);
        $this->messages = ChatMessage::with(['sender', 'receiver'])
            ->where(function($query) use ($selectedUser) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $selectedUser->id);
            })
            ->orWhere(function($query) use ($selectedUser) {
                $query->where('sender_id', $selectedUser->id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function submit()
    {
        $validated = $this->validate($this->rules, $this->validationMessages);
        $selectedUser = $this->selectedUserId ? User::find($this->selectedUserId) : null;
        if (!$selectedUser) {
            $this->addError('newMessage', 'No user selected.');
            return;
        }
        try {
            $message = ChatMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $selectedUser->id,
                'message' => $validated['newMessage']
            ])->load('sender', 'receiver');

            $this->messages[] = $message->toArray();
            $this->reset('newMessage');
            $this->dispatch('message-sent');
            
            event(new MessageSent(Auth::user(), $message));
            
        } catch (\Exception $e) {
            $this->addError('newMessage', 'Failed to send message: '.$e->getMessage());
        }
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification',
        ];
    }

    public function newChatMessageNotification($message)
    {
        $selectedUser = $this->selectedUserId ? User::find($this->selectedUserId) : null;
        if ($selectedUser && $message['sender_id'] == $selectedUser->id) {
            $messageObj = ChatMessage::find($message['id']);
            $this->messages[] = $messageObj ? $messageObj->toArray() : null;
            $this->dispatch('message-received');
        }
    }

    public function updatedSelectedUserId($value)
    {
        $this->loadMessages();
        $this->dispatch('chat-selected');
    }

    public function render()
    {
        $selectedUser = $this->selectedUserId ? User::find($this->selectedUserId) : null;
        return view('livewire.chat', [
            'selectedUser' => $selectedUser,
            
        ]);
    }
}