<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'password', 'company_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function audioFiles()
    {
        return $this->hasMany(AudioFile::class);
    }
}
