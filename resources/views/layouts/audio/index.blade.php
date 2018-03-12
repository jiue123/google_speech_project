@extends('layouts.app')

@section('content')
    <div class="container container-upload">
        <div class="row">
            <div class="file-loading">
                <input id="audio-input" name="audio[]" type="file" multiple>
            </div>
        </div>
    </div>
    <script>
        var audioUploadUrl = "{{ route('admin.audio.store') }}";
    </script>
@endsection