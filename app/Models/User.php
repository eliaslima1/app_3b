<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['company_id', 'cpf', 'name', 'email', 'password', 'phone', 'is_verified', 'role'];

    protected $hidden = ['password'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
