@props([
    'product', 'new' => false
])

<div class="ps-shoe mb-30">
    <div class="ps-shoe__thumbnail">
        @if ($new)
        <div class="ps-badge"><span>New</span></div>
        @endif
        @if ($product->compare_price)
        <div class="ps-badge ps-badge--sale @if($new) ps-badge--2nd @endif"><span>-{{ $product->discount_percent }}%</span></div>
        @endif
        <a class="ps-shoe__favorite" href="#"><i class="ps-icon-heart"></i></a>
        <img src="{{ $product->image_url }}" alt="">
        <a class="ps-shoe__overlay" href="{{ $product->url }}"></a>
    </div>
    <div class="ps-shoe__content">
        <div class="ps-shoe__variants">
            <div class="ps-shoe__variant normal">
                @foreach($product->getMedia('gallery') as $media)
                <img src="{{ $media->getUrl() }}" alt="">
                @endforeach
            </div>
            <x-rating-stars :rating="$product->rating" class="ps-shoe__rating" />
        </div>
        <div class="ps-shoe__detail">
            <a class="ps-shoe__name" href="#">{{ $product->name }}</a>
            <p class="ps-shoe__categories">
                <a href="{{ route('products', $product->category->slug) }}">{{ $product->category->name }}</a>
            </p>
            <span class="ps-shoe__price">
                @if ($product->compare_price)
                <del>{{ Money::format($product->compare_price) }}</del>
                @endif
                {{ Money::format($product->price) }}
            </span>
        </div>
    </div>
</div>