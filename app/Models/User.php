<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Role;
use App\Models\Order;

class User extends Authenticatable
{
  
    use HasFactory, Notifiable, SoftDeletes;
    
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'password',
        'phone',
        'role_id',
        'created_at',
        'updated_at',
    ];

    protected $dates = ['deleted_at'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
