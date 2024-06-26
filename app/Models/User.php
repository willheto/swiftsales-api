<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

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

    public static function getValidationRules(array $fieldsToValidate): array
    {
        $validationRules =  [
            'firstName' => ['string', 'required'],
            'lastName' => ['string', 'required'],
            'email' => ['email', 'required', 'unique:users'],
            'timeZone' => ['string', 'required'], // TODO: validate timezone
            'userType' => ['string', 'required', 'in:user,admin'],
            'organization' => ['string', 'nullable'],
            'password' => ['string', 'required', 'min:8']
        ];

        if (
            empty($fieldsToValidate)
        ) {
            return $validationRules;
        }

        // Filter the rules based on the posted fields
        $filteredRules = array_intersect_key($validationRules, $fieldsToValidate);

        return $filteredRules;
    }

    public function getFillableUserDataFromRequest(Request $request): array
    {
        if (isset($request->password)) {
            $request['password'] = $this->hashPassword($request['password']);
        }

        return $request->except('userID', 'userType', 'organizationID');
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
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
