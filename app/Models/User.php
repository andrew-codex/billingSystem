<?php

namespace App\Models;
use App\Models\RolePermission;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    protected $appends = ['full_address'];
    protected $fillable = [
        'first_name',
        'last_name' ,
        'middle_name',
        'suffix',
        'email',
        'password',
        'phone',
        'address',
        'status',
        'role',
        'archived',
          'city_name','city_code',
        'barangay_name','barangay_code',
        'must_change_password'
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




public function hasPermission($permission)
    {
        $rolePermission = RolePermission::where('role', $this->role)->first();

        if (!$rolePermission) {
            return false;
        }

        
        return in_array($permission, $rolePermission->permissions ?? []);
    }


    public function getFullAddressAttribute()
    {
       
        $cities = collect(json_decode(file_get_contents(public_path('json/city.json')), true));
        $barangays = collect(json_decode(file_get_contents(public_path('json/barangay.json')), true));

        $barangay = $barangays->firstWhere('brgy_code', $this->barangay_code)['brgy_name'] ?? null;
        $city = $cities->firstWhere('city_code', $this->city_code)['city_name'] ?? null;
     

        return collect([$barangay, $city])
            ->filter()
            ->join(', ');
    }

 

}
