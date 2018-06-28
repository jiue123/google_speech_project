@extends('layouts.app')

@section('content')
    @php
        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
        $count = $totalItems * ($page);
        $firstOrder = $count - ($totalItems - 1);
        $page = (count($data) == 1 && $count > $totalItems) ? $page - 1 : $page;

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
    <div class="container container-upload container-result-convert">
        <div class="row">
            <form id="filterForm" action="{{route('admin.listConvert.index')}}" method="get">
                {{ csrf_field() }}
                <div class="input-group float-md-right col-md-3 col-sm-12 col-xs-12 filterFrom">
                    <span class="input-group-addon">
                        <span>From</span>
                    </span>
                    <input type="text" name="dateFrom" id="dateFrom" class="form-control" value="{{$dateFrom}}">
                </div>
                <div class="input-group col-md-3 col-sm-12 col-xs-12 filterTo">
                    <span class="input-group-addon">
                        <span>To</span>
                    </span>
                    <input type="text" name="dateTo" id="dateTo" class="form-control" value="{{$dateTo}}">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="hidden-sm hidden-xs"></th>
                        <th>Name</th>
                        <th>Result</th>
                        <th id="sortDate" data-sort="{{route('admin.listConvert.index',[
                            'dateFrom' => $dateFrom,
                            'dateTo' => $dateTo,
                            'page' => $page,
                            'sort' => $nextSort
                        ])}}">Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $value)
                        <tr>
                            <td class="hidden-sm hidden-xs" scope="row">{{$firstOrder}}</td>
                            <td>{{$value->audioFile->name}}</td>
                            <td>
                                <div class="form-group">
                                    <textarea disabled class="form-control" rows="5">{{$value->result_convert}}</textarea>
                                    <a href="javascript:void(0)">
                                        <i class="glyphicon glyphicon-play-circle" data-toggle="modal"
                                           data-target=".bd-modal-sm{{$firstOrder}}"></i>
                                    </a>
                                    <div data-pause="{{$firstOrder}}" class="modal fade bd-modal-sm{{$firstOrder}}"
                                         tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="smallModalLabel">Audio</h4>
                                                    <div class="close" data-dismiss="modal" aria-label="Close">
                                                        <span data-pause="{{$firstOrder}}" aria-hidden="true">×</span>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <audio id="audio-{{$firstOrder}}" controls>
                                                        <source src="{{$cloudPath . $value->audioFile->path}}">
                                                    </audio>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{$value->created_at->format('M d Y')}}</td>
                            <td class="action-column" align="right">
                                <form id="deleteForm{{$value->id}}" action="{{route('admin.listConvert.destroy',[
                                    'id' => $value->id,
                                    'dateFrom' => $dateFrom,
                                    'dateTo' => $dateTo,
                                    'page' => $page,
                                    'sort' => $sort
                                ])}}" accept-charset="UTF-8" method="POST">
                                    {{method_field('DELETE')}}
                                    {{csrf_field()}}
                                    <a href="{{route('admin.listConvert.edit', ['id' => $value->id])}}">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <i data-content="{{$value->result_convert}}" data-filename="{{$value->audioFile->name}}"
                                           class="glyphicon glyphicon-download-alt"></i>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <i data-delete="{{$value->id}}" class="glyphicon glyphicon-floppy-remove"></i>
                                    </a>
                                    <a href="{{route('admin.textAnalyze.show', ['str' => $value->result_convert])}}">
                                        <i class="glyphicon glyphicon-indent-left"></i>
                                    </a>
                                </form>
                            </td>
                        </tr>
                        @php
                            $firstOrder++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <nav class="result-convert-pagination">
                {{$data->appends(['dateFrom' => $dateFrom, 'dateTo' => $dateTo])->links()}}
            </nav>
        </div>
    </div>
    <script>
        var $sort = '{{$sort}}';
    </script>
@endsection