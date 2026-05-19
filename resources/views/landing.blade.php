@extends('layout')

@section('content')
    <!-- Header Section: Displays a welcoming message and a call-to-action button -->
    <header class="text-center py-5 bg-primary text-white">
        <h1>Welcome to Our Supermarket</h1>
        <p>Your one-stop shop for fresh groceries and essentials</p>
        <a href="{{ route('products.index') }}" class="btn btn-light">Shop Now</a>
    </header>

    <!-- Featured Products Section: Showcases product categories -->
    <section class="container py-5">
        <div class="row">
            <!-- Product Card: Fresh Produce -->
            <div class="col-md-4">
                <div class="card">
                    <img src="storage/products/stock_product_2.png" class="card-img-top" alt="Fresh Produce">
                    <div class="card-body">
                        <h5 class="card-title">Fresh Produce</h5>
                        <p class="card-text">Get the best quality fruits and vegetables.</p>
                    </div>
                </div>
            </div>
            <!-- Product Card: Dairy Products -->
            <div class="col-md-4">
                <div class="card">
                    <img src="storage/products/stock_product_3.png" class="card-img-top" alt="Dairy Products">
                    <div class="card-body">
                        <h5 class="card-title">Dairy Products</h5>
                        <p class="card-text">A variety of dairy options to keep you healthy.</p>
                    </div>
                </div>
            </div>
            <!-- Product Card: Bakery -->
            <div class="col-md-4">
                <div class="card">
                    <img src="storage/products/stock_product_0.png" class="card-img-top" alt="Bakery">
                    <div class="card-body">
                        <h5 class="card-title">Bakery</h5>
                        <p class="card-text">Freshly baked bread and delicious pastries.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
