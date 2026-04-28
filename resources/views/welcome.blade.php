@extends('layouts.app')

@section('content')
<!-- Hero Slider Section -->
<section class="hero-section mb-5">
    <div class="container-fluid px-0">
        @php
            use App\Models\Slider;
            $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        @endphp
        
        @if($sliders->count() > 0)
        <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($sliders as $key => $slider)
                <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ $slider->image }}" class="d-block w-100" alt="{{ $slider->title }}" style="height: 500px; object-fit: cover; width: 100%;">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 fw-bold">{{ $slider->title }}</h1>
                        <p class="lead">{{ $slider->subtitle }}</p>
                        @if($slider->button_text)
                        <a href="{{ $slider->button_link }}" class="btn btn-lg btn-primary">{{ $slider->button_text }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        @else
        <!-- Fallback if no sliders -->
        <div class="bg-primary text-white text-center py-5">
            <h1>Welcome to ProShop</h1>
            <p class="lead">Your one-stop destination for quality products</p>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
        </div>
        @endif
    </div>
</section>

<div class="container">
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <h2 class="mb-4 text-center">Featured Products</h2>
    <div class="row">
        @foreach($featuredProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @else
                <div class="bg-secondary text-white text-center py-5">No Image</div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-primary fw-bold">${{ number_format($product->price, 2) }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto slide every 5 seconds
    $('.carousel').carousel({
        interval: 5000
    });
});
</script>
@endpush