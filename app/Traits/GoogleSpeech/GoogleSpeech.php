<?php
/**
 * Created by PhpStorm.
 * User: PhatLe
 * Date: 3/2/18
 * Time: 5:19 PM
 */

namespace App\Traits\GoogleSpeech;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Google\Cloud\Speech\SpeechClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait GoogleSpeech
{
    protected function googleSpeechConvert(Request $request, Model $user)
    {
        $files = $request->file('audio');
        $arrCloudURL = $this->saveToCloud($files);

        if ($audioObj = $user->audioFiles()->create($arrCloudURL)) {
            if ($this->convertAudio($arrCloudURL, $audioObj))
                return true;
            return false;
        } else {
            return false;
        }
    }

    protected function saveToCloud($files)
    {
        $today = Carbon::today();
        $path = 'audio/' . $today->format('Y/m/d');

        foreach ($files as $file) {
            if (!$file->isValid())
                continue;

            $fileName = $file->getClientOriginalName();
            $extensionFile = $file->getClientOriginalExtension();
            $cloundFileName = $today->timestamp . '_' . $this->random_string(15) . '.' . $extensionFile;
            $filePath = $file->storeAs($path, $cloundFileName, ['ContentType' => \File::mimeType($file)]);

            $audio = [
                'name' => $fileName,
                'path' => $filePath,
                'extension' => $extensionFile,
            ];
        }

        return $audio;
    }

    protected function convertAudio(array $arrCloudURL, $audioObj = '')
    {
        $transcript = [];
        # Instantiates a client
        $speech = $this->googleSpeechClient();

        # The audio file's encoding and sample rate
        if ($arrCloudURL['extension'] === 'wav') {
            $options = config('google.google_speech_options_convert.wav');
        } else {
            $options = config('google.google_speech_options_convert.flac');
        }

        $url = config('filesystems.disks.azure.blob_service_url') . '/' .
            config('filesystems.disks.azure.container') . '/' . $arrCloudURL['path'];

        # The audio file to transcribe
        $fileName = fopen($url, 'r');

        # Detects speech in the audio file
        $results = $speech->recognize($fileName, $options);
        foreach ($results as $result) {
            $transcript = [
                'result_convert' => $result->alternatives()[0]['transcript'],
            ];
        }
        if ($audioObj->audioConvertResult()->create($transcript))
            return true;
        return false;
    }

    protected function  googleSpeechClient()
    {
        $speech = new SpeechClient([
            'keyFile' => json_decode(file_get_contents(config('google.key_file')), true),
            'languageCode' => 'en-US',
        ]);

        return $speech;
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