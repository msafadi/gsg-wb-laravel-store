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
        <a class="ps-shoe__overlay" href="{{ route('products.show', [$product->category->slug, $product->slug]) }}"></a>
    </div>
    <div class="ps-shoe__content">
        <div class="ps-shoe__variants">
            <div class="ps-shoe__variant normal">
                <img src="images/access/1.jpg" alt="">
                <img src="images/access/2.jpg" alt="">
                <img src="images/access/3.jpg" alt="">
                <img src="images/access/4.jpg" alt="">
            </div>
            <select class="ps-rating ps-shoe__rating">
                <option value="1">1</option>
                <option value="1">2</option>
                <option value="1">3</option>
                <option value="1">4</option>
                <option value="2">5</option>
            </select>
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