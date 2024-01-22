<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Auth\Authorizable;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'userID';

    protected $fillable = [
        'firstName', 'lastName', 'email', 'password', 'timeZone'
    ];

    protected $doNotUpdate = ['email'];
    protected $hidden = ['password'];

    public function getValidationRules()
    {
        return [
            'firstName' => ['string', 'required'],
            'lastName' => ['string', 'required'],
            'email' => ['email', 'required', 'unique:users'],
            'timeZone' => ['string', 'required'], // TODO: validate timezone
            'password' => ['string', 'required', 'min:8']
        ];
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'userID', 'userID');
    }
    public function salesAppointments()
    {
        return $this->hasMany(SalesAppointment::class, 'userID', 'userID');
    }
}
