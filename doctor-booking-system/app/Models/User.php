<?php

namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes, AuthenticableTrait;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'image',
        'DOB',
        'address',
        'gender',
        'height',
        'weight',
        'password',
        'device_token',
    ];

    protected $hidden = [
        'password',
    ];
}
