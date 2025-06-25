<div>
    <form wire:submit.prevent="updatedAvatar">
        <label for="avatar-upload">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="avatar-img" style="cursor:pointer;" alt="Avatar">
            @else
                <img src="{{ generateAvatar($user->name) }}" class="avatar-img" style="cursor:pointer;" alt="Avatar">
            @endif
        </label>
        <input type="file" id="avatar-upload" wire:model="avatar" style="display:none;">
        @error('avatar') <span class="error">{{ $message }}</span> @enderror
    </form>
</div>