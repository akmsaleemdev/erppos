<div class="pos-cart-wrapper">
    <table class="pos-cart-table" id="pos_table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price inc. tax</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {{-- Cart items will be added here dynamically --}}
        </tbody>
    </table>
    
    {{-- Empty Cart State --}}
    <div class="pos-empty-cart" id="pos-empty-cart" style="display: none;">
        <div class="pos-empty-cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <p class="pos-empty-cart-text">No products added yet</p>
    </div>
</div>

{{-- Totals Section --}}
<div class="pos-totals-section">
    <div class="pos-total-row">
        <span class="pos-total-label">Items:</span>
        <span class="pos-total-value" id="total-items">0.00</span>
    </div>
    <div class="pos-total-row">
        <span class="pos-total-label">Discount (-):</span>
        <span class="pos-total-value" id="total-discount">0.00</span>
    </div>
    <div class="pos-total-row">
        <span class="pos-total-label">Order Tax (+):</span>
        <span class="pos-total-value" id="total-tax">0.00</span>
    </div>
    <div class="pos-total-row">
        <span class="pos-total-label">Shipping (+):</span>
        <span class="pos-total-value" id="total-shipping">0.00</span>
    </div>
    <div class="pos-total-row">
        <span class="pos-total-label">Total Payable:</span>
        <span class="pos-total-value" id="total-payable">0.00</span>
    </div>
</div>

{{-- Action Buttons --}}
<div class="pos-actions-grid">
    <button class="pos-action-btn" id="pos-draft">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
        </svg>
        <span>Draft</span>
    </button>
    <button class="pos-action-btn" id="pos-quotation">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
        </svg>
        <span>Quotation</span>
    </button>
    <button class="pos-action-btn" id="pos-suspend">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="6" y="4" width="4" height="16"></rect>
            <rect x="14" y="4" width="4" height="16"></rect>
        </svg>
        <span>Suspend</span>
    </button>
    <button class="pos-action-btn" id="pos-credit-sale">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
            <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
        <span>Credit Sale</span>
    </button>
    <button class="pos-action-btn" id="pos-card">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
            <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
        <span>Card</span>
    </button>
</div>

{{-- Payment Buttons --}}
<div class="pos-payment-buttons">
    <button class="pos-payment-btn btn-multiple-pay" data-payment-type="multiple">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
            <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
        <span>Multiple Pay</span>
    </button>
    <button class="pos-payment-btn btn-cash" data-payment-type="cash">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="1" x2="12" y2="23"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
        <span>Cash</span>
    </button>
    <button class="pos-payment-btn btn-cancel" data-payment-type="cancel">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        <span>Cancel</span>
    </button>
</div>
