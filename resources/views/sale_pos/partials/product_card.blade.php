{{-- Removed onclick to prevent conflicts with JavaScript event handlers --}}
@foreach($products as $product)
<div class="product-card tw-bg-white tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden tw-cursor-pointer tw-transition-all hover:tw-shadow-md hover:tw-border-cyan-300" 
     data-variation-id="{{ $product['variation_id'] }}"
     data-product-id="{{ $product['product_id'] }}">
    <div class="tw-aspect-square tw-bg-gray-50 tw-flex tw-items-center tw-justify-center tw-p-2">
        @if(isset($product['image']) && $product['image'])
        <img src="{{ $product['image'] }}" 
             alt="{{ $product['name'] }}" 
             class="tw-w-full tw-h-full tw-object-cover"
             style="max-width: 100px; max-height: 100px;">
        @else
        <div class="tw-w-24 tw-h-24 tw-bg-gradient-to-br tw-from-cyan-100 tw-to-blue-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
            <i class="fa fa-image tw-text-cyan-400 tw-text-3xl"></i>
        </div>
        @endif
    </div>
    <div class="tw-p-3">
        <h4 class="tw-text-sm tw-font-semibold tw-text-gray-800 tw-mb-1 tw-line-clamp-2">
            {{ $product['name'] }}
        </h4>
        <p class="tw-text-cyan-600 tw-font-bold tw-text-base">
            {{ session('currency')['symbol'] ?? '$' }}{{ number_format($product['selling_price'], 2) }}
        </p>
        @if(isset($product['qty_available']) && $product['qty_available'])
        <p class="tw-text-xs tw-text-gray-500 tw-mt-1">
            {{ $product['qty_available'] }} in stock
        </p>
        @endif
    </div>
</div>
@endforeach
