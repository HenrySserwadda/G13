<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class Chat extends Component
{
    use WithFileUploads;

    public static string $layout = 'layouts.app';
    public $users;
    public $newMessage = '';
    public $messages = [];
    public $searchTerm = '';
    public $loginID;
    #[Url]
    public $selectedUserId = null;
    public $newFile;

    protected $rules = [
        'newMessage' => 'nullable|string|max:1000',
        'newFile' => 'nullable|file|max:10240', // 10MB max
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
        // Authorization check: ensure the selected user exists and is not the current user
        $selectedUser = User::find($userId);
        if (!$selectedUser || $selectedUser->id === Auth::id()) {
            $this->addError('selectedUserId', 'Invalid user selected.');
            return;
        }
        
        $this->selectedUserId = $userId;
        $this->loadMessages();
        $this->dispatch('chat-selected');
    }

    public function loadMessages()
    {
        if (!$this->selectedUserId) return;
        
        $selectedUser = User::find($this->selectedUserId);
        if (!$selectedUser || $selectedUser->id === Auth::id()) {
            $this->addError('selectedUserId', 'Invalid user selected.');
            return;
        }
        
        $messages = ChatMessage::with(['sender', 'receiver'])
            ->where(function($query) use ($selectedUser) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $selectedUser->id);
            })
            ->orWhere(function($query) use ($selectedUser) {
                $query->where('sender_id', $selectedUser->id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Normalize created_at to string
        $this->messages = $messages->map(function($msg) {
            $arr = $msg->toArray();
            $arr['created_at'] = $msg->created_at ? $msg->created_at->toDateTimeString() : null;
            return $arr;
        })->toArray();
    }

    public function submit()
    {
        $validated = $this->validate($this->rules, $this->validationMessages);
        $selectedUser = $this->selectedUserId ? User::find($this->selectedUserId) : null;

        // Authorization check: ensure the selected user exists and is not the current user
        if (!$selectedUser || $selectedUser->id === Auth::id()) {
            $this->addError('newMessage', 'Invalid recipient selected.');
            return;
        }
        if (empty($validated['newMessage']) && !$this->newFile) {
            $this->addError('newMessage', 'Message or file is required.');
            return;
        }

        $filePath = null;
        $fileType = null;
        $originalFileName = null;
        if ($this->newFile) {
            $filePath = $this->newFile->store('chat_uploads', 'public');
            $fileType = $this->newFile->getMimeType();
            $originalFileName = $this->newFile->getClientOriginalName();
        }

        try {
            $message = ChatMessage::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $selectedUser->id,
                'message' => $validated['newMessage'] ?? '',
                'file_path' => $filePath,
                'file_type' => $fileType,
                'original_file_name' => $originalFileName,
            ])->load('sender', 'receiver');
            $this->messages[] = $message->toArray();
            $this->reset(['newMessage', 'newFile']);
            $this->dispatch('message-sent');
            broadcast(new MessageSent(Auth::user(), $message));
        } catch (\Exception $e) {
            $this->addError('newMessage', 'Failed to send message: '.$e->getMessage());
        }
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},.message.sent" => 'newChatMessageNotification',
        ];
    }

    public function newChatMessageNotification($message)
    {
        // Authorization check: ensure the message is intended for the current user
        if ($message['receiver_id'] != Auth::id()) {
            return; // Ignore messages not intended for this user
        }
        
        $selectedUser = $this->selectedUserId ? User::find($this->selectedUserId) : null;
        if ($selectedUser && $message['sender_id'] == $selectedUser->id) {
            $this->messages[] = $message;
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
        $selectedUser = null;
        if ($this->selectedUserId) {
            $selectedUser = User::find($this->selectedUserId);
            // Authorization check: ensure the selected user exists and is not the current user
            if (!$selectedUser || $selectedUser->id === Auth::id()) {
                $selectedUser = null;
                $this->selectedUserId = null;
            }
        }
        
        return view('livewire.chat', [
            'selectedUser' => $selectedUser,
        ]);
    }
}