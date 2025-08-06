<form class="cart-form" action="{{ route('public.cart.add-to-cart') }}" method="POST">
    @csrf
    @if (!empty($withVariations) && $product->variations()->count() > 0)
        <div class="pr_switch_wrap">
            {!! render_product_swatches($product, [
                'selected' => $selectedAttrs,
                'view'     => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer'
            ]) !!}
        </div>
        <div class="number-items-available" style="display: none; margin-bottom: 10px;"></div>
    @endif

    {!! render_product_options($product) !!}

    <input type="hidden"
        name="id" class="hidden-product-id"
        value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}"/>

    @if (EcommerceHelper::isCartEnabled() || !empty($withButtons))
        {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}
        <div class="product-button">
            @if (EcommerceHelper::isCartEnabled())
                <a href="{{ $product->url }}" class="btn buy-now-button @if ($product->isOutOfStock()) disabled @endif" title="{{ __('Buy Now') }}">
                    <span class="buy-now-text">{{ __('Buy Now') }}</span>
                </a>
            @endif
            @if (!empty($withButtons))
                {!! Theme::partial('ecommerce.product-loop-buttons', compact('product', 'wishlistIds')) !!}
            @endif
        </div>
    @endif
</form>
