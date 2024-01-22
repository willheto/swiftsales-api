<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesAppointment extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'salesAppointmentID';
    protected $table = 'salesAppointments';
    protected $foreignKey = ['userID', 'leadID'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'isCustomerAllowedToShareFiles' => 'boolean',
    ];

    protected $fillable = [
        'userID',
        'leadID',
        'timeStart',
        'timeEnd',
        'notes',
        'meetingUrl',
        'meetingExpiryTime',
        'isCustomerAllowedToShareFiles'
    ];

    protected $salesAppointmentFiles;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'leadID', 'leadID');
    }

    public function salesAppointmentFiles(): HasMany
    {
        return $this->hasMany(SalesAppointmentFile::class, 'salesAppointmentID', 'salesAppointmentID')->with('file');
    }

    public function files(): HasManyThrough
    {
        return $this->hasManyThrough(File::class, SalesAppointmentFile::class, 'salesAppointmentID', 'fileID', 'salesAppointmentID', 'fileID');
    }
}
