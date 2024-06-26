<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesAppointmentFile extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'salesAppointmentFileID';
    protected $table = 'salesAppointmentFiles';

    protected $fillable = [
        'fileID',
        'salesAppointmentID',
    ];

    public static function getValidationRules(array $fieldsToValidate): array
    {
        $validationRules =  [
            'fileID' => ['integer', 'required'],
            'salesAppointmentID' => ['integer', 'required'],
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

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'fileID', 'fileID');
    }

    public function salesAppointment(): BelongsTo
    {
        return $this->belongsTo(SalesAppointment::class, 'salesAppointmentID', 'salesAppointmentID');
    }
}
