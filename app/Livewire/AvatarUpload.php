<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AvatarUpload extends Component
{
    use WithFileUploads;

    public $user;
    public $avatar;

    public function mount(User $user)
    {
         $this->user = auth()->user();
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'image|max:2048', // 2MB Max
        ]);

        $path = $this->avatar->store('avatars', 'public');
        $this->user->avatar = $path;
        $this->user->save();

        // Optionally emit event to refresh parent
        $this->dispatch('avatarUpdated');
    }

    public function render()
    {
        return view('livewire.avatar-upload');
    }
}