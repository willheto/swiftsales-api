<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Lead extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'leadID';

    /**
     * @var string
     */
    protected $foreignKey = 'userID';

    protected $fillable = [
        'userID',
        'businessID',
        'companyName',
        'contactPerson',
        'contactPhone',
        'contactEmail',
        'header',
        'description'
    ];

    public static function getValidationRules(array $fieldsToValidate): array
    {
        $validationRules =  [
            'businessID' => ['string'],
            'companyName' => ['string', 'required'],
            'contactPerson' => ['string'],
            'contactPhone' => ['string'],
            'contactEmail' => ['email'],
            'header' => ['string'],
            'description' => ['string']
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


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
