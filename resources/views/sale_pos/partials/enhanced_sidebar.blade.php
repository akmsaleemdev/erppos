<div class="pos-sidebar-enhanced">
    {{-- Category Filters --}}
    <div class="pos-category-filters">
        <button class="pos-category-btn active" data-category="all">
            <span>All Products</span>
        </button>
        @if(isset($categories) && count($categories) > 0)
            @foreach($categories as $category)
                <button class="pos-category-btn" data-category="{{ $category->id }}">
                    <span>{{ $category->name }}</span>
                </button>
            @endforeach
        @else
            <button class="pos-category-btn" data-category="coffee">
                <span>Coffee</span>
            </button>
            <button class="pos-category-btn" data-category="tea">
                <span>Tea</span>
            </button>
            <button class="pos-category-btn" data-category="snacks">
                <span>Snacks</span>
            </button>
            <button class="pos-category-btn" data-category="beverages">
                <span>Beverages</span>
            </button>
            <button class="pos-category-btn" data-category="pastries">
                <span>Pastries</span>
            </button>
        @endif
    </div>

    {{-- Products Grid --}}
    <div class="pos-products-grid">
        {{-- Products will be loaded dynamically via JavaScript --}}
        <div class="pos-empty-cart" style="grid-column: 1 / -1;">
            <div class="pos-empty-cart-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <p class="pos-empty-cart-text">Loading products...</p>
        </div>
    </div>
</div>

<style>
.pos-sidebar-enhanced {
    background: var(--neutral-50);
    padding: 1rem;
    height: 100%;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
</style>
