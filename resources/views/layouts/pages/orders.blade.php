@extends('layouts.pages.page')

@section('title') Orders in progress @stop

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
                <th>#Order</th>
                <th>Plate</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>
                        @forelse ($order->plate->ingredients as $ingredient)
                            <p>{{ $ingredient->name }} - {{ $ingredient->quantity }} </p>
                        @empty
                            <p>Ingredients Empty</p>
                        @endforelse
                    </td>
                    <td>{{ $order->status }}</td>
                </tr>
            @empty
                <p>No orders</p>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>

@stop
