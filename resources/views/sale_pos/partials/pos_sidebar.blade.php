{{-- New sidebar with category buttons and product grid --}}
<div class="tw-h-full tw-flex tw-flex-col">
    {{-- Category Buttons --}}
    <div class="tw-mb-4">
        <div class="tw-grid tw-grid-cols-2 tw-gap-2">
            <button type="button" class="category-btn tw-px-4 tw-py-3 tw-rounded-lg tw-text-sm tw-font-medium tw-transition-all tw-bg-cyan-500 tw-text-white" data-category="all">
                All Products
            </button>
            @foreach($categories as $category)
            <button type="button" class="category-btn tw-px-4 tw-py-3 tw-rounded-lg tw-text-sm tw-font-medium tw-transition-all tw-bg-white tw-text-gray-700 tw-border tw-border-gray-200 hover:tw-bg-gray-50" data-category="{{ $category->id }}">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Product Grid --}}
    <div class="tw-flex-1 tw-overflow-y-auto" id="product_list_body" style="max-height: calc(100vh - 300px);">
        <div class="tw-grid tw-grid-cols-2 tw-gap-3" id="product_grid">
            {{-- Products will be loaded here via AJAX --}}
        </div>
        <div class="tw-text-center tw-py-4" id="suggestion_page_loader" style="display: none;">
            <i class="fa fa-spinner fa-spin fa-2x tw-text-cyan-500"></i>
        </div>
    </div>
</div>

<input type="hidden" id="suggestion_page" value="1">
