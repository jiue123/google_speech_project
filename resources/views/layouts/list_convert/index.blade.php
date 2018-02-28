@extends('layouts.app')

@section('content')
    <div class="container container-upload">
        <div class="row">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Date</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Hello.mp3</td>
                    <td>2018/02/01</td>
                    <td><button type="button" class="btn btn-primary">Download</button></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Hello.mp4</td>
                    <td>2018/02/02</td>
                    <td><button type="button" class="btn btn-primary">Download</button></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Hello.awm</td>
                    <td>2018/02/03</td>
                    <td><button type="button" class="btn btn-primary">Download</button></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection