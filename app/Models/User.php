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
        'category',
        'user_id',
        'status',
        'gender'
    // Use 'user_id' as the user identification string, but do not use it for Eloquent relationships
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
        $path = $this->avatar;
        if (!str_starts_with($path, 'avatars/')) {
            $path = 'avatars/' . $path;
        }
        return asset('storage/' . $path);
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


public function inventories()
{
    return $this->hasMany(Inventory::class);
}

    public static function generateUserId(string $category){
        $prefix = strtoupper(substr($category,0,2));
        $lastUser = User::where('category', $category)
            ->whereNotNull('user_id')
            ->orderBy('user_id', 'desc')->first();

        if($lastUser && preg_match('/\d+/', $lastUser->user_id, $matches)){
            $lastNumber = (int)$matches[0];
        } else {
            $lastNumber = 0;
        }
        $nextNumber = str_pad($lastNumber+1, 4, '0', STR_PAD_LEFT);
        return $prefix.$nextNumber;
    }

    public function redirectToDashboard(): string 
    {
        $category = trim(preg_replace('/[\r\n]+/', '', $this->category));

        switch ($category) {
            case 'staff':
                return route('dashboard.staff'); 
            case 'supplier':
                return route('dashboard.supplier'); 
            case 'wholesaler':
                if ($this->status == 'pending') {
                    return route('insertpdf'); 
                } else {
                    return route('dashboard.wholesaler'); 
                }
            case 'retailer':
                return route('dashboard.wholesaler'); 
            case 'systemadmin':
                return route('dashboard.systemadmin'); 
            default:
                return route('dashboard.customer'); 
        }
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'user_id', 'user_id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'user_id', 'user_id');
    }
    public function staff(){
        return $this->belongsTo(Staff::class, 'user_id', 'user_id');
    }
    public function retailer(){
        return $this->belongsTo(Retailer::class, 'user_id', 'user_id');
    }
    public function wholesaler(){
        return $this->belongsTo(Wholesaler::class, 'user_id', 'user_id');
    }
    public function systemadmin(){
        return $this->belongsTo(Systemadmin::class,'user_id','user_id');
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(\App\Models\ChatMessage::class, 'sender_id');
    }
}
