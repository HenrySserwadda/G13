@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Recommended Products</h1>
    
    <div class="row">
        @foreach($recommendations as $product)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product ID: {{ $product['id'] }}</h5>
                    <p class="card-text">
                        <strong>Material:</strong> {{ $product['Material'] }}<br>
                        <strong>Size:</strong> {{ $product['Size'] }}<br>
                        <strong>Style:</strong> {{ $product['Style'] }}<br>
                        <strong>Color:</strong> {{ $product['Color'] }}
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection