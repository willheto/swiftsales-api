<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesAppointmentFile extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'salesAppointmentFileID';
    protected $table = 'salesAppointmentFiles';

    protected $fillable = [
        'fileID',
        'salesAppointmentID',
    ];

    public static function getValidationRules()
    {
        return [
            'fileID' => ['integer', 'required'],
            'salesAppointmentID' => ['integer', 'required'],
        ];
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'fileID', 'fileID');
    }

    public function salesAppointment()
    {
        return $this->belongsTo(SalesAppointment::class, 'salesAppointmentID', 'salesAppointmentID');
    }
}
