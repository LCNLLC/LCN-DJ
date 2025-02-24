<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductTranslation;
use App\Models\Package;

class ProductBoostController extends Controller
{
    public function index()
	{
        $packages = Package::where('status',1)->orderBy('created_at', 'desc');
        $packages = $packages->paginate(6);

        return view('seller.boost_plan.index', compact('packages'));
	}

	public function add_plan($id)
	{
        $packages = Package::where('status',1)->orderBy('created_at', 'desc');
        $packages = $packages->paginate(6);
        $product_id = $id;
        return view('seller.boost_plan.index', compact('packages','product_id'));
	}
}
