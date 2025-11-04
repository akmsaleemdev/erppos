@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('css')
<link rel="stylesheet" href="{{ asset('css/pos-enhanced.css') }}">
<style>
    body {
        overflow: hidden;
    }
    .pos-container {
        height: 100vh;
        display: flex;
        flex-direction: column;
    }
</style>
@endsection

@section('content')
<div class="pos-container tw-bg-gray-50">
    {{-- Header --}}
    <div class="tw-bg-white tw-border-b tw-border-gray-200 tw-px-6 tw-py-4">
        <div class="tw-flex tw-items-center tw-justify-between">
            <div class="tw-flex tw-items-center tw-gap-4">
                <h3 class="tw-text-xl tw-font-bold tw-text-gray-800">Point of Sale</h3>
                <div class="tw-text-sm tw-text-gray-600">
                    <i class="fa fa-map-marker tw-text-cyan-500"></i>
                    <span class="tw-ml-1">{{ $default_location->name ?? 'Location' }}</span>
                </div>
            </div>
            <div class="tw-flex tw-items-center tw-gap-2">
                <button type="button" class="tw-px-4 tw-py-2 tw-bg-gray-100 tw-text-gray-700 tw-rounded-lg tw-text-sm hover:tw-bg-gray-200">
                    <i class="fa fa-history"></i> Recent Transactions
                </button>
                <button type="button" class="tw-px-4 tw-py-2 tw-bg-cyan-500 tw-text-white tw-rounded-lg tw-text-sm hover:tw-bg-cyan-600">
                    <i class="fa fa-plus"></i> Add Expense
                </button>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="tw-flex tw-flex-1 tw-overflow-hidden">
        {{-- Left Side: Cart --}}
        <div class="tw-w-1/2 tw-p-6 tw-flex tw-flex-col">
            <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-flex tw-flex-col tw-h-full">
                {{-- Customer Selection --}}
                <div class="tw-p-4 tw-border-b tw-border-gray-200">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <i class="fa fa-user tw-text-gray-400"></i>
                        <select class="form-control tw-flex-1" id="customer_id" name="contact_id">
                            <option value="">Walk-In Customer</option>
                            @if(isset($customers))
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <button type="button" class="tw-px-3 tw-py-2 tw-bg-cyan-500 tw-text-white tw-rounded-lg hover:tw-bg-cyan-600">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="tw-p-4 tw-border-b tw-border-gray-200">
                    <div class="tw-relative">
                        <i class="fa fa-search tw-absolute tw-left-3 tw-top-1/2 tw-transform -tw-translate-y-1/2 tw-text-gray-400"></i>
                        <input type="text" 
                               id="search_product" 
                               class="tw-w-full tw-pl-10 tw-pr-4 tw-py-3 tw-border tw-border-gray-200 tw-rounded-lg focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-cyan-500" 
                               placeholder="Search products by name or SKU...">
                    </div>
                </div>

                {{-- Cart Items --}}
                <div class="tw-flex-1 tw-overflow-y-auto tw-p-4" id="cart_items">
                    <div class="tw-text-center tw-py-12" id="empty_cart_message">
                        <i class="fa fa-shopping-cart tw-text-gray-300 tw-text-5xl tw-mb-4"></i>
                        <p class="tw-text-gray-500">No items in cart</p>
                        <p class="tw-text-sm tw-text-gray-400 tw-mt-2">Add products from the right panel</p>
                    </div>
                    <table class="table tw-w-full" id="pos_table" style="display: none;">
                        <thead>
                            <tr class="tw-text-sm tw-text-gray-600">
                                <th>Product</th>
                                <th class="tw-text-center">Qty</th>
                                <th class="tw-text-right">Price</th>
                                <th class="tw-text-right">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="pos_table_body">
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="tw-p-4 tw-border-t tw-border-gray-200 tw-bg-gray-50">
                    <div class="tw-space-y-2">
                        <div class="tw-flex tw-justify-between tw-text-sm">
                            <span class="tw-text-gray-600">Items:</span>
                            <span class="tw-font-medium" id="total_items">0</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-text-sm">
                            <span class="tw-text-gray-600">Subtotal:</span>
                            <span class="tw-font-medium" id="subtotal">0.00</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-text-sm">
                            <span class="tw-text-gray-600">Tax:</span>
                            <span class="tw-font-medium" id="total_tax">0.00</span>
                        </div>
                        <div class="tw-flex tw-justify-between tw-text-lg tw-font-bold tw-pt-2 tw-border-t tw-border-gray-300">
                            <span>Total:</span>
                            <span class="tw-text-cyan-600" id="total_payable">0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Buttons --}}
                <div class="tw-p-4 tw-border-t tw-border-gray-200">
                    <div class="tw-grid tw-grid-cols-3 tw-gap-2 tw-mb-2">
                        <button type="button" class="tw-px-4 tw-py-2 tw-bg-gray-100 tw-text-gray-700 tw-rounded-lg tw-text-sm hover:tw-bg-gray-200">
                            <i class="fa fa-file"></i> Draft
                        </button>
                        <button type="button" class="tw-px-4 tw-py-2 tw-bg-gray-100 tw-text-gray-700 tw-rounded-lg tw-text-sm hover:tw-bg-gray-200">
                            <i class="fa fa-file-text"></i> Quotation
                        </button>
                        <button type="button" class="tw-px-4 tw-py-2 tw-bg-gray-100 tw-text-gray-700 tw-rounded-lg tw-text-sm hover:tw-bg-gray-200">
                            <i class="fa fa-pause"></i> Suspend
                        </button>
                    </div>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-2">
                        <button type="button" class="tw-px-6 tw-py-3 tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-500 tw-text-white tw-rounded-lg tw-font-medium hover:tw-from-cyan-600 hover:tw-to-blue-600">
                            <i class="fa fa-credit-card"></i> Card
                        </button>
                        <button type="button" class="tw-px-6 tw-py-3 tw-bg-gradient-to-r tw-from-green-500 tw-to-emerald-500 tw-text-white tw-rounded-lg tw-font-medium hover:tw-from-green-600 hover:tw-to-emerald-600">
                            <i class="fa fa-money"></i> Cash
                        </button>
                    </div>
                    <button type="button" class="tw-w-full tw-mt-2 tw-px-6 tw-py-2 tw-bg-red-500 tw-text-white tw-rounded-lg tw-text-sm hover:tw-bg-red-600">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Side: Products --}}
        <div class="tw-w-1/2 tw-p-6 tw-bg-gray-100">
            @include('sale_pos.partials.pos_sidebar')
        </div>
    </div>
    
    {{-- Added hidden input for location ID --}}
    <input type="hidden" id="location_id" value="{{ $default_location->id ?? 1 }}">
    {{-- Added CSRF token for AJAX requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>
@endsection

@section('javascript')
<script src="{{ asset('js/pos-enhanced.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Initialize POS
    $(document).ready(function() {
        loadProducts('all');
        
        // Category button click
        $('.category-btn').on('click', function() {
            $('.category-btn').removeClass('tw-bg-cyan-500 tw-text-white').addClass('tw-bg-white tw-text-gray-700 tw-border tw-border-gray-200');
            $(this).removeClass('tw-bg-white tw-text-gray-700 tw-border tw-border-gray-200').addClass('tw-bg-cyan-500 tw-text-white');
            
            const categoryId = $(this).data('category');
            loadProducts(categoryId);
        });
        
        // Search functionality
        let searchTimeout;
        $('#search_product').on('keyup', function() {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val();
            searchTimeout = setTimeout(function() {
                loadProducts($('.category-btn.tw-bg-cyan-500').data('category'), searchTerm);
            }, 300);
        });
    });
    
    function loadProducts(categoryId, searchTerm = '') {
        $('#suggestion_page_loader').show();
        
        $.ajax({
            url: '/sells/pos/get-product-suggestion',
            method: 'GET',
            dataType: 'json',
            data: {
                category_id: categoryId,
                term: searchTerm,
                location_id: $('#location_id').val()
            },
            success: function(response) {
                $('#product_grid').html('');
                
                if (response.success && response.products && response.products.length > 0) {
                    response.products.forEach(function(product) {
                        const productCard = `
                            <div class="product-card tw-bg-white tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden tw-cursor-pointer tw-transition-all hover:tw-shadow-md hover:tw-border-cyan-300" 
                                 data-variation-id="${product.variation_id}"
                                 data-product-id="${product.product_id}">
                                <div class="tw-aspect-square tw-bg-gray-50 tw-flex tw-items-center tw-justify-center tw-p-2">
                                    <img src="${product.image}" 
                                         alt="${product.name}" 
                                         class="tw-w-full tw-h-full tw-object-cover"
                                         style="max-width: 100px; max-height: 100px;">
                                </div>
                                <div class="tw-p-3">
                                    <h4 class="tw-text-sm tw-font-semibold tw-text-gray-800 tw-mb-1 tw-line-clamp-2">
                                        ${product.name}
                                    </h4>
                                    <p class="tw-text-cyan-600 tw-font-bold tw-text-base">
                                        $${parseFloat(product.selling_price).toFixed(2)}
                                    </p>
                                    ${product.qty_available ? `<p class="tw-text-xs tw-text-gray-500 tw-mt-1">${product.qty_available} in stock</p>` : ''}
                                </div>
                            </div>
                        `;
                        $('#product_grid').append(productCard);
                    });
                    
                    $('.product-card').off('click').on('click', function() {
                        const variationId = $(this).data('variation-id');
                        addProductToCart(variationId);
                    });
                } else {
                    $('#product_grid').html('<div class="tw-col-span-2 tw-text-center tw-py-8 tw-text-gray-500">No products found</div>');
                }
                
                $('#suggestion_page_loader').hide();
            },
            error: function(xhr, status, error) {
                $('#suggestion_page_loader').hide();
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error loading products: ' + error);
                }
            }
        });
    }
    
    function addProductToCart(variationId) {
        // Call your existing add to cart function
        if (typeof pos_product_row === 'function') {
            pos_product_row(variationId);
        } else if (typeof window.pos_product_row === 'function') {
            window.pos_product_row(variationId);
        }
        
        // Update UI
        $('#empty_cart_message').hide();
        $('#pos_table').show();
        
        // Visual feedback
        if (typeof toastr !== 'undefined') {
            toastr.success('Product added to cart');
        }
    }
</script>
@endsection
