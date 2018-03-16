<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioConvertResult extends Model
{
    protected $table = "audio_convert_results";
    public $timestamps = true;

    protected $fillable = [
        'audio_file_id',
        'result_convert',
    ];

    public function audioFile()
    {
        return $this->belongsTo(AudioFile::class);
    }
}
