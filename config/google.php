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
        ],
        'flac' =>[
            'encoding' => 'FLAC',
            'sampleRateHertz' => 16000,
        ],
    ],
    'google_storage_bucket_name' => env('STORAGE_BUCKET_NAME', 'Storage Bucket Name'),
    'google_storage' => env('USE_GOOGLE_STORAGE', false),
    'google_storage_bucket_url' => env('STORAGE_BUCKET_URL', '')
];