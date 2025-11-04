// Modern POS JavaScript for Laravel
// Add this file to your Laravel public/js folder

class POSSystem {
  constructor() {
    this.cart = []
    this.products = []
    this.customer = null
    this.discount = 0
    this.orderTax = 0
    this.shipping = 0

    this.init()
  }

  init() {
    this.updateDateTime()
    setInterval(() => this.updateDateTime(), 1000)
    this.attachEventListeners()
    this.updateDisplay()
  }

  updateDateTime() {
    const now = new Date()
    const formatted = now.toLocaleString("en-US", {
      month: "2-digit",
      day: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      hour12: false,
    })

    const dateTimeElement = document.getElementById("current-datetime")
    if (dateTimeElement) {
      dateTimeElement.textContent = formatted.replace(",", "")
    }
  }

  attachEventListeners() {
    // Search input
    const searchInput = document.getElementById("product-search")
    if (searchInput) {
      searchInput.addEventListener("input", (e) => this.searchProducts(e.target.value))
      searchInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
          this.searchProducts(e.target.value)
        }
      })
    }

    // Customer select
    const customerSelect = document.getElementById("customer-select")
    if (customerSelect) {
      customerSelect.addEventListener("change", (e) => this.selectCustomer(e.target.value))
    }

    // Payment buttons
    document.querySelectorAll("[data-action]").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const action = e.currentTarget.dataset.action
        this.handleAction(action)
      })
    })
  }

  searchProducts(query) {
    if (!query || query.length < 2) {
      this.displayProducts([])
      return
    }

    // Make AJAX call to your Laravel backend
    fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
      .then((response) => response.json())
      .then((data) => {
        this.products = data.products || []
        this.displayProducts(this.products)
      })
      .catch((error) => {
        console.error("Error searching products:", error)
      })
  }

  displayProducts(products) {
    const productsList = document.getElementById("products-list")
    if (!productsList) return

    if (products.length === 0) {
      productsList.innerHTML = '<div class="empty-state">No Products to display</div>'
      return
    }

    productsList.innerHTML = products
      .map(
        (product) => `
      <div class="product-card" onclick="pos.addToCart(${product.id})">
        <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">
          ${product.name}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span style="color: #6b7280; font-size: 0.875rem;">${product.sku || ""}</span>
          <span style="color: #6366f1; font-weight: 600;">$${Number.parseFloat(product.price).toFixed(2)}</span>
        </div>
      </div>
    `,
      )
      .join("")
  }

  addToCart(productId) {
    const product = this.products.find((p) => p.id === productId)
    if (!product) return

    const existingItem = this.cart.find((item) => item.id === productId)

    if (existingItem) {
      existingItem.quantity += 1
    } else {
      this.cart.push({
        id: product.id,
        name: product.name,
        price: Number.parseFloat(product.price),
        quantity: 1,
      })
    }

    this.updateDisplay()
  }

  removeFromCart(productId) {
    this.cart = this.cart.filter((item) => item.id !== productId)
    this.updateDisplay()
  }

  updateQuantity(productId, quantity) {
    const item = this.cart.find((item) => item.id === productId)
    if (item) {
      item.quantity = Number.parseInt(quantity) || 1
      this.updateDisplay()
    }
  }

  updateDisplay() {
    this.updateCartTable()
    this.updateSummary()
  }

  updateCartTable() {
    const tbody = document.getElementById("cart-items")
    if (!tbody) return

    if (this.cart.length === 0) {
      tbody.innerHTML =
        '<tr><td colspan="5" style="text-align: center; color: #6b7280; padding: 2rem;">Cart is empty</td></tr>'
      return
    }

    tbody.innerHTML = this.cart
      .map((item) => {
        const subtotal = item.price * item.quantity
        return `
        <tr>
          <td>
            <div class="product-name">${item.name}</div>
          </td>
          <td>
            <input 
              type="number" 
              class="quantity-input" 
              value="${item.quantity}" 
              min="1"
              onchange="pos.updateQuantity(${item.id}, this.value)"
            />
          </td>
          <td>$${item.price.toFixed(2)}</td>
          <td>$${subtotal.toFixed(2)}</td>
          <td>
            <button class="remove-btn" onclick="pos.removeFromCart(${item.id})">
              ✕
            </button>
          </td>
        </tr>
      `
      })
      .join("")
  }

  updateSummary() {
    const itemCount = this.cart.reduce((sum, item) => sum + item.quantity, 0)
    const subtotal = this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
    const total = subtotal - this.discount + this.orderTax + this.shipping

    // Update items count
    const itemsElement = document.getElementById("items-count")
    if (itemsElement) {
      itemsElement.textContent = itemCount.toFixed(2)
    }

    // Update total
    const totalElement = document.getElementById("cart-total")
    if (totalElement) {
      totalElement.textContent = subtotal.toFixed(2)
    }

    // Update discount
    const discountElement = document.getElementById("discount-value")
    if (discountElement) {
      discountElement.textContent = this.discount.toFixed(2)
    }

    // Update order tax
    const taxElement = document.getElementById("tax-value")
    if (taxElement) {
      taxElement.textContent = this.orderTax.toFixed(2)
    }

    // Update shipping
    const shippingElement = document.getElementById("shipping-value")
    if (shippingElement) {
      shippingElement.textContent = this.shipping.toFixed(2)
    }

    // Update final total
    const finalTotalElement = document.getElementById("final-total")
    if (finalTotalElement) {
      finalTotalElement.textContent = total.toFixed(2)
    }
  }

  selectCustomer(customerId) {
    this.customer = customerId
    console.log("Selected customer:", customerId)
  }

  handleAction(action) {
    console.log("Action:", action)

    switch (action) {
      case "draft":
        this.saveDraft()
        break
      case "quotation":
        this.createQuotation()
        break
      case "suspend":
        this.suspendSale()
        break
      case "credit":
        this.creditSale()
        break
      case "card":
        this.cardPayment()
        break
      case "multiple":
        this.multiplePayment()
        break
      case "cash":
        this.cashPayment()
        break
      case "cancel":
        this.cancelSale()
        break
      case "recent":
        this.showRecentTransactions()
        break
      default:
        console.log("Unknown action:", action)
    }
  }

  saveDraft() {
    if (this.cart.length === 0) {
      alert("Cart is empty")
      return
    }

    // Send to Laravel backend
    fetch("/api/pos/draft", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        customer_id: this.customer,
        items: this.cart,
        discount: this.discount,
        tax: this.orderTax,
        shipping: this.shipping,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        alert("Draft saved successfully")
        console.log("Draft saved:", data)
      })
      .catch((error) => {
        console.error("Error saving draft:", error)
        alert("Error saving draft")
      })
  }

  cashPayment() {
    if (this.cart.length === 0) {
      alert("Cart is empty")
      return
    }

    // Send to Laravel backend
    fetch("/api/pos/payment", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        customer_id: this.customer,
        items: this.cart,
        discount: this.discount,
        tax: this.orderTax,
        shipping: this.shipping,
        payment_method: "cash",
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        alert("Payment processed successfully")
        this.clearCart()
        console.log("Payment processed:", data)
      })
      .catch((error) => {
        console.error("Error processing payment:", error)
        alert("Error processing payment")
      })
  }

  cancelSale() {
    if (confirm("Are you sure you want to cancel this sale?")) {
      this.clearCart()
    }
  }

  clearCart() {
    this.cart = []
    this.customer = null
    this.discount = 0
    this.orderTax = 0
    this.shipping = 0

    const searchInput = document.getElementById("product-search")
    if (searchInput) searchInput.value = ""

    const customerSelect = document.getElementById("customer-select")
    if (customerSelect) customerSelect.value = ""

    this.displayProducts([])
    this.updateDisplay()
  }

  createQuotation() {
    console.log("Create quotation")
    // Implement quotation logic
  }

  suspendSale() {
    console.log("Suspend sale")
    // Implement suspend logic
  }

  creditSale() {
    console.log("Credit sale")
    // Implement credit sale logic
  }

  cardPayment() {
    console.log("Card payment")
    // Implement card payment logic
  }

  multiplePayment() {
    console.log("Multiple payment")
    // Implement multiple payment logic
  }

  showRecentTransactions() {
    console.log("Show recent transactions")
    // Implement recent transactions logic
  }
}

// Initialize POS system when DOM is ready
let pos
document.addEventListener("DOMContentLoaded", () => {
  pos = new POSSystem()
})
