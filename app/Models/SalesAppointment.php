<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SalesAppointment extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'salesAppointmentID';
    protected $table = 'salesAppointments';
    protected $foreignKey = ['userID', 'leadID'];


    protected $fillable = [
        'userID',
        'leadID',
        'timeStart',
        'timeEnd',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'leadID', 'leadID');
    }
}
