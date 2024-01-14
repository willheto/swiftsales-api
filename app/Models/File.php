<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $primaryKey = 'fileID';

    protected $fillable = [
        'fileName',
        'filePath',
    ];

    public static function getValidationRules()
    {
        return [
            'fileName' => ['string', 'required'],
            'filePath' => ['string', 'required'],
        ];
    }
}
