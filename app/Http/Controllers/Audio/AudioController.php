<?php

namespace App\Http\Controllers\Audio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\GoogleSpeech\ConvertAudio;
use App\Traits\SaveFile\SaveFile;
use Google\Cloud\Speech\SpeechClient;

class AudioController extends Controller
{
    use ConvertAudio, SaveFile;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.audio.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = $request->file('audio');
        foreach ($files as $file) {
            return response()->json($file->extension());
        }
//        if ($request->hasFile('audio')) {
//            $files = $this->SaveFile($request);
//            $this->convertAudio();
//        }
//
//        # Instantiates a client
//        $speech = new SpeechClient([
//            'keyFile' => config('google.key_file'),
//            'projectId' => config('google.project_id'),
//            'languageCode' => 'en-US',
//        ]);
//
//        # The name of the audio file to transcribe
//        $fileName = __DIR__ . '/resources/audio.raw';
//
//        # The audio file's encoding and sample rate
//        $options = config('google.google_speech_options_convert.wav');
//
//        # Detects speech in the audio file
//        $results = $speech->recognize(fopen($fileName, 'r'), $options);
//
//        foreach ($results as $result) {
//            echo 'Transcription: ' . $result->alternatives()[0]['transcript'] . PHP_EOL;
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
