<?php
/**
 * Created by PhpStorm.
 * User: PhatLe
 * Date: 3/2/18
 * Time: 3:09 PM
 */

return [
    'project_id' => env('GOOGLE_PROJECT_ID', 'Project ID Of Speech Api'),
    'key_file' => base_path(env('GOOGLE_APPLICATION_CREDENTIALS', '')),
    'google_speech_options_convert' => [
        'wav' => [
            'encoding' => 'MULAW',
            'sampleRateHertz' => 8000,
            'languageCode' => 'ja-JP',
        ],
        'flac' =>[
            'encoding' => 'FLAC',
            'sampleRateHertz' => 16000,
            'languageCode' => 'ja-JP',
        ],
    ]
];