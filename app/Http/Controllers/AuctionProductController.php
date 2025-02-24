<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Cache;
use Artisan;
use Combinations;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductTax;
use CoreComponentRepository;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Services\ProductService;
use App\Models\ProductTranslation;
use App\Services\ProductTaxService;
use App\Http\Requests\ProductRequest;
use App\Services\ProductStockService;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Services\ProductFlashDealService;
use App\Models\AuctionProductBid;
use App\Models\ProductStock;
use AizPackages\CombinationGenerate\Services\CombinationService;



class AuctionProductController extends Controller
{
	protected $productService;
	protected $productTaxService;
	protected $productFlashDealService;
	protected $productStockService;

	public function __construct(
		ProductService $productService,
		ProductTaxService $productTaxService,
		ProductFlashDealService $productFlashDealService,
		ProductStockService $productStockService
	) {
		$this->productService = $productService;
		$this->productTaxService = $productTaxService;
		$this->productFlashDealService = $productFlashDealService;
		$this->productStockService = $productStockService;

        // Staff Permission Check
		$this->middleware(['permission:add_new_product'])->only('create');
		$this->middleware(['permission:show_all_products'])->only('all_products');
		$this->middleware(['permission:show_in_house_products'])->only('inhouse_auction_products');
		$this->middleware(['permission:show_seller_products'])->only('seller_auction_products');
		$this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
		$this->middleware(['permission:product_duplicate'])->only('duplicate');
		$this->middleware(['permission:product_delete'])->only('destroy');
        $this->middleware(['permission:bidProductStatus'])->only('bidProductStatus');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inhouse_auction_products(Request $request)
    {	///admin auction products
    	CoreComponentRepository::instantiateShopRepository();

    	$type = 'In House';
    	$col_name = null;
    	$query = null;
    	$sort_search = null;

    	$products = Product::where('added_by', 'admin')->where('product_type', 3)->where('wholesale_product', 0)->withCount('bids');

    	if ($request->type != null) {
    		$var = explode(",", $request->type);
    		$col_name = $var[0];
    		$query = $var[1];
    		$products = $products->orderBy($col_name, $query);
    		$sort_type = $request->type;
    	}
    	if ($request->search != null) {
    		$sort_search = $request->search;
    		$products = $products
    		->where('name', 'like', '%' . $sort_search . '%')
    		->orWhereHas('stocks', function ($q) use ($sort_search) {
    			$q->where('sku', 'like', '%' . $sort_search . '%');
    		});
    	}

    	$products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

    	return view('backend.product.auction_products.index', compact('products', 'type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_auction_products(Request $request)
    {
    	$col_name = null;
    	$query = null;
    	$seller_id = null;
    	$sort_search = null;
    	$products = Product::where('added_by', 'seller')->where('product_type', 3)->where('wholesale_product', 0)->withCount('bids');
    	if ($request->has('user_id') && $request->user_id != null) {
    		$products = $products->where('user_id', $request->user_id);
    		$seller_id = $request->user_id;
    	}
    	if ($request->search != null) {
    		$products = $products
    		->where('name', 'like', '%' . $request->search . '%');
    		$sort_search = $request->search;
    	}
    	if ($request->type != null) {
    		$var = explode(",", $request->type);
    		$col_name = $var[0];
    		$query = $var[1];
    		$products = $products->orderBy($col_name, $query);
    		$sort_type = $request->type;
    	}

    	$products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);
    	$type = 'Seller';
    	return view('backend.product.auction_products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function auction_product_list_seller(Request $request)
    {     
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->where('product_type', 3)->withCount('bids')->where('wholesale_product', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);

        return view('seller.product.auctionproducts.index', compact('products', 'search'));
    }

    public function all_auction_product_list(Request $request)
    {
    	$col_name = null;
    	$query = null;
    	$seller_id = null;
    	$sort_search = null;
    	$products = Product::orderBy('created_at', 'desc')->where('product_type', 3)->where('wholesale_product', 0);
    	if ($request->has('user_id') && $request->user_id != null) {
    		$products = $products->where('user_id', $request->user_id);
    		$seller_id = $request->user_id;
    	}
    	if ($request->search != null) {
    		$sort_search = $request->search;
    		$products = $products
    		->where('name', 'like', '%' . $sort_search . '%')
    		->orWhereHas('stocks', function ($q) use ($sort_search) {
    			$q->where('sku', 'like', '%' . $sort_search . '%');
    		});
    	}
    	if ($request->type != null) {
    		$var = explode(",", $request->type);
    		$col_name = $var[0];
    		$query = $var[1];
    		$products = $products->orderBy($col_name, $query);
    		$sort_type = $request->type;
    	}

    	$products = $products->paginate(15);
    	$type = 'All';

    	return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	CoreComponentRepository::initializeCache();

    	$categories = Category::where('parent_id', 0)
    	->where('digital', 0)
    	->with('childrenCategories')
    	->get();

    	return view('backend.product.products.create', compact('categories'));
    }
   

    public function show($id)
    {
        //
    }

    
    public function admin_product_edit(Request $request, $id)
    {
    	CoreComponentRepository::initializeCache();

    	$product = Product::findOrFail($id);
    	if ($product->digital == 1) {
    		return redirect('admin/digitalproducts/' . $id . '/edit');
    	}

    	$lang = $request->lang;
    	$tags = json_decode($product->tags);
    	$categories = Category::where('parent_id', 0)
    	->where('digital', 0)
    	->with('childrenCategories')
    	->get();
    	return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function bid($id)
    {
        CoreComponentRepository::instantiateShopRepository();

        $bids = AuctionProductBid::where('product_id', $id)->orderBy('amount', 'desc')->paginate(15);

        return view('backend.product.auction_products.bids', compact('bids'));
    }
    public function seller_bid($id)
    {
        CoreComponentRepository::instantiateShopRepository();

        $bids = AuctionProductBid::where('product_id', $id)->orderBy('amount', 'desc')->paginate(15);

        return view('seller.product.auctionproducts.bids', compact('bids'));
    }
    public function bidProductStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->auction_product_status = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function bidProductStatusSeller(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->auction_product_status = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function bidStatus(Request $request)
    {
        $bid = AuctionProductBid::findOrFail($request->id);
        $bidStatus = '';
        if($request->status == 2)
        {
            $product = Product::findOrFail($bid->product_id);
            $product->auction_product_status = 2;
            $product->save();
            $bidStatus = 'Accepted';
        }
        else
        {
            $product = Product::findOrFail($bid->product_id);
            $product->auction_product_status = 1;
            $product->save();
            if($request->status == 3)
            {
                $bidStatus = 'Rejected';
            }
            else
            {
                 $bidStatus = 'Pending';
            }
        }
        BidStatusNotification($bid->user_id, $bidStatus, $product->name);
        $bid->status = $request->status;
        if ($bid->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function SellerBidStatus(Request $request)
    {
        $bid = AuctionProductBid::findOrFail($request->id);
        $bidStatus = '';
        if($request->status == 2)
        {
            $product = Product::findOrFail($bid->product_id);
            $product->auction_product_status = 2;
            $product->save();
            $bidStatus = 'Accepted';
        }
        else
        {
            $product = Product::findOrFail($bid->product_id);
            $product->auction_product_status = 1;
            $product->save();
            if($request->status == 3)
            {
                $bidStatus = 'Rejected';
            }
            else
            {
                 $bidStatus = 'Pending';
            }
        }
        BidStatusNotification($bid->user_id, $bidStatus, $product->name);
        $bid->status = $request->status;
        if ($bid->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function showBidProducts()
    {
        $user = auth()->user();
        $products = Product::whereHas('bids', function ($query) use ($user) {
        $query->where('user_id', $user->id)->where('status', 'accepted');
        })->paginate(5);
        return view('frontend.user.view_bidlist', compact('products'));
    }
    
}

