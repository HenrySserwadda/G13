<?php



namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class Chat extends Component
{
    public $users;
    public $selectedUser;
    public $newMessage = '';
    public $messages;
    public $searchTerm = '';
    public $loginID;

    // Define validation rules
    protected $rules = [
        'newMessage' => 'required|string|max:1000'
    ];

    // Custom validation messages
    protected $validationMessages = [
        'newMessage.required' => 'Message cannot be empty',
        'newMessage.max' => 'Message is too long (max 1000 characters)'
    ];

    public function mount()
    {
        $this->loadUsers();
        if ($this->users->isNotEmpty()) {
            $this->selectedUser = $this->users->first();
            $this->loadMessages();
            $this->loginID = Auth::id();
        }
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

    public function updatedSearchTerm()
    {
        $this->loadUsers();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->loadMessages();
        $this->dispatch('chat-selected');
    }

    public function loadMessages()
    {
        if (!$this->selectedUser) return;
        
        $this->messages = ChatMessage::with(['sender', 'receiver'])
            ->where(function($query) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $this->selectedUser->id);
            })
            ->orWhere(function($query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function submit()
    {
        // Validate with custom error messages
        $validated = $this->validate($this->rules, $this->validationMessages);

        try {
            $message = ChatMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $this->selectedUser->id,
                'message' => $validated['newMessage']
            ])->load('sender', 'receiver');

            $this->messages->push($message);
            $this->reset('newMessage');
            $this->dispatch('message-sent');
            
        } catch (\Exception $e) {
            $this->addError('newMessage', 'Failed to send message: '.$e->getMessage());
        }
       event(new MessageSent(auth()->user(), $message));

    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification',
        ];
    }

    public function newChatMessageNotification($message)
    {
        /*if ($message['sender_id'] ==$this->selectedUser->id) {
            $messageObj = ChatMessage::find($message['id']);
             $this->messages->push($messageObj);
        }*/

        //$message = ChatMessage::find($event['id']);
        //if ($message) {
          //  $this->messages->push($message);
          //  $this->dispatch('message-received', ['message' => $message]);
        //}
        if ($message['sender_id'] == $this->selectedUser->id) {
        $messageObj = ChatMessage::find($message['id']);
        $this->messages->push($messageObj);
        //$this->dispatch('message-received');}
    }}

    public function render()
    {
        return view('livewire.chat')
            ->layout('layouts.app');
    }
}