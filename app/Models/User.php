<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class);
    }

    // public function courses()
    // {
    //     return $this->hasMany(Course::class, 'instructor_id');
    // }

    public function courses()
{
    return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id');
}


    public function threads()
    {
        return $this->hasMany(Thread::class, 'instructor_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user');
    }

    
    
}
