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
                <button type="submit" class="btn add-to-cart-button @if ($product->isOutOfStock()) disabled @endif" @if ($product->isOutOfStock()) disabled @endif title="{{ __('Add to cart') }}" style="background-color: #3BB77E; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: 600; width: 100%; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;">
                    <span class="svg-icon" style="margin-right: 8px;">
                        <svg style="width: 16px; height: 16px; fill: currentColor;">
                            <use href="#svg-icon-cart" xlink:href="#svg-icon-cart"></use>
                        </svg>
                    </span>
                    <span class="add-to-cart-text">{{ __('Add to cart') }}</span>
                </button>
            @endif
            @if (!empty($withButtons))
                {!! Theme::partial('ecommerce.product-loop-buttons', compact('product', 'wishlistIds')) !!}
            @endif
        </div>
    @endif
</form>