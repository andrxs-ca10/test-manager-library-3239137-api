<?php

namespace App\Adapters\Persistence;

use Illuminate\Database\Eloquent\Model;

class UserEloquentModel extends Model
{
    protected $table = 'library.user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'username',
        'password_hash',
        'role',
    ];
}