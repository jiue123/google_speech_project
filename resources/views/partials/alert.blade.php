@if ($message = session('message'))
    <div class="alert alert-success customize-success animation-opacity" role="alert">
        <i class="glyphicon glyphicon-ok"></i>
        {{$message['value']}}
    </div>
@endif
@php
    Session::forget('message');
@endphp