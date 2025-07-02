<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Http\Controllers;
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
        'userid'
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


public function inventories()
{
    return $this->hasMany(Inventory::class);
}


    public function userDetails(): array{//function has to be called by objects 
    // //and these objects should be the users themselves in order for their details to be seen on te profile icon on the dashboard
        //but I dont know how to
        return[
            'name'=> $this->name,
            'category'=>$this->category,
        ];
    }

    public static function generateUserId(string $category){
            $prefix=strtoupper(substr($category,0,1));
            $lastUser=User::where('category',$category)
            ->whereNotNull('userid')
            ->orderBy('userid','desc')->first();

            if($lastUser && preg_match('/\d+/',$lastUser->userid,$matches)){
                $lastNumber=(int)$matches[0];
            }
            else{
                $lastNumber=0;
            }
            $nextNumber=str_pad($lastNumber+1,4,'0',STR_PAD_LEFT);
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

    

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
