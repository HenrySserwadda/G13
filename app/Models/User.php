<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'category'
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

    public function redirectToDashboard(User $user)
    {
        switch($user->category) {
            case 'staff':
                return redirect()->route('dashboard.staff');
            case 'supplier':
                return redirect()->route('dashboard.supplier');
            case 'wholesaler':
                return redirect()->route('dashboard.wholesaler');
            case 'retailer':
                return redirect()->route('dashboard.retailer');
            case 'systemadmin':
                return redirect()->route('dashboard.systemadmin');
            default:
                return redirect()->route('dashboard.customer');
            
        }
    }
}
