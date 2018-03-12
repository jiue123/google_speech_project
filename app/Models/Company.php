<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'alias',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
