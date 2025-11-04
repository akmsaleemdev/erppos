{{-- Updated POS Sidebar with Category Filters and Product Grid --}}
<div class="pos-sidebar-container tw-h-full tw-flex tw-flex-col">
    {{-- Category Filter Buttons --}}
    <div class="category-filters tw-mb-4 tw-p-4 tw-bg-white tw-rounded-lg tw-shadow-md">
        <button class="category-btn tw-w-full tw-mb-2 tw-px-4 tw-py-3 tw-rounded-lg tw-font-semibold tw-text-white tw-transition-all tw-duration-200 active" 
                data-category="all" 
                style="background: linear-gradient(to right, #06b6d4, #0891b2);">
            <i class="fa fa-th-large tw-mr-2"></i> All Products
        </button>
        
        @if(!empty($categories))
            @foreach($categories as $category)
                <button class="category-btn tw-w-full tw-mb-2 tw-px-4 tw-py-3 tw-rounded-lg tw-font-semibold tw-bg-gray-100 tw-text-gray-700 hover:tw-bg-gray-200 tw-transition-all tw-duration-200" 
                        data-category="{{ $category->id }}">
                    <i class="fa fa-tag tw-mr-2"></i> {{ $category->name }}
                </button>
            @endforeach
        @endif
    </div>

    {{-- Product Grid --}}
    <div class="product-grid-container tw-flex-1 tw-overflow-y-auto tw-p-4 tw-bg-gray-50 tw-rounded-lg">
        <div class="product-grid tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-2 tw-gap-3" id="product_grid">
            {{-- Products will be loaded here via JavaScript --}}
            <div class="tw-col-span-full tw-text-center tw-py-8 tw-text-gray-500" id="no_products_message">
                <i class="fa fa-shopping-bag tw-text-4xl tw-mb-2"></i>
                <p>No Products to display</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Category button active state */
.category-btn.active {
    background: linear-gradient(to right, #06b6d4, #0891b2) !important;
    color: white !important;
    box-shadow: 0 4px 6px rgba(6, 182, 212, 0.3);
}

/* Product card styles */
.product-card {
    background: white;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    border-color: #06b6d4;
}

.product-card-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin: 0 auto 8px;
    display: block;
}

.product-card-name {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.3;
    min-height: 34px;
}

.product-card-price {
    font-size: 14px;
    font-weight: 700;
    color: #06b6d4;
    text-align: center;
    margin-top: 4px;
}

/* Scrollbar styling */
.product-grid-container::-webkit-scrollbar {
    width: 6px;
}

.product-grid-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.product-grid-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.product-grid-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
$(document).ready(function() {
    // Load products on page load
    loadProducts('all');
    
    // Category filter click handler
    $('.category-btn').on('click', function() {
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
        
        const category = $(this).data('category');
        loadProducts(category);
    });
    
    // Function to load products
    function loadProducts(category) {
        const locationId = $('#location_id').val();
        
        $.ajax({
            url: '/sells/pos/get-product-suggestion',
            method: 'GET',
            data: {
                location_id: locationId,
                category_id: category !== 'all' ? category : null
            },
            success: function(response) {
                renderProducts(response);
            },
            error: function(error) {
                console.error('Error loading products:', error);
                toastr.error('Failed to load products');
            }
        });
    }
    
    // Function to render products in grid
    function renderProducts(products) {
        const grid = $('#product_grid');
        const noProductsMsg = $('#no_products_message');
        
        grid.empty();
        
        if (!products || products.length === 0) {
            grid.append(noProductsMsg);
            return;
        }
        
        products.forEach(function(product) {
            const imageUrl = product.image_url || '/img/default.png';
            const productName = product.name || 'Unnamed Product';
            const productPrice = product.selling_price || '0.00';
            
            const card = `
                <div class="product-card" data-variation-id="${product.variation_id}" data-product='${JSON.stringify(product)}'>
                    <img src="${imageUrl}" alt="${productName}" class="product-card-image" loading="lazy">
                    <div class="product-card-name">${productName}</div>
                    <div class="product-card-price">${__currency_trans_from_en(productPrice, true)}</div>
                </div>
            `;
            
            grid.append(card);
        });
        
        // Add click handler to product cards
        $('.product-card').on('click', function() {
            const productData = $(this).data('product');
            addProductToCart(productData);
        });
    }
    
    // Function to add product to cart
    function addProductToCart(product) {
        // This function should integrate with your existing POS cart logic
        // You may need to call your existing pos_product_row function
        if (typeof window.pos_product_row === 'function') {
            window.pos_product_row(product);
        } else {
            console.error('pos_product_row function not found');
        }
    }
});
</script>
