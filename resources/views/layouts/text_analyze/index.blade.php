@extends('layouts.app')

@section('content')
    <div class="container container-upload container-text-analyze">
        <div class="row">
            <textarea disabled class="form-control" name="text_analyze" rows="20" style="resize: vertical;background: #ffffff;">
                @foreach ($data as $value)
                    {{$value[0]}}
                    {{$value[1]}}
                @endforeach
            </textarea>
        </div>
    </div>
@endsection