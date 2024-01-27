<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'userID';

    /**
     * @var string
     */
    protected $foreignKey = 'organizationID';

    protected $fillable = [
        'firstName', 'lastName', 'email', 'password', 'timeZone', 'userType', 'organization'
    ];

    protected $hidden = ['password'];

    public function getValidationRules(): array
    {
        return [
            'firstName' => ['string', 'required'],
            'lastName' => ['string', 'required'],
            'email' => ['email', 'required', 'unique:users'],
            'timeZone' => ['string', 'required'], // TODO: validate timezone
            'userType' => ['string', 'required', 'in:user,admin'],
            'organization' => ['string', 'nullable'],
            'password' => ['string', 'required', 'min:8']
        ];
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'userID', 'userID');
    }

    public function salesAppointments(): HasMany
    {
        return $this->hasMany(SalesAppointment::class, 'userID', 'userID');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organizationID', 'organizationID');
    }
}
