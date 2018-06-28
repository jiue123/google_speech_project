@extends('layouts.app')

@section('content')
    @php
        if (config('google.google_storage')) {
            $cloudPath = config('google.google_storage_bucket_url') . config('google.google_storage_bucket_name') . '/';
        } else {
            switch (config('filesystems.default')) {
                case 's3':
                    $cloudPath = config('filesystems.disks.azure.blob_service_url') . config('filesystems.disks.azure.container') . '/';
                    break;
                case 'azure':
                    $cloudPath = config('filesystems.disks.s3.bucket_url');
                    break;
            }
        }
    @endphp
    @include('partials.alert')
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