@extends('layouts.pages.page')

@section('title')
    Purchases Historical
@stop

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
                    <th>Product name</th>
                    <th>#Quantity</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->product_name }}</td>
                        <td>
                            {{ $purchase->quantity  }}
                        </td>
                        <td>{{ $purchase->description }}</td>
                    </tr>
                @empty
                    <p>No items</p>
                @endforelse
                </tbody>

            </table>
            {{ $purchases->setPath('/purchases')->appends(['page' => $purchases->currentPage(), 'pageSize' => $purchases->perPage()])->links() }}
        </div>
        @stop
