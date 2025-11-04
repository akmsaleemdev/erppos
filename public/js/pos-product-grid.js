;(($) => {
  // Declare toastr and __currency_trans_from_en variables
  const toastr = window.toastr
  const __currency_trans_from_en = window.__currency_trans_from_en

  // Product Grid Manager
  window.POSProductGrid = {
    currentCategory: "all",
    products: [],

    init: function () {
      this.bindEvents()
      this.loadProducts()
    },

    bindEvents: function () {
      const self = this

      // Category filter buttons
      $(document).on("click", ".category-btn", function () {
        $(".category-btn").removeClass("active")
        $(this).addClass("active")

        self.currentCategory = $(this).data("category")
        self.filterProducts()
      })

      // Product card click
      $(document).on("click", ".product-card", function () {
        const productData = $(this).data("product")
        self.addToCart(productData)
      })

      // Search functionality
      $(document).on("keyup", "#search_product", function () {
        const searchTerm = $(this).val().toLowerCase()
        self.searchProducts(searchTerm)
      })
    },

    loadProducts: function () {
      
      const locationId = $("#location_id").val()

      if (!locationId) {
        console.warn("Location ID not found")
        return
      }

      $.ajax({
        url: "/sells/pos/get-product-suggestion",
        method: "GET",
        data: {
          location_id: locationId,
          term: "",
        },
        success: (response) => {
          this.products = response || []
          this.renderProducts(this.products)
        },
        error: (xhr, status, error) => {
          console.error("Error loading products:", error)
          if (toastr) {
            toastr.error("Failed to load products")
          }
        },
      })
    },

    filterProducts: function () {
      

      if (this.currentCategory === "all") {
        this.renderProducts(this.products)
        return
      }

      const filtered = this.products.filter((product) => product.category_id == this.currentCategory)

      this.renderProducts(filtered)
    },

    searchProducts: function (searchTerm) {
      

      if (!searchTerm) {
        this.filterProducts()
        return
      }

      const filtered = this.products.filter((product) => {
        const name = (product.name || "").toLowerCase()
        const sku = (product.sku || "").toLowerCase()
        return name.includes(searchTerm) || sku.includes(searchTerm)
      })

      this.renderProducts(filtered)
    },

    renderProducts: function (products) {
      const grid = $("#product_grid")
      const noProductsMsg = $("#no_products_message")

      grid.empty()

      if (!products || products.length === 0) {
        grid.append(`
                    <div class="tw-col-span-full tw-text-center tw-py-8 tw-text-gray-500">
                        <i class="fa fa-shopping-bag tw-text-4xl tw-mb-2"></i>
                        <p>No Products to display</p>
                    </div>
                `)
        return
      }

      products.forEach(
        function (product) {
          const card = this.createProductCard(product)
          grid.append(card)
        }.bind(this),
      )
    },

    createProductCard: function (product) {
      const imageUrl = product.image_url || product.image || "/img/default.png"
      const productName = product.name || product.text || "Unnamed Product"
      const productPrice = product.selling_price_inc_tax || product.default_sell_price || "0.00"
      const variationId = product.variation_id || product.id

      // Escape quotes in product data for JSON
      const productJson = JSON.stringify(product).replace(/"/g, "&quot;")

      return `
                <div class="product-card" data-variation-id="${variationId}" data-product='${productJson}'>
                    <img src="${imageUrl}" 
                         alt="${productName}" 
                         class="product-card-image" 
                         loading="lazy"
                         onerror="this.src='/img/default.png'">
                    <div class="product-card-name">${productName}</div>
                    <div class="product-card-price">${this.formatPrice(productPrice)}</div>
                </div>
            `
    },

    formatPrice: (price) => {
      // Use existing currency formatting if available
      if (__currency_trans_from_en && typeof __currency_trans_from_en === "function") {
        return __currency_trans_from_en(price, true)
      }
      return price
    },

    addToCart: (product) => {
      if (window.pos_product_row && typeof window.pos_product_row === "function") {
        // Call existing function to add product to cart
        window.pos_product_row(product)

        // Visual feedback
        if (toastr) {
          toastr.success("Product added to cart")
        }
      } else {
        console.error("pos_product_row function not found")
        if (toastr) {
          toastr.error("Unable to add product to cart")
        }
      }
    },
  }

  // Initialize on document ready
  $(document).ready(() => {
    if ($("#product_grid").length > 0) {
      window.POSProductGrid.init()
    }
  })
})(window.jQuery)
