<x-store-layout title="Checkout">
    <div class="ps-checkout pt-80 pb-80">
        <div class="ps-container">
            <form class="ps-checkout__form" action="{{ route('checkout') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
                        <div class="ps-checkout__billing">
                            <h3>Shipping Detail</h3>
                            <div class="form-group form-group--inline">
                                <label>First Name<span>*</span>
                                </label>
                                <x-form.input name="shipping[first_name]" :value="$user->profile->first_name" />
                            </div>
                            <div class="form-group form-group--inline">
                                <label>Last Name<span>*</span>
                                </label>
                                <x-form.input name="shipping[last_name]" :value="$user->profile->last_name" />
                            </div>
                            <div class="form-group form-group--inline">
                                <label>Phone Number<span>*</span>
                                </label>
                                <x-form.input name="shipping[phone_number]" />
                            </div>
                            <div class="form-group form-group--inline">
                                <label>Email Address<span>*</span>
                                </label>
                                <x-form.input name="shipping[email]" :value="$user->email" />
                            </div>
                            <div class="form-group form-group--inline">
                                <label>Street Address<span>*</span>
                                </label>
                                <x-form.input name="shipping[street]" :value="$user->profile->address" />
                            </div>
                            <div class="form-group form-group--inline">
                                <label>City<span>*</span>
                                </label>
                                <x-form.input name="shipping[city]" :value="$user->profile->city" />
                            </div>
                            <div class="form-group form-group--inline">
                                <label>Country<span>*</span>
                                </label>
                                <select name="shipping[country_code]" id="country_code">
                                    <option value="">Select Country</option>
                                    @foreach(Symfony\Component\Intl\Countries::getNames() as $code => $name)
                                        @if ($code == 'IL')
                                            @continue
                                        @endif
                                    <option value="{{ $code }}" @if(old('shipping.country_code', $user->profile->country_code) == $code) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="ps-checkbox">
                                    <input class="form-control" type="checkbox" id="cb01">
                                    <label for="cb01">Create an account?</label>
                                </div>
                            </div>
                            <h3 class="mt-40"> Addition information</h3>
                            <div class="form-group form-group--inline textarea">
                                <label>Order Notes</label>
                                <textarea class="form-control" rows="5" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="ps-checkout__order">
                            <header>
                                <h3>Your Order</h3>
                            </header>
                            <div class="content">
                                <table class="table ps-checkout__products">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase">Product</th>
                                            <th class="text-uppercase">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->all() as $item)
                                        <tr>
                                            <td>{{ $item->product->name }} x{{ $item->quantity }}</td>
                                            <td>{{ Money::format($item->quantity * $item->product->price) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td>Order Total</td>
                                            <td>{{ Money::format($cart->total()) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <footer>
                                <h3>Payment Method</h3>
                                <div class="form-group cheque">
                                    <div class="ps-radio">
                                        <input class="form-control" type="radio" id="rdo01" name="payment" checked>
                                        <label for="rdo01">Cheque Payment</label>
                                        <p>Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                                    </div>
                                </div>
                                <div class="form-group paypal">
                                    <div class="ps-radio ps-radio--inline">
                                        <input class="form-control" type="radio" name="payment" id="rdo02">
                                        <label for="rdo02">Paypal</label>
                                    </div>
                                    <ul class="ps-payment-method">
                                        <li><a href="#"><img src="images/payment/1.png" alt=""></a></li>
                                        <li><a href="#"><img src="images/payment/2.png" alt=""></a></li>
                                        <li><a href="#"><img src="images/payment/3.png" alt=""></a></li>
                                    </ul>
                                    <button class="ps-btn ps-btn--fullwidth">Place Order<i class="ps-icon-next"></i></button>
                                </div>
                            </footer>
                        </div>
                        <div class="ps-shipping">
                            <h3>FREE SHIPPING</h3>
                            <p>YOUR ORDER QUALIFIES FOR FREE SHIPPING.<br> <a href="#"> Singup </a> for free shipping on every order, every time.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-store-layout>