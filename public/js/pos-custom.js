// Declare jQuery ($) variable
const $ = window.jQuery

$(document).ready(() => {
  // Initialize product grid
  loadProductGrid("all")

  // Category button click handler
  $(".category-btn").on("click", function () {
    $(".category-btn")
      .removeClass("active tw-bg-cyan-500 tw-text-white")
      .addClass("tw-bg-white tw-text-gray-700 tw-border tw-border-gray-200")
    $(this)
      .removeClass("tw-bg-white tw-text-gray-700 tw-border tw-border-gray-200")
      .addClass("active tw-bg-cyan-500 tw-text-white")

    var categoryId = $(this).data("category")
    loadProductGrid(categoryId)
  })

  // Hide "no products" row when products are added
  $(document).on("DOMNodeInserted", "#pos_table tbody", () => {
    if ($("#pos_table tbody tr.product_row").length > 0) {
      $("#no_products_row").hide()
    } else {
      $("#no_products_row").show()
    }
  })
})

// Load products into grid
function loadProductGrid(categoryId) {
  var locationId = $("#location_id").val()

  if (!locationId) {
    window.toastr.warning("Please select a location first")
    return
  }

  $("#suggestion_page_loader").show()
  $("#product_grid").html("")

  $.ajax({
    method: "GET",
    url: "/sells/pos/get-product-suggestion",
    data: {
      category_id: categoryId === "all" ? "" : categoryId,
      location_id: locationId,
      page: 1,
    },
    dataType: "html",
    success: (result) => {
      $("#product_grid").html(result)
      $("#suggestion_page_loader").hide()
    },
    error: () => {
      $("#suggestion_page_loader").hide()
      window.toastr.error("Failed to load products")
    },
  })
}

// Add product to cart from grid
function addProductToCart(variationId) {
  if ($("#location_id").val() == "") {
    window.toastr.warning("Please select a location")
    return
  }

  window.pos_product_row(variationId)
}

// Update the product row rendering to include thumbnail
var originalPosProductRow = window.pos_product_row
window.pos_product_row = (variation_id, purchase_line_id, weighing_scale_barcode, quantity) => {
  // Call original function
  if (originalPosProductRow) {
    originalPosProductRow(variation_id, purchase_line_id, weighing_scale_barcode, quantity)
  }

  // Hide no products row
  $("#no_products_row").hide()
}
