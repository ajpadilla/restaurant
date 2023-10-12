@extends('layouts.pages.page')

@section('title') Plates @stop

@section('content')

    <div class="row">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div id="login-alert" class="alert alert-danger col-sm-12">{{ $error }}</div>
            @endforeach
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Plate</th>
                <th>Ingredients and Quantities</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($plates as $plate)
                <tr>
                    <td>{{ $plate->id }}</td>
                    <td>{{ $plate->name }}</td>
                    <td>
                    @forelse ($plate->ingredients as $ingredient)
                            <p>{{ $ingredient->name }} - {{ $ingredient->quantity }} </p>
                    @empty
                    <p>Ingredients Empty</p>
                    @endforelse
                    </td>
                </tr>
            @empty
                <p>No orders</p>
            @endforelse
            </tbody>
        </table>
    </div>
@stop
