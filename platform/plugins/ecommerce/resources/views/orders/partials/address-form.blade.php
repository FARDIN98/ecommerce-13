<div class="customer-address-payment-form">

    @if (EcommerceHelper::isEnabledGuestCheckout() && ! auth('customer')->check())
        <div class="form-group mb-3">
            <p>{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}">{{ __('Login') }}</a></p>
        </div>
    @endif

    @if (auth('customer')->check())
        <div class="form-group mb-3">
            @if ($isAvailableAddress)
                <label class="control-label mb-2" for="address_id">{{ __('Select available addresses') }}:</label>
            @endif
            @php
                $oldSessionAddressId = old('address.address_id', $sessionAddressId);
            @endphp
            <div class="list-customer-address @if (! $isAvailableAddress) d-none @endif">
                <div class="select--arrow">
                    <select name="address[address_id]" class="form-control" id="address_id">
                        <option value="new" @selected ($oldSessionAddressId == 'new')>{{ __('Add new address...') }}</option>
                        @if ($isAvailableAddress)
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}" @selected ($oldSessionAddressId == $address->id)>{{ $address->full_address }}</option>
                            @endforeach
                        @endif
                    </select>
                    <i class="fas fa-angle-down"></i>
                </div>
                <br>
                <div class="address-item-selected @if (! $sessionAddressId) d-none @endif">
                    @if ($isAvailableAddress && $oldSessionAddressId != 'new')
                        @if ($oldSessionAddressId && $addresses->contains('id', $oldSessionAddressId))
                            @include('plugins/ecommerce::orders.partials.address-item', ['address' => $addresses->firstWhere('id', $oldSessionAddressId)])
                        @elseif ($defaultAddress = get_default_customer_address())
                            @include('plugins/ecommerce::orders.partials.address-item', ['address' => $defaultAddress])
                        @else
                            @include('plugins/ecommerce::orders.partials.address-item', ['address' => Arr::first($addresses)])
                        @endif
                    @endif
                </div>
                <div class="list-available-address d-none">
                    @if ($isAvailableAddress)
                        @foreach($addresses as $address)
                            <div class="address-item-wrapper" data-id="{{ $address->id }}">
                                @include('plugins/ecommerce::orders.partials.address-item', compact('address'))
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="address-form-wrapper @if (auth('customer')->check() && $oldSessionAddressId !== 'new' && $isAvailableAddress) d-none @endif">
        <div class="form-group mb-3 @error('address.name') has-error @enderror">
            <div class="form-input-wrapper">
                <input type="text" name="address[name]" id="address_name"
                       class="form-control"
                       required
                       value="{{ old('address.name', Arr::get($sessionCheckoutData, 'name')) ?: (auth('customer')->check() ? auth('customer')->user()->name : null) }}">
                <label for='address_name'>{{ __('Full Name') }}</label>
            </div>
            {!! Form::error('address.name', $errors) !!}
        </div>

        <div class="form-group mb-3 @error('address.phone') has-error @enderror">
            <div class="form-input-wrapper">
                <input type="tel" name="address[phone]" id="address_phone"
                       class="form-control"
                       required
                       value="{{ old('address.phone', Arr::get($sessionCheckoutData, 'phone')) ?: (auth('customer')->check() ? auth('customer')->user()->phone : null) }}">
                <label for='address_phone'>{{ __('Phone') }}</label>
            </div>
            {!! Form::error('address.phone', $errors) !!}
        </div>

        <div class="form-group mb-3 @error('address.address') has-error @enderror">
            <div class="form-input-wrapper">
                <input id="address_address" type="text" class="form-control" required name="address[address]" value="{{ old('address.address', Arr::get($sessionCheckoutData, 'address')) }}" autocomplete="false">
                <label for='address_address'>{{ __('Address') }}</label>
            </div>
            {!! Form::error('address.address', $errors) !!}
        </div>

        <!-- Hidden fields for required data -->
        <input type="hidden" name="address[email]" value="{{ old('address.email', Arr::get($sessionCheckoutData, 'email')) ?: (auth('customer')->check() ? auth('customer')->user()->email : 'customer@example.com') }}">
        <input type="hidden" name="address[country]" value="{{ old('address.country', Arr::get($sessionCheckoutData, 'country')) ?: EcommerceHelper::getFirstCountryId() }}">
        <input type="hidden" name="address[state]" value="{{ old('address.state', Arr::get($sessionCheckoutData, 'state')) ?: 'Default State' }}">
        <input type="hidden" name="address[city]" value="{{ old('address.city', Arr::get($sessionCheckoutData, 'city')) ?: 'Default City' }}">
        @if (EcommerceHelper::isZipCodeEnabled())
            <input type="hidden" name="address[zip_code]" value="{{ old('address.zip_code', Arr::get($sessionCheckoutData, 'zip_code')) ?: '00000' }}">
        @endif
    </div>

    @if (! auth('customer')->check())
        <div class="form-group mb-3">
            <input type="checkbox" name="create_account" value="1" id="create_account" @if (old('create_account') == 1) checked @endif>
            <label for="create_account" class="control-label ps-2">{{ __('Register an account with above information?') }}</label>
        </div>

        <div class="password-group @if (! $errors->has('password') && ! $errors->has('password_confirmation')) d-none @endif">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group  @error('password') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input id="password" type="password" class="form-control" name="password" autocomplete="password">
                            <label for='password'>{{ __('Password') }}</label>
                        </div>
                        {!! Form::error('password', $errors) !!}
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group @error('password_confirmation') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input id="password-confirm" type="password" class="form-control" autocomplete="password-confirmation" name="password_confirmation">
                            <label for='password-confirm'>{{ __('Password confirmation') }}</label>
                        </div>
                        {!! Form::error('password_confirmation', $errors) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
