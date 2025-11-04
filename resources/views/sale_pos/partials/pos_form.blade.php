{{-- Updated POS form layout to match reference design --}}
<div class="row tw-mb-4">
    <div class="col-sm-12">
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
            <div class="tw-flex-1">
                <div class="input-group">
                    <span class="input-group-addon tw-bg-white">
                        <i class="fa fa-user"></i>
                    </span>
                    {!! Form::select('contact_id', [], null, [
                        'class' => 'form-control',
                        'id' => 'customer_id',
                        'placeholder' => 'Walk-In Customer',
                        'required',
                    ]) !!}
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default tw-bg-cyan-500 tw-text-white tw-border-cyan-500 hover:tw-bg-cyan-600" data-toggle="modal" data-target=".contact_modal">
                            <i class="fa fa-plus"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="tw-mb-3">
            <div class="input-group">
                <span class="input-group-addon tw-bg-white">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" class="form-control" id="search_product" 
                    placeholder="Enter Product name / SKU / Scan bar code" 
                    style="border-radius: 0 8px 8px 0;">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default tw-bg-cyan-500 tw-text-white tw-border-cyan-500 hover:tw-bg-cyan-600" data-toggle="modal" data-target=".quick_add_product_modal">
                        <i class="fa fa-plus"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Updated product table with cleaner design --}}
<div class="row">
    <div class="col-sm-12">
        <div class="tw-bg-white tw-rounded-lg tw-overflow-hidden">
            <table class="table table-condensed table-bordered" id="pos_table" style="margin-bottom: 0;">
                <thead class="tw-bg-gray-50">
                    <tr>
                        <th class="tw-text-sm tw-font-semibold tw-text-gray-700" style="width: 40%;">Product</th>
                        <th class="tw-text-center tw-text-sm tw-font-semibold tw-text-gray-700" style="width: 15%;">Quantity</th>
                        <th class="tw-text-center tw-text-sm tw-font-semibold tw-text-gray-700" style="width: 20%;">Price inc. tax</th>
                        <th class="tw-text-center tw-text-sm tw-font-semibold tw-text-gray-700" style="width: 20%;">Subtotal</th>
                        <th class="tw-text-center tw-text-sm tw-font-semibold tw-text-gray-700" style="width: 5%;"><i class="fa fa-trash"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tw-text-center tw-text-gray-400" id="no_products_row">
                        <td colspan="5" class="tw-py-12">
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                <div class="tw-w-16 tw-h-16 tw-bg-cyan-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mb-3">
                                    <i class="fa fa-shopping-bag tw-text-cyan-500 tw-text-2xl"></i>
                                </div>
                                <p class="tw-text-gray-500 tw-text-sm">No products added yet</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<input type="hidden" name="is_quotation" id="is_quotation" value="0">
<input type="hidden" id="product_row_count" value="0">
