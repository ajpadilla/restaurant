@extends('layouts.pages.page')

@section('title')
    Ingredients in warehouse
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

        </form>
        <div class="well">
            <table class="table">
                <thead>
                <tr>
                    <th>#Item</th>
                    <th>name</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($ingredients as $ingredient)
                    <tr>
                        <td>{{ $ingredient->id }}</td>
                        <td>
                            {{ $ingredient->name }}
                        </td>
                        <td>{{ $ingredient->quantity }}</td>
                    </tr>
                @empty
                    <p>No items</p>
                @endforelse
                </tbody>
            </table>
        </div>
        @stop
