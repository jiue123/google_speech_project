@extends('layouts.app')

@section('content')
    @php
        $dateFrom = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : '';
        $dateTo = isset($_GET['dateTo']) ? $_GET['dateTo'] : '';
    @endphp
    @include('partials.alert')
    <div class="container container-upload container-result-convert">
        <div class="row">
            <form id="filterForm" action="{{route('admin.listConvert.index')}}" method="get">
                {{ csrf_field() }}
                <div class="input-group float-md-right col-md-3 col-sm-12 col-xs-12 filterFrom">
                    <span class="input-group-addon" onclick="resultConvertFilter()">
                        <span>From</span>
                    </span>
                    <input type="text" name="dateFrom" id="dateFrom" class="form-control" value="{{$dateFrom}}">
                </div>
                <div class="input-group col-md-3 col-sm-12 col-xs-12 filterTo">
                    <span class="input-group-addon" onclick="resultConvertFilter()">
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
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
                        $count = $totalItems * ($page);
                        $firstOrder = $count - ($totalItems - 1);
                        $cloudPath = config('filesystems.disks.s3.bucket_url');
                        $page = (count($data) == 1 && $count > $totalItems) ? $page - 1 : $page;
                    @endphp
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
                                    <div class="modal fade bd-modal-sm{{$firstOrder}}" tabindex="-1" role="dialog"
                                         aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="mySmallModalLabel">Audio</h4>
                                                    <div onclick="pause({{$firstOrder}})" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
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
                                ])}}" accept-charset="UTF-8" method="POST">
                                    {{method_field('DELETE')}}
                                    {{csrf_field()}}
                                    <a href="{{route('admin.listConvert.edit', ['id' => $value->id])}}">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <a href="#"><i class="glyphicon glyphicon-download-alt"></i></a>
                                    <a href="javascript:void(0)" onclick="confirmDelete({{$value->id}})">
                                        <i class="glyphicon glyphicon-floppy-remove"></i>
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
        function pause(i) {
            document.getElementById("audio-" + i).pause();
        }
        function confirmDelete(i) {
            if (confirm('Are you sure want to delete?'))
                document.getElementById("deleteForm" + i).submit();
            return false;
        }
    </script>
@endsection