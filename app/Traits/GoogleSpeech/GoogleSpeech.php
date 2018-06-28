<?php
/**
 * Created by PhpStorm.
 * User: PhatLe
 * Date: 3/2/18
 * Time: 5:19 PM
 */

namespace App\Traits\GoogleSpeech;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Google\Cloud\Speech\SpeechClient;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Core\ExponentialBackoff;

trait GoogleSpeech
{
    protected $bucket;
    protected $saveType;
    protected $saveToCloud = 1;
    protected $saveToGoogleStorage = 2;

    protected function googleSpeechConvert(Request $request, Model $user)
    {
        $files = $request->file('audio');
        if (config('google.google_storage')) {
            $arrCloudURL = $this->saveToGoogleStorage($files);
        } else {
            $arrCloudURL = $this->saveToCloud($files);
        }

        if ($audioObj = $user->audioFiles()->create($arrCloudURL)) {
            if ($this->convertAudio($arrCloudURL, $audioObj, $request->input('language')))
                return true;
            return false;
        } else {
            return false;
        }
    }

    protected function saveToCloud($files)
    {
        $wavPath = $flacPath = '';
        $this->saveType = $this->saveToCloud;
        $today = Carbon::today();
        $path = 'audio/' . $today->format('Y/m/d');

        foreach ($files as $file) {
            if (!$file->isValid())
                continue;

            $fileName = $file->getClientOriginalName();
            $extensionFile = $file->getClientOriginalExtension();
            $cloundFileName = $today->timestamp . '_' . $this->random_string(15) . '.' . $extensionFile;
            $filePath = $file->storeAs($path, $cloundFileName, ['ContentType' => \File::mimeType($file)]);

            switch ($extensionFile) {
                case 'wav':
                    $wavPath = $filePath;
                case 'flac':
                    $flacPath = $filePath;
                    break;
            }

            $audio = [
                'name' => $fileName,
                'path' => $wavPath,
                'path_flac' => $flacPath,
                'extension' => $extensionFile,
            ];
        }

        return $audio;
    }

    protected function saveToGoogleStorage($files)
    {
        $wavPath = $flacPath = '';
        $this->saveType = $this->saveToGoogleStorage;
        $storage = $this->googleStorageClient();
        $this->bucket = $storage->bucket(config('google.google_storage_bucket_name'));

        $today = Carbon::today();
        $path = 'audio/' . $today->format('Y/m/d');

        foreach ($files as $file) {
            if (!$file->isValid())
                continue;

            $fileName = $file->getClientOriginalName();
            $extensionFile = $file->getClientOriginalExtension();
            $cloundFileName = $today->timestamp . '_' . $this->random_string(15) . '.' . $extensionFile;
            $filePath = $path . '/' . $cloundFileName;

            $this->bucket->upload(fopen($file->getRealPath(), 'r'), [
                'name' => $filePath,
                'predefinedAcl' => 'publicRead'
            ]);

            switch ($extensionFile) {
                case 'wav':
                    $wavPath = $filePath;
                    break;
                case 'flac':
                    $flacPath = $filePath;
                    break;
            }

            $audio = [
                'name' => $fileName,
                'path' => $wavPath,
                'path_flac' => $flacPath,
                'extension' => $extensionFile,
            ];
        }

        return $audio;
    }

    protected function convertAudio(array $arrCloudURL, $audioObj = '', $language = '')
    {
        $str = '';
        $transcript = [];
        # Instantiates a client
        $speech = $this->googleSpeechClient();

        # The audio file's encoding and sample rate
        switch ($arrCloudURL['extension']) {
            case 'wav':
                $path = $arrCloudURL['path'];
                $options = config('google.google_speech_options_convert.wav');
                break;
            case 'flac':
                $path = $arrCloudURL['path_flac'];
                $options = config('google.google_speech_options_convert.flac');
                break;
        }
        $options['languageCode'] = $language;


        if ($this->saveType == $this->saveToCloud) {
            $url = config('filesystems.disks.s3.bucket_url') . $path;

            # The audio file to transcribe
            $fileName = fopen($url, 'r');

            # Detects speech in the audio file
            $results = $speech->recognize($fileName, $options);
            foreach ($results as $result) {
                $alternative = $result->alternatives()[0];
                $str = $str . $alternative['transcript'] . "\r\n";
            }

            $transcript = [
                'result_convert' => $result->alternatives()[0]['transcript'],
            ];

        } elseif ($this->saveType == $this->saveToGoogleStorage) {
            $object = $this->bucket->object($path)->gcsUri();

            # Create the asyncronous recognize operation
            $operation = $speech->beginRecognizeOperation($object, $options);

            # Wait for the operation to complete
            $backoff = new ExponentialBackoff(20);
            $backoff->execute(function () use ($operation) {
                \Log::info('Waiting for operation to complete');
                $operation->reload();
                if (!$operation->isComplete()) {
                    throw new Exception('Job has not yet completed', 500);
                }
            });

            # Return results
            if ($operation->isComplete()) {
                $results = $operation->results();
                foreach ($results as $result) {
                    $alternative = $result->alternatives()[0];
                    $str = $str . $alternative['transcript'] . "\r\n";
                }

                $transcript = [
                    'result_convert' => $str,
                ];
            } else {
                return false;
            }
        }

        if ($audioObj->audioConvertResult()->create($transcript))
            return true;
        return false;
    }

    protected function googleSpeechClient()
    {
        $speech = new SpeechClient([
            'keyFile' => json_decode(file_get_contents(config('google.key_file')), true),
            'languageCode' => 'en-US',
        ]);

        return $speech;
    }

    protected function googleStorageClient()
    {
        $storage = new StorageClient([
            'keyFile' => json_decode(file_get_contents(config('google.key_file')), true)
        ]);

        return $storage;
    }

    private function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
}