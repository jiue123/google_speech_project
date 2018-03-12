<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioFile extends Model
{
    protected $table = "audio_files";
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'path',
        'path_flac',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audioConvertResult()
    {
        return $this->hasOne(AudioConvertResult::class);
    }
}
