@extends('layouts.app')

@section('content')
    @php
        $cloudPath = config('filesystems.disks.s3.bucket_url');
    @endphp
    @if ($message = Session::get('message'))
    <div class="alert alert-success customize-success animation-opacity" role="alert">
        <i class="glyphicon glyphicon-ok"></i>
        {{$message['value']}}
    </div>
    @endif
    <div class="container container-upload container-result-convert-edit">
        <form action="{{route('admin.listConvert.update', ['id' => $data->id])}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="row">
                <div class="form-group">
                    <label for="inputEmail4">Result Convert</label>
                    <textarea class="form-control" name="result_convert" rows="10">{{$data->result_convert}}</textarea>
                </div>
                <div class="form-group audio">
                    <audio controls>
                        <source src="{{$cloudPath . $data->audioFile->path}}">
                    </audio>
                </div>
                <button type="submit" class="btn btn-primary">Edit</button>
            </div>
        </form>
    </div>
@endsection