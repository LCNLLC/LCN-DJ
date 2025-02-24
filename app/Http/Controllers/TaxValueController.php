<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxValue;
use App\Models\Product;
use App\Models\ProductTax;

class TaxValueController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        // $this->middleware(['permission:vat_&_tax_setup'])->only('index','create','edit','destroy');
        // $this->middleware(['permission:tax_value_setup'])->only('index','create','edit','destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_taxes = TaxValue::orderBy('created_at', 'desc')->get();
        return view('backend.setup_configurations.tax.tax-value-index', compact('all_taxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.setup_configurations.tax.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_type' => 'required|in:1,2,3',
            'tax_value' => 'required|numeric',
            'tax_type' => 'required|in:amount,percent',
        ]);

        // Create new TaxValue entry
        $tax = new TaxValue();
        $tax->product_type = $request->product_type;
        $tax->tax_value = $request->tax_value;
        $tax->tax_type = $request->tax_type;

        if ($tax->save()) {
            // $productType = $request->product_type;
            // $productIds = [];

            // // Update products in chunks based on product_type
            // if ($productType == 1 || $productType == 2) {
            //     // Set the product type condition
            //     $typeToQuery = $productType == 1 ? 1 : 3;

            //     // Retrieve products in chunks
            //     Product::where('product_type', $typeToQuery)->chunk(100, function ($products) use ($request, &$productIds) {
            //         foreach ($products as $product) {
            //             $product->tax = $request->tax_value;
            //             $product->tax_type = $request->tax_type;
            //             $product->save();
            //             $productIds[] = $product->id;
            //         }
            //     });

            //     // Update the product_taxes table in chunks if needed
            //     if (!empty($productIds)) {
            //         ProductTax::whereIn('product_id', $productIds)->chunkById(100, function ($productTaxes) use ($request) {
            //             foreach ($productTaxes as $productTax) {
            //                 $productTax->tax = $request->tax_value;
            //                 $productTax->tax_type = $request->tax_type;
            //                 $productTax->save();
            //             }
            //         });
            //     }
            // }

            flash('Tax has been inserted successfully')->success();
            return redirect()->route('tax-value.index');
        } else {
            flash('Something went wrong')->error();
            return back();
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tax = TaxValue::findOrFail($id);
        return view('backend.setup_configurations.tax.tax-value-edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'product_type' => 'required|in:1,2,3',
            'tax_value' => 'required|numeric',
            'tax_type' => 'required|in:amount,percent',
        ]);

        // Find the TaxValue record
        $tax = TaxValue::findOrFail($id);
        $tax->product_type = $request->product_type;
        $tax->tax_value = $request->tax_value;
        $tax->tax_type = $request->tax_type;

        if ($tax->save()) {
            // Determine the product type to update and apply chunking
            // $productType = $request->product_type;
            // $typeToQuery = $productType == 1 ? 1 : 3;

            // // Retrieve and update products in chunks
            // Product::where('product_type', $typeToQuery)
            //     ->chunk(100, function ($products) use ($request) {
            //         $updateData = [
            //             'tax' => $request->tax_value,
            //             'tax_type' => $request->tax_type,
            //         ];

            //         // Collect product IDs for the product_taxes update
            //         $productIds = $products->pluck('id')->toArray();

            //         // Mass update products
            //         Product::whereIn('id', $productIds)->update($updateData);

            //         // Update the product_taxes table in chunks
            //         if (!empty($productIds)) {
            //             ProductTax::whereIn('product_id', $productIds)
            //                 ->update($updateData);
            //         }
                // });

            flash('Tax has been updated successfully')->success();
            return redirect()->route('tax-value.index');
        } else {
            flash('Something went wrong')->error();
            return back();
        }
    }


    /**
     * Change the tax status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function change_tax_status(Request $request)
    {
        $tax = TaxValue::findOrFail($request->id);
        $tax->tax_status = !$tax->tax_status;

        if ($tax->save()) {
            return 1;
        }
        return 0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (TaxValue::destroy($id)) {
            flash('Tax has been deleted successfully')->success();
            return redirect()->route('tax-value.index');
        } else {
            flash('Something went wrong')->error();
            return back();
        }
    }
}
