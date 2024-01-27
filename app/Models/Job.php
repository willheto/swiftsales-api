<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends BaseModel
{
    protected $primaryKey = 'jobID';

    /**
     * @var string
     */
    protected $foreignKey = 'userID';

    protected $fillable = [
        'userID',
        'isSuccessful',
        'jobType',
        'status',
        'errorMessage',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
