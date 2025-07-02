<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Http\Controllers;
class User extends Authenticatable 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

   public function redirectToDashboard(User $user): string 
    {
        $category = trim(preg_replace('/[\r\n]+/', '', $user->category));

        switch ($category) {
            case 'staff':
                return route('dashboard.staff'); 
            case 'supplier':
                return route('dashboard.supplier'); 
            case 'wholesaler':
                if ($user->status == 'pending') {
                    return route('insertpdf'); 
                } else {
                    return route('dashboard.wholesaler'); 
                }
            case 'retailer':
                return route('dashboard.retailer'); 
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
