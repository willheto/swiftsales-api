<?php

namespace App\Models;

class Job extends BaseModel
{
    protected $primaryKey = 'jobID';
    protected $foreignKey = 'userID';

    protected $fillable = [
        'userID',
        'isSuccessful',
        'jobType',
        'status',
        'errorMessage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
