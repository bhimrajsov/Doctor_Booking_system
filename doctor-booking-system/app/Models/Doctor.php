<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'id','first_name', 'last_name', 'email', 'phone', 'image', 'speciality',
        'languages', 'education', 'password', 'DOB', 'gender', 'device_token'
    ];

    protected $hidden = [
        'password'
    ];
}
