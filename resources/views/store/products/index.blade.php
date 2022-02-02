<x-store-layout :title="$category->name ?: 'Products'">

    <div class="ps-products-wrap pt-40">
        @if ($category->name)
        <h2>{{ $category->name }}</h2>
        @endif
    </div>
    <div class="ps-products-wrap pt-40 pb-80">
        
        <div class="ps-products" data-mh="product-listing">
            <div class="ps-product-action">
                <div class="ps-product__filter">
                    <select class="ps-select selectpicker">
                        <option value="1">Shortby</option>
                        <option value="2">Name</option>
                        <option value="3">Price (Low to High)</option>
                        <option value="3">Price (High to Low)</option>
                    </select>
                </div>
                {{ $products->links() }}
                
            </div>
            <div class="ps-product__columns">
                @foreach ($products as $product)
                <div class="ps-product__column">
                    <x-product-card :product="$product" />
                </div>
                @endforeach
            </div>
            <div class="ps-product-action">
                <div class="ps-product__filter">
                    <select class="ps-select selectpicker">
                        <option value="1">Shortby</option>
                        <option value="2">Name</option>
                        <option value="3">Price (Low to High)</option>
                        <option value="3">Price (High to Low)</option>
                    </select>
                </div>
                {{ $products->links() }}
            </div>
        </div>
        <div class="ps-sidebar" data-mh="product-listing">
            <aside class="ps-widget--sidebar ps-widget--category">
                <div class="ps-widget__header">
                    <h3>Category</h3>
                </div>
                <div class="ps-widget__content">
                    <ul class="ps-list--checked">
                        <li class="current"><a href="product-listing.html">Life(521)</a></li>
                        <li><a href="product-listing.html">Running(76)</a></li>
                        <li><a href="product-listing.html">Baseball(21)</a></li>
                        <li><a href="product-listing.html">Football(105)</a></li>
                        <li><a href="product-listing.html">Soccer(108)</a></li>
                        <li><a href="product-listing.html">Trainning & game(47)</a></li>
                        <li><a href="product-listing.html">More</a></li>
                    </ul>
                </div>
            </aside>
            <aside class="ps-widget--sidebar ps-widget--filter">
                <div class="ps-widget__header">
                    <h3>Category</h3>
                </div>
                <div class="ps-widget__content">
                    <div class="ac-slider" data-default-min="300" data-default-max="2000" data-max="3450" data-step="50" data-unit="$"></div>
                    <p class="ac-slider__meta">Price:<span class="ac-slider__value ac-slider__min"></span>-<span class="ac-slider__value ac-slider__max"></span></p><a class="ac-slider__filter ps-btn" href="#">Filter</a>
                </div>
            </aside>
            <aside class="ps-widget--sidebar ps-widget--category">
                <div class="ps-widget__header">
                    <h3>Sky Brand</h3>
                </div>
                <div class="ps-widget__content">
                    <ul class="ps-list--checked">
                        <li class="current"><a href="product-listing.html">Nike(521)</a></li>
                        <li><a href="product-listing.html">Adidas(76)</a></li>
                        <li><a href="product-listing.html">Baseball(69)</a></li>
                        <li><a href="product-listing.html">Gucci(36)</a></li>
                        <li><a href="product-listing.html">Dior(108)</a></li>
                        <li><a href="product-listing.html">B&G(108)</a></li>
                        <li><a href="product-listing.html">Louis Vuiton(47)</a></li>
                    </ul>
                </div>
            </aside>
            <aside class="ps-widget--sidebar ps-widget--category">
                <div class="ps-widget__header">
                    <h3>Width</h3>
                </div>
                <div class="ps-widget__content">
                    <ul class="ps-list--checked">
                        <li class="current"><a href="product-listing.html">Narrow</a></li>
                        <li><a href="product-listing.html">Regular</a></li>
                        <li><a href="product-listing.html">Wide</a></li>
                        <li><a href="product-listing.html">Extra Wide</a></li>
                    </ul>
                </div>
            </aside>
            <div class="ps-sticky desktop">
                <aside class="ps-widget--sidebar">
                    <div class="ps-widget__header">
                        <h3>Size</h3>
                    </div>
                    <div class="ps-widget__content">
                        <table class="table ps-table--size">
                            <tbody>
                                <tr>
                                    <td class="active">3</td>
                                    <td>5.5</td>
                                    <td>8</td>
                                    <td>10.5</td>
                                    <td>13</td>
                                </tr>
                                <tr>
                                    <td>3.5</td>
                                    <td>6</td>
                                    <td>8.5</td>
                                    <td>11</td>
                                    <td>13.5</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>6.5</td>
                                    <td>9</td>
                                    <td>11.5</td>
                                    <td>14</td>
                                </tr>
                                <tr>
                                    <td>4.5</td>
                                    <td>7</td>
                                    <td>9.5</td>
                                    <td>12</td>
                                    <td>14.5</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>7.5</td>
                                    <td>10</td>
                                    <td>12.5</td>
                                    <td>15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </aside>
                <aside class="ps-widget--sidebar">
                    <div class="ps-widget__header">
                        <h3>Color</h3>
                    </div>
                    <div class="ps-widget__content">
                        <ul class="ps-list--color">
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                        </ul>
                    </div>
                </aside>
            </div>
            <!--aside.ps-widget--sidebar-->
            <!--    .ps-widget__header: h3 Ads Banner-->
            <!--    .ps-widget__content-->
            <!--        a(href='product-listing'): img(src="images/offer/sidebar.jpg" alt="")-->
            <!---->
            <!--aside.ps-widget--sidebar-->
            <!--    .ps-widget__header: h3 Best Seller-->
            <!--    .ps-widget__content-->
            <!--        - for (var i = 0; i < 3; i ++)-->
            <!--            .ps-shoe--sidebar-->
            <!--                .ps-shoe__thumbnail-->
            <!--                    a(href='#')-->
            <!--                    img(src="images/shoe/sidebar/"+(i+1)+".jpg" alt="")-->
            <!--                .ps-shoe__content-->
            <!--                    if i == 1-->
            <!--                        a(href='#').ps-shoe__title Nike Flight Bonafide-->
            <!--                    else if i == 2-->
            <!--                        a(href='#').ps-shoe__title Nike Sock Dart QS-->
            <!--                    else-->
            <!--                        a(href='#').ps-shoe__title Men's Sky-->
            <!--                    p <del> £253.00</del> £152.00-->
            <!--                    a(href='#').ps-btn PURCHASE-->
        </div>
    </div>

</x-store-layout>