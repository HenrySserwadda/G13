<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


// Accessor for avatar URL
public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        return asset('storage/avatars/'.$this->avatar);
    }
    return $this->generateDefaultAvatar();
}

// Generate default avatar
public function generateDefaultAvatar()
{
    $colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FF33A8', '#33FFF5'];
    $initials = '';
    $words = explode(' ', $this->name);
    
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
        if (strlen($initials) >= 2) break;
    }
    
    $colorIndex = crc32($this->name) % count($colors);
    $bgColor = $colors[$colorIndex];
    
    return "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'><rect width='100' height='100' rx='50' fill='$bgColor'/><text x='50' y='60' font-size='50' text-anchor='middle' fill='white'>$initials</text></svg>";
}


   public function redirectToDashboard()
{
    switch($this->category) {
        case 'staff':
            return '/dashboard/staff';
        case 'supplier':
            return '/dashboard/supplier';
        case 'wholesaler':
            return '/dashboard/wholesaler';
        case 'retailer':
            return '/dashboard/retailer';
        case 'systemadmin':
            return '/dashboard/systemadmin';
        default:
            return '/dashboard/customer';
    }
}
public function inventories()
{
    return $this->hasMany(Inventory::class);
}


}
