<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use App\Models\BusinessLocation;

class SellPosController extends Controller
{

    public function create()
    {
        $business_id = request()->session()->get('user.business_id');
        
        $categories = Category::where('business_id', $business_id)
            ->where('category_type', 'product')
            ->select('id', 'name')
            ->get();
        
        $customers = Contact::where('business_id', $business_id)
            ->where('type', 'customer')
            ->select('id', 'name', 'mobile')
            ->get();
        
        $default_location = BusinessLocation::where('business_id', $business_id)
            ->first();
        
        
        return view('sale_pos.create')->with(compact(
            'categories',
            'customers',
            'default_location'
            // ... your other existing variables
        ));
    }

    public function getProductSuggestion(Request $request)
    {
        $category_id = $request->get('category_id');
        $brand_id = $request->get('brand_id');
        $location_id = $request->get('location_id');
        $page = $request->get('page', 1);
        $search_term = $request->get('term', '');
        
        $business_id = request()->session()->get('user.business_id');
        
        $query = Product::join('variations', 'products.id', '=', 'variations.product_id')
            ->join('product_variations', 'variations.product_variation_id', '=', 'product_variations.id')
            ->leftJoin('variation_location_details as vld', function($join) use ($location_id) {
                $join->on('variations.id', '=', 'vld.variation_id')
                     ->where('vld.location_id', '=', $location_id);
            })
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->where('products.business_id', $business_id)
            ->where('products.not_for_selling', 0)
            ->where('products.is_inactive', 0);
        
        if (!empty($search_term)) {
            $query->where(function($q) use ($search_term) {
                $q->where('products.name', 'like', '%' . $search_term . '%')
                  ->orWhere('products.sku', 'like', '%' . $search_term . '%')
                  ->orWhere('variations.sub_sku', 'like', '%' . $search_term . '%');
            });
        }
        
        if (!empty($category_id) && $category_id !== 'all') {
            $query->where('products.category_id', $category_id);
        }
        
        if (!empty($brand_id)) {
            $query->where('products.brand_id', $brand_id);
        }
        
        $products = $query->select(
                'products.id as product_id',
                'products.name',
                'products.image',
                'products.type',
                'variations.id as variation_id',
                'variations.name as variation_name',
                'variations.sub_sku',
                'variations.sell_price_inc_tax as selling_price',
                'vld.qty_available'
            )
            ->paginate(20);
        
        $formatted_products = $products->map(function($product) {
            return [
                'variation_id' => $product->variation_id,
                'product_id' => $product->product_id,
                'name' => $product->name . ($product->variation_name != 'DUMMY' ? ' - ' . $product->variation_name : ''),
                'image' => $product->image ? asset('uploads/img/' . $product->image) : asset('img/default.png'),
                'selling_price' => $product->selling_price,
                'qty_available' => $product->qty_available ?? 0,
                'sub_sku' => $product->sub_sku
            ];
        });
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'products' => $formatted_products,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total()
                ]
            ]);
        }
        
        // For non-AJAX requests, return view
        return view('sale_pos.partials.product_card', ['products' => $formatted_products]);
    }

}
