<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'address',
        'phone_number',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date', // cast thành date
        'password' => 'hashed',
    ];

    /**
     * Định dạng date khi trả JSON cho frontend
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    /**
     * Quan hệ 1-1 với Profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Quan hệ 1-n với Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Quan hệ 1-1 với Cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Tự động tạo Cart nếu chưa có
     */
    public function getOrCreateCart()
    {
        return $this->cart()->firstOrCreate(['user_id' => $this->id]);
    }

    /**
     * Lấy ID cho JWT
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Thêm các claims custom vào JWT
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->roles->pluck('name'),
            'profile' => $this->profile,
        ];
    }

    /**
     * Quan hệ 1-n với Comment
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Quan hệ 1-n với Review
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
