<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CommissionHistory;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Search;
use App\Models\Shop;
use Auth;

class ReportController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:in_house_product_sale_report'])->only('in_house_sale_report');
        $this->middleware(['permission:seller_products_sale_report'])->only('seller_sale_report');
        $this->middleware(['permission:products_stock_report'])->only('stock_report');
        $this->middleware(['permission:product_wishlist_report'])->only('wish_report');
        $this->middleware(['permission:user_search_report'])->only('user_search_report');
        $this->middleware(['permission:commission_history_report'])->only('commission_history');
        $this->middleware(['permission:wallet_transaction_report'])->only('wallet_transaction_history');
    }

    // Stock Report using "current_stock" for filtering and summary
    public function stock_report(Request $request)
    {
        $sort_by = $request->input('category_id', null);
        $stock_filter = $request->input('stock_filter', null);

        $productsQuery = Product::orderBy('created_at', 'desc');

        if ($sort_by) {
            $productsQuery->where('category_id', $sort_by);
        }
        if ($stock_filter) {
            if ($stock_filter === 'low') {
                $threshold = config('settings.low_stock_threshold', 10);
                $productsQuery->where('current_stock', '<=', $threshold)
                              ->where('current_stock', '>', 0);
            } elseif ($stock_filter === 'out') {
                $productsQuery->where('current_stock', '<=', 0);
            }
        }
        $products = $productsQuery->paginate(15);

        // Stock summary grouped by category using "current_stock"
        $stockSummary = Product::selectRaw('category_id, SUM(current_stock) as total_stock')
                            ->groupBy('category_id')
                            ->get();
        $stockGraphData = $stockSummary;

        return view('backend.reports.stock_report', compact('products', 'sort_by', 'stock_filter', 'stockSummary', 'stockGraphData'));
    }

    // In-House Sales Report using "num_of_sale" and filtering by category
    public function in_house_sale_report(Request $request)
    {
        $sort_by = $request->input('category_id', null);
        $productsQuery = Product::where('added_by', 'admin')
                            ->orderBy('num_of_sale', 'desc');
        if ($sort_by) {
            $productsQuery->where('category_id', $sort_by);
        }
        $products = $productsQuery->paginate(15);

        // Sales summary by category
        $salesSummary = Product::selectRaw('category_id, SUM(num_of_sale) as total_sales')
                            ->where('added_by', 'admin')
                            ->groupBy('category_id')
                            ->get();
        $salesGraphData = $salesSummary;

        return view('backend.reports.in_house_sale_report', compact('products', 'sort_by', 'salesSummary', 'salesGraphData'));
    }

    // Seller Sales Report (remains unchanged)
    public function seller_sale_report(Request $request)
    {
        $sort_by =null;
        // $sellers = User::where('user_type', 'seller')->orderBy('created_at', 'desc');
        $sellers = Shop::with('user')->orderBy('created_at', 'desc');
        if ($request->has('verification_status')){
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers','sort_by'));
    }

    // Wishlist Report updated: removed subcategory eager loading and filtering
    public function wish_report(Request $request)
    {
        $sort_by = $request->input('category_id', null);
        $query = Product::query();

        if ($sort_by) {
            $query->where('category_id', $sort_by);
        }
        // Removed subcategory filter as the column/relationship doesn't exist
        if ($request->filled('stock_filter')) {
            $stock_filter = $request->stock_filter;
            if ($stock_filter === 'low') {
                $threshold = config('settings.low_stock_threshold', 10);
                $query->where('current_stock', '<=', $threshold)
                      ->where('current_stock', '>', 0);
            } elseif ($stock_filter === 'out') {
                $query->where('current_stock', '<=', 0);
            }
        }
        $query->orderBy('created_at', 'desc');
        $products = $query->paginate(10);

        // Wishlist summary: count the number of products per category
        $wishlistSummary = Product::selectRaw('category_id, COUNT(id) as total_wishes')
                                ->groupBy('category_id')
                                ->get();
        $wishlistGraphData = $wishlistSummary;

        return view('backend.reports.wish_report', compact('products', 'sort_by', 'wishlistSummary', 'wishlistGraphData'));
    }

    // User Search Report (unchanged)
    public function user_search_report(Request $request)
    {
        $searchQuery = Search::query();

        if ($request->filled('order_by')) {
            $order_by = $request->order_by;
            if ($order_by == 'alphabetical') {
                $searchQuery->orderBy('search_term', 'asc');
            } else {
                $searchQuery->orderBy('count', 'desc');
            }
        } else {
            $searchQuery->orderBy('count', 'desc');
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $searchQuery->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $searches = $searchQuery->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    // Commission History Report (unchanged)
    public function commission_history(Request $request)
    {
        $seller_id = Auth::user()->user_type == 'seller' ? Auth::user()->id : null;
        if ($request->filled('seller_id')) {
            $seller_id = $request->seller_id;
        }

        $commissionQuery = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->filled('date_range')) {
            $date_range = $request->date_range;
            $dates = explode(" / ", $date_range);
            if (count($dates) == 2) {
                $commissionQuery->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }

        if ($request->filled('transaction_value')) {
            $commissionQuery->where('transaction_value', '>=', $request->transaction_value);
        }
        if ($request->filled('payment_method')) {
            $commissionQuery->where('payment_method', $request->payment_method);
        }

        if ($seller_id) {
            $commissionQuery->where('seller_id', $seller_id);
        }

        $commission_history = $commissionQuery->paginate(10);

        $summary = CommissionHistory::selectRaw('COUNT(*) as total_transactions, SUM(transaction_value) as total_value')
                    ->when($seller_id, function($query) use ($seller_id) {
                        return $query->where('seller_id', $seller_id);
                    })
                    ->when($request->filled('date_range'), function($query) use ($request) {
                        $dates = explode(" / ", $request->date_range);
                        if (count($dates) == 2) {
                            return $query->whereBetween('created_at', [$dates[0], $dates[1]]);
                        }
                    })
                    ->first();

        if (Auth::user()->user_type == 'seller') {
            return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'summary'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'summary'));
    }

    // Wallet Transaction History (unchanged)
    public function wallet_transaction_history(Request $request)
    {
        $user_id = $request->input('user_id', null);
        $date_range = $request->input('date_range', null);

        $users_with_wallet = User::whereIn('id', function($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $walletQuery = Wallet::orderBy('created_at', 'desc');
        if ($date_range) {
            $dates = explode(" / ", $date_range);
            if (count($dates) == 2) {
                $walletQuery->whereBetween('created_at', [$dates[0], $dates[1]]);
            }
        }
        if ($user_id) {
            $walletQuery->where('user_id', $user_id);
        }

        $wallets = $walletQuery->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }
}
