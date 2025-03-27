<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject  // Implement JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',           // Thêm trường gender
        'date_of_birth',    // Thêm trường date_of_birth
        'address',          // Thêm trường address
        'phone_number',
        'role',    // Thêm trường phone_number
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',  // Cast date_of_birth thành đối tượng Carbon
    ];

    /**
     * Quan hệ 1-1 với Profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);  // Quan hệ 1-1 với Profile
    }

    /**
     * Quan hệ 1-n với Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);  // Quan hệ 1-n với Orders
    }

    /**
     * Quan hệ 1-1 với Cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);  // Quan hệ 1-1 với Cart
    }

    /**
     * Tạo hoặc lấy giỏ hàng hiện tại của user
     */
    public function getOrCreateCart()
    {
        return $this->cart()->firstOrCreate(['user_id' => $this->id]);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Trả về khóa chính (thường là ID người dùng)
    }

    /**
     * Get custom claims for the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->roles->pluck('name'), // Trả về các vai trò của người dùng dưới dạng mảng
            'profile' => $this->profile,           // Trả về thông tin profile nếu cần
        ];
    }

    // Các phương thức khác như roles() có thể được sử dụng nếu cần
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    // }

}
