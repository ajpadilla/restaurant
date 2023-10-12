@extends('layouts.pages.page')

@section('title') Dashboard @stop

@section('content')
    <h1>Plates List To Buy</h1>
    <div class="row">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div id="login-alert" class="alert alert-danger col-sm-12">{{ $error }}</div>
            @endforeach
        @endif
    </div>
    <div class="row" id="main" >
        <div class="col-sm-12 col-md-12 well" id="content">

            <div class="btn-toolbar">
                <button class="btn btn-primary">New User</button>
                <button class="btn">Import</button>
                <button class="btn">Export</button>
            </div>
            <div class="well">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                        <th style="width: 36px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>adasdfasdf</td>
                        <td>adasdfasdf</td>
                        <td>adasdfasdf</td>
                        <td>
                            <a href="/" class="btn btn-primary"><i class="icon-pencil"></i>Buy</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
