@extends('layouts.app')

@section('content')
<style>
    /* ========== GOOGLE FONTS ========== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    * {
        font-family: 'Inter', sans-serif;
    }
    
    /* ========== HERO SLIDER ========== */
     /* SLIDER CONTAINER */
    .hero-slider {
        position: relative;
        margin-top: -24px;
        overflow: hidden;
    }
    
    /* CAROUSEL ITEMS */
    .hero-slider .carousel {
        position: relative;
    }
    
    .hero-slider .carousel-item {
        height: 85vh;
        min-height: 550px;
        position: relative;
    }
    
    /* SLIDER IMAGES */
    .hero-slider .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.7);
        transition: transform 8s ease;
    }
    
    .hero-slider .carousel-item.active img {
        transform: scale(1.05);
    }
    
    /* OVERLAY GRADIENT */
    .hero-slider .carousel-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }
    
    /* CAPTION STYLES */
    .hero-slider .carousel-caption {
        bottom: 50%;
        transform: translateY(50%);
        text-align: left;
        left: 8%;
        right: auto;
        z-index: 2;
    }
    
    /* ANIMATED TITLE */
    .hero-slider .carousel-caption h1 {
        font-size: 64px;
        font-weight: 800;
        margin-bottom: 20px;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        position: relative;
        animation: slideInLeft 0.8s ease;
    }
    
    /* ANIMATED SUBTITLE */
    .hero-slider .carousel-caption p {
        font-size: 20px;
        margin-bottom: 30px;
        color: rgba(255,255,255,0.9);
        animation: slideInLeft 1s ease;
    }
    
    /* ANIMATED BUTTON */
    .hero-slider .carousel-caption .btn {
        padding: 14px 40px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 50px;
        background: linear-gradient(135deg, #007bff, #00c6ff);
        border: none;
        color: white;
        transition: all 0.3s;
        animation: slideInLeft 1.2s ease;
        box-shadow: 0 5px 15px rgba(0,123,255,0.3);
    }
    
    .hero-slider .carousel-caption .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0,123,255,0.4);
    }
    
    /* INDICATORS */
    .hero-slider .carousel-indicators {
        bottom: 30px;
        z-index: 2;
    }
    
    .hero-slider .carousel-indicators button {
        width: 50px;
        height: 3px;
        background: white;
        opacity: 0.5;
        transition: all 0.3s;
        border: none;
        margin: 0 5px;
    }
    
    .hero-slider .carousel-indicators button.active {
        width: 80px;
        opacity: 1;
        background: #007bff;
    }
    
    /* CONTROLS */
    .hero-slider .carousel-control-prev,
    .hero-slider .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s;
    }
    
    .hero-slider:hover .carousel-control-prev,
    .hero-slider:hover .carousel-control-next {
        opacity: 1;
    }
    
    .hero-slider .carousel-control-prev {
        left: 30px;
    }
    
    .hero-slider .carousel-control-next {
        right: 30px;
    }
    
    .hero-slider .carousel-control-prev-icon,
    .hero-slider .carousel-control-next-icon {
        width: 40px;
        height: 40px;
        background-size: 60%;
    }
    
    /* BADGE ON SLIDER */
    .slider-badge {
        position: absolute;
        top: 30px;
        right: 30px;
        background: rgba(220,53,69,0.9);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        z-index: 2;
        backdrop-filter: blur(5px);
        animation: pulse 2s infinite;
    }
    
    /* ANIMATIONS */
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .hero-slider .carousel-caption h1 {
            font-size: 48px;
        }
        .hero-slider .carousel-caption p {
            font-size: 18px;
        }
    }
    
    @media (max-width: 992px) {
        .hero-slider .carousel-item {
            height: 70vh;
            min-height: 450px;
        }
        .hero-slider .carousel-caption h1 {
            font-size: 36px;
        }
        .hero-slider .carousel-caption p {
            font-size: 16px;
        }
        .hero-slider .carousel-caption .btn {
            padding: 10px 30px;
            font-size: 14px;
        }
    }
    
    @media (max-width: 768px) {
        .hero-slider .carousel-item {
            height: 60vh;
            min-height: 400px;
        }
        .hero-slider .carousel-caption {
            bottom: 50%;
            transform: translateY(50%);
            text-align: center;
            left: 5%;
            right: 5%;
        }
        .hero-slider .carousel-caption h1 {
            font-size: 28px;
        }
        .hero-slider .carousel-caption p {
            font-size: 14px;
        }
        .slider-badge {
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            font-size: 10px;
        }
        .hero-slider .carousel-control-prev,
        .hero-slider .carousel-control-next {
            width: 40px;
            height: 40px;
        }
    }
    
    @media (max-width: 576px) {
        .hero-slider .carousel-item {
            height: 50vh;
            min-height: 350px;
        }
        .hero-slider .carousel-caption h1 {
            font-size: 22px;
        }
        .hero-slider .carousel-caption p {
            font-size: 11px;
        }
        .hero-slider .carousel-caption .btn {
            padding: 8px 20px;
            font-size: 12px;
        }
        .hero-slider .carousel-indicators button {
            width: 30px;
        }
        .hero-slider .carousel-indicators button.active {
            width: 50px;
        }
    }
    /* ========== SECTION TITLES ========== */
    .section-title {
        text-align: center;
        margin-bottom: 60px;
        position: relative;
    }
    .section-title h2 {
        font-size: 40px;
        font-weight: 800;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    .section-title h2:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #007bff, #00c6ff);
        border-radius: 2px;
    }
    .section-title p {
        color: #666;
        font-size: 18px;
    }
    
    /* ========== CATEGORY CARDS ========== */
    .category-section {
        padding: 80px 0;
        background: #f8f9fa;
    }
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .category-card {
        background: white;
        border-radius: 20px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .category-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0,123,255,0.1), transparent);
        transition: left 0.5s;
    }
    .category-card:hover:before {
        left: 100%;
    }
    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .category-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        transition: all 0.3s;
    }
    .category-card:hover .category-icon {
        transform: scale(1.1);
    }
    .category-card h5 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .category-card p {
        color: #666;
        font-size: 14px;
    }
    
    /* ========== PRODUCT CARDS ========== */
    .featured-section {
        padding: 80px 0;
        background: white;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0,0,0,0.1);
    }
    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
    }
    .badge-new {
        background: #28a745;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-sale {
        background: #dc3545;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .product-img {
        position: relative;
        overflow: hidden;
        background: #f5f5f5;
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-img img {
        transform: scale(1.1);
    }
    .product-actions {
        position: absolute;
        bottom: -60px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 12px;
        padding: 12px;
        background: rgba(0,0,0,0.85);
        transition: bottom 0.3s ease;
    }
    .product-card:hover .product-actions {
        bottom: 0;
    }
    .action-btn {
        background: white;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #333;
        transition: all 0.3s;
        cursor: pointer;
        font-size: 16px;
    }
    .action-btn:hover {
        background: #007bff;
        color: white;
        transform: scale(1.1);
    }
    .product-body {
        padding: 18px;
    }
    .product-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        height: 40px;
    }
    .product-title a {
        color: #333;
        text-decoration: none;
    }
    .product-title a:hover {
        color: #007bff;
    }
    .product-rating {
        margin-bottom: 10px;
    }
    .product-rating i {
        font-size: 12px;
        margin-right: 2px;
    }
    .product-price {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .current-price {
        font-size: 20px;
        font-weight: 800;
        color: #dc3545;
    }
    .old-price {
        font-size: 14px;
        color: #999;
        text-decoration: line-through;
    }
    .original-price {
        font-size: 16px;
        font-weight: 600;
        color: #007bff;
    }
    
    /* ========== FLASH SALE ========== */
    .flash-sale-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
    }
    .flash-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .flash-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .flash-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .flash-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #dc3545;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        z-index: 1;
    }
    .flash-img {
        height: 250px;
        overflow: hidden;
    }
    .flash-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .flash-card:hover .flash-img img {
        transform: scale(1.05);
    }
    .flash-body {
        padding: 20px;
    }
    .flash-price {
        margin: 15px 0;
    }
    .flash-price .sale {
        font-size: 24px;
        font-weight: 800;
        color: #dc3545;
    }
    .flash-price .original {
        font-size: 16px;
        color: #999;
        text-decoration: line-through;
        margin-left: 10px;
    }
    .flash-timer {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 15px;
    }
    .flash-timer span {
        font-size: 20px;
        font-weight: 800;
        color: #dc3545;
    }
    .btn-flash {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        border: none;
        border-radius: 50px;
        padding: 12px;
        color: white;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
    }
    .btn-flash:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220,53,69,0.4);
    }
    
    /* ========== BANNERS ========== */
    .banner-section {
        padding: 80px 0;
        background: white;
    }
    .banner-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .banner-card {
        padding: 50px 40px;
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
        cursor: pointer;
    }
    .banner-card:hover {
        transform: scale(1.02);
    }
    .banner-card h3 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    .banner-card h2 {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 15px;
    }
    .banner-card .btn-banner {
        background: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        margin-top: 20px;
        transition: all 0.3s;
    }
    .banner-card .btn-banner:hover {
        transform: translateX(5px);
    }
    .banner-icon {
        position: absolute;
        right: 30px;
        bottom: 30px;
        opacity: 0.2;
        font-size: 80px;
    }
    
    /* ========== NEWSLETTER ========== */
    .newsletter-section {
        padding: 100px 20px;
        background: linear-gradient(135deg, #2f2fb0 0%, #89a5f3 100%);
        position: relative;
        overflow: hidden;
    }
    .newsletter-section:before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(191, 200, 206, 0.05) 1%, transparent 1%);
        background-size: 50px 50px;
        animation: grain 30s linear infinite;
    }
    @keyframes grain {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }
    .newsletter-box {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    .newsletter-box h2 {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 15px;
    }
    .newsletter-box p {
        font-size: 18px;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    .newsletter-form {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .newsletter-form input {
        flex: 1;
        min-width: 280px;
        padding: 16px 25px;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        background: white;
        transition: all 0.3s;
    }
    .newsletter-form input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.3);
    }
    .newsletter-form button {
        padding: 16px 40px;
        border: none;
        border-radius: 50px;
        background: linear-gradient(135deg, #007bff, #00c6ff);
        color: white;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    .newsletter-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,123,255,0.3);
    }
    
    /* ========== FOOTER ========== */
    .footer-section {
        background: #0a0a0a;
        padding: 70px 20px 30px;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .footer-col h4, .footer-col h5 {
        color: white;
        margin-bottom: 20px;
        font-weight: 700;
    }
    .footer-col p {
        color: #aaa;
        line-height: 1.6;
    }
    .social-links {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
    .social-links a {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s;
    }
    .social-links a:hover {
        background: #007bff;
        transform: translateY(-3px);
    }
    .footer-links {
        list-style: none;
        padding: 0;
    }
    .footer-links li {
        margin-bottom: 12px;
    }
    .footer-links a {
        color: #aaa;
        text-decoration: none;
        transition: color 0.3s;
    }
    .footer-links a:hover {
        color: #007bff;
        padding-left: 5px;
    }
    .footer-contact {
        list-style: none;
        padding: 0;
    }
    .footer-contact li {
        color: #aaa;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .footer-contact i {
        width: 20px;
        color: #007bff;
    }
    
    /* ========== RESPONSIVE ========== */
    @media (max-width: 1200px) {
        .category-grid, .products-grid, .flash-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 992px) {
        .hero-slider .carousel-caption h1 {
            font-size: 40px;
        }
        .category-grid, .products-grid, .flash-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .hero-slider .carousel-item {
            height: 60vh;
            min-height: 400px;
        }
        .hero-slider .carousel-caption {
            bottom: 20%;
            text-align: center;
            left: 5%;
            right: 5%;
        }
        .hero-slider .carousel-caption h1 {
            font-size: 28px;
        }
        .hero-slider .carousel-caption p {
            font-size: 14px;
        }
        .section-title h2 {
            font-size: 28px;
        }
        .category-grid, .products-grid, .flash-grid {
            grid-template-columns: 1fr;
        }
        .banner-grid {
            grid-template-columns: 1fr;
        }
        .footer-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .social-links {
            justify-content: center;
        }
        .footer-contact li {
            justify-content: center;
        }
        .newsletter-box h2 {
            font-size: 28px;
        }
    }
    
    /* ========== TRUST BADGES ========== */
    .trust-section {
        padding: 40px 0;
        background: white;
        border-top: 1px solid #eee;
    }
    .trust-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .trust-item {
        text-align: center;
    }
    .trust-item i {
        font-size: 32px;
        color: #007bff;
        margin-bottom: 10px;
    }
    .trust-item h6 {
        font-weight: 700;
        margin-bottom: 5px;
    }
    .trust-item p {
        font-size: 12px;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .trust-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- ========== HERO SLIDER ========== -->
<section class="hero-slider">
    @php 
        use App\Models\Slider;
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
    @endphp
    
    @if($sliders->count() > 0)
    <div id="mainSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            @foreach($sliders as $key => $slider)
            <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($sliders as $key => $slider)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img src="{{ $slider->image }}" alt="{{ $slider->title }}">
                <div class="slider-badge">
                    <i class="fas fa-bolt"></i> Limited Time Offer
                </div>
                <div class="carousel-caption">
                    <h1>{{ $slider->title }}</h1>
                    <p>{{ $slider->subtitle }}</p>
                    @if($slider->button_text)
                    <a href="{{ $slider->button_link }}" class="btn">
                        {{ $slider->button_text }} <i class="fas fa-arrow-right ms-2"></i>
                    </a>
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
    @endif
</section>
<!-- ========== TRUST BADGES ========== -->
<section class="trust-section">
    <div class="trust-grid">
        <div class="trust-item">
            <i class="fas fa-truck"></i>
            <h6>Free Shipping</h6>
            <p>On orders over $50</p>
        </div>
        <div class="trust-item">
            <i class="fas fa-shield-alt"></i>
            <h6>Secure Payment</h6>
            <p>100% secure transactions</p>
        </div>
        <div class="trust-item">
            <i class="fas fa-undo-alt"></i>
            <h6>Easy Returns</h6>
            <p>30 days return policy</p>
        </div>
        <div class="trust-item">
            <i class="fas fa-headset"></i>
            <h6>24/7 Support</h6>
            <p>Dedicated customer support</p>
        </div>
    </div>
</section>

<!-- ========== CATEGORY SECTION ========== -->
<section class="category-section">
    <div class="section-title">
        <h2>Shop by <span style="color: #007bff;">Category</span></h2>
        <p>Browse through our wide range of quality products</p>
    </div>
    <div class="category-grid">
        <div class="category-card">
            <div class="category-icon bg-primary"><i class="fas fa-mobile-alt fa-3x text-white"></i></div>
            <h5>Electronics</h5>
            <p class="text-muted small">Smartphones, Laptops & more</p>
        </div>
        <div class="category-card">
            <div class="category-icon bg-success"><i class="fas fa-tshirt fa-3x text-white"></i></div>
            <h5>Fashion</h5>
            <p class="text-muted small">Clothing, Shoes & Accessories</p>
        </div>
        <div class="category-card">
            <div class="category-icon bg-warning"><i class="fas fa-home fa-3x text-white"></i></div>
            <h5>Home & Living</h5>
            <p class="text-muted small">Furniture, Decor & Kitchen</p>
        </div>
        <div class="category-card">
            <div class="category-icon bg-danger"><i class="fas fa-heartbeat fa-3x text-white"></i></div>
            <h5>Health & Beauty</h5>
            <p class="text-muted small">Cosmetics, Personal Care</p>
        </div>
    </div>
</section>

<!-- ========== FLASH SALE SECTION ========== -->
@php
    $flashSales = \App\Models\FlashSale::with('product')
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->take(4)
        ->get();
@endphp

@if($flashSales->count() > 0)
<section class="flash-sale-section">
    <div class="section-title">
        <h2>🔥 Hot <span style="color: #dc3545;">Flash Sale</span></h2>
        <p>Limited time offers - Hurry up!</p>
    </div>
    <div class="flash-grid">
        @foreach($flashSales as $flashSale)
        <div class="flash-card">
            <div class="flash-badge">-{{ round((($flashSale->product->price - $flashSale->sale_price) / $flashSale->product->price) * 100) }}% OFF</div>
            <div class="flash-img">
                @if($flashSale->product->image)
                <img src="{{ asset($flashSale->product->image) }}" alt="{{ $flashSale->product->name }}">
                @endif
            </div>
            <div class="flash-body">
                <h5>{{ \Illuminate\Support\Str::limit($flashSale->product->name, 25) }}</h5>
                <div class="flash-price">
                    <span class="sale">${{ number_format($flashSale->sale_price, 2) }}</span>
                    <span class="original">${{ number_format($flashSale->product->price, 2) }}</span>
                </div>
                <div class="flash-timer">
                    <span>⏰ Ends in: 2d 5h 32m</span>
                </div>
                <button class="btn-flash add-to-cart" data-id="{{ $flashSale->product->id }}">🛒 Add to Cart</button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('flash-sales.index') }}" class="btn btn-outline-danger px-4 py-2">View All Offers →</a>
    </div>
</section>
@endif

<!-- ========== FEATURED PRODUCTS ========== -->
@php
    $featuredProducts = \App\Models\Product::where('status', 'active')->latest()->take(8)->get();
@endphp

<section class="featured-section">
    <div class="section-title">
        <h2>✨ Featured <span style="color: #007bff;">Products</span></h2>
        <p>Hand-picked selection just for you</p>
    </div>
    <div class="products-grid">
        @foreach($featuredProducts as $product)
        <div class="product-card">
            <div class="product-badge">
                @if($product->created_at->diffInDays(now()) <= 7)
                <span class="badge-new">NEW</span>
                @endif
                @if($product->compare_price)
                <span class="badge-sale ms-1">SALE</span>
                @endif
            </div>
            <div class="product-img">
                @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                @else
                <div class="d-flex align-items-center justify-content-center h-100 text-muted">No Image</div>
                @endif
                <div class="product-actions">
                    <button class="action-btn add-to-cart" data-id="{{ $product->id }}" title="Add to Cart">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                    <button class="action-btn add-to-wishlist" data-id="{{ $product->id }}" title="Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                    <a href="{{ route('products.show', $product) }}" class="action-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
            <div class="product-body">
                <h6 class="product-title"><a href="{{ route('products.show', $product) }}">{{ \Illuminate\Support\Str::limit($product->name, 35) }}</a></h6>
                <div class="product-rating">
                    @php $rating = $product->average_rating ?? 0; @endphp
                    @for($i=1; $i<=5; $i++)
                        @if($i <= round($rating))
                            <i class="fas fa-star text-warning"></i>
                        @else
                            <i class="far fa-star text-muted"></i>
                        @endif
                    @endfor
                    <span class="text-muted ms-1">({{ $product->reviews->count() }})</span>
                </div>
                <div class="product-price">
                    @if($product->compare_price)
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                    <span class="old-price">${{ number_format($product->compare_price, 2) }}</span>
                    @else
                    <span class="original-price">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-5">
        <a href="{{ route('products.index') }}" class="btn btn-primary px-5 py-3 rounded-pill">View All Products →</a>
    </div>
</section>

<!-- ========== BANNER SECTION ========== -->
<section class="banner-section">
    <div class="banner-grid">
        <div class="banner-card bg-primary text-white">
            <div>
                <h3>Summer Sale</h3>
                <h2>Up to 50% OFF</h2>
                <p>On selected items</p>
                <a href="{{ route('products.index') }}" class="btn-banner">Shop Now →</a>
            </div>
            <i class="fas fa-tag banner-icon"></i>
        </div>
        <div class="banner-card bg-success text-white">
            <div>
                <h3>Free Shipping</h3>
                <h2>On Orders $50+</h2>
                <p>Limited time offer</p>
                <a href="{{ route('products.index') }}" class="btn-banner">Shop Now →</a>
            </div>
            <i class="fas fa-truck banner-icon"></i>
        </div>
    </div>
</section>

<!-- ========== NEWSLETTER SECTION ========== -->
<section class="newsletter-section">
    <div class="newsletter-box">
        <h2>Subscribe to Our Newsletter</h2>
        <p>Get the latest updates on new products and exclusive offers</p>
        <form class="newsletter-form">
            <input type="email" placeholder="Enter your email address">
            <button type="button" onclick="alert('Thank you for subscribing!')">Subscribe</button>
        </form>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="footer-section">
    <div class="footer-grid">
        <div class="footer-col">
            <h4>ProShop</h4>
            <p>Your one-stop destination for quality products at affordable prices. Shop with confidence.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h5>Quick Links</h5>
            <ul class="footer-links">
                <li><a href="{{ route('products.index') }}">Products</a></li>
                <li><a href="{{ route('flash-sales.index') }}">Flash Sales</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Customer Service</h5>
            <ul class="footer-links">
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Shipping Info</a></li>
                <li><a href="#">Returns Policy</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Contact Info</h5>
            <ul class="footer-contact">
                <li><i class="fas fa-map-marker-alt"></i> Sheikhupura,Punjab,Pakistan</li>
                <li><i class="fas fa-phone"></i> +92 301 90618883</li>
                <li><i class="fas fa-envelope"></i> support@proshop.com</li>
            </ul>
        </div>
    </div>
    <hr style="border-color: #222; margin: 40px 0 20px;">
    <div class="text-center">
        <p style="color: #666;">&copy; 2026 ProShop. All rights reserved.</p>
    </div>
</footer>

@push('scripts')
<script>
$(document).ready(function() {
    // Add to Cart
    $('.add-to-cart').click(function() {
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                if(response.success) {
                    alert(response.message);
                    $('#cart-count').text(response.cart_count);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Please login to add items to cart');
            }
        });
    });
    
    // Add to Wishlist
    $('.add-to-wishlist').click(function() {
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route("wishlist.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert('Please login to add to wishlist');
            }
        });
    });
});
</script>
@endpush
@endsection