@extends('layouts.app')

@section('content')
    <div class="container container-upload">
        <div class="row">
            <select class="custom-select" id="selectLanguage">
                <option value="">Language of audio file</option>
                <option value="en-US">en-US [English (United States)]</option>
                <option value="ja-JP">ja-JP [Japanese (Japan)]</option>
                <option value="ko-KR">ko-KR [Korean (South Korea)]</option>
            </select>
            <div class="file-loading">
                <input id="audio-input" name="audio[]" type="file" multiple>
            </div>
        </div>
    </div>
    <script>
        var audioUploadUrl = "{{ route('admin.audio.store') }}";
    </script>
@endsection