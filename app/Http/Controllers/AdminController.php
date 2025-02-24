<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Referral;
use Artisan;
use Cache;
use CoreComponentRepository;

class AdminController extends Controller
{



    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard(Request $request)
    {
        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){
            $num_of_sale_data = $qty_data = '';
            foreach ($root_categories as $category) {
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = $sale = 0;
                foreach ($products as $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty . ',';
                $num_of_sale_data .= $sale . ',';
            }
            return [
                'num_of_sale_data' => $num_of_sale_data,
                'qty_data'         => $qty_data,
            ];
        });

        // Retrieve published products; adjust the query as necessary.
        // Product::where('published', 1)->get();
        // dd(Product::where('published', 1)->first());
        // $products = Product::where('published', 1)->first();
//, 'products'
        return view('backend.dashboard', compact('root_categories', 'cached_graph_data'));
    }

    function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    public function systemCache()
    {
        $page_title = "System Cache";
        return view('backend.system.cache', compact('page_title'));
    }

    public function systemClearCache()
    {

        Artisan::call('optimize:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    public function systemClearView()
    {

        \Artisan::call('view:clear');
        \Artisan::call('view:clear');
        flash(translate('System View cache cleared Successfully'))->success();
        return back();
    }

    public function systemClearConfig()
    {

        \Artisan::call('config:clear');
        \Artisan::call('config:cache');

        flash(translate('System Config cache cleared Successfully.'))->success();
        return back();

    }

    public function systemClearRoute()
    {

        \Artisan::call('route:clear');

        flash(translate('System Routes cache cleared Successfully..'))->success();
        return back();
    }

    public function systemInformation()
    {
        $page_title = "System Information";
        $packages_json = json_decode(file_get_contents(base_path().'/composer.lock'), true);
        // dd($packages_json);
        $packages_json = $packages_json['packages'];

        foreach($packages_json as $key => $package) {
            if(isset($package['name']) && $package['name'] === 'laravel/framework') {
                $packages_json[$key]['name'] = 'custom/framework';
                $packages_json[$key]['description'] = str_replace('Laravel Framework', 'Custom Framework', $package['description'] ?? '');
            }

        }

        return view('backend.system.sysinfo', compact('page_title', 'packages_json'));
    }

    public function refIndex()
    {
        $page_title = 'Manage Referral';
        $trans = Referral::get();
        $analytics = [
            'total_referrals' => Referral::count(),
            'total_active' => Referral::where('status', 1)->count(),
            'avg_percent' => Referral::avg('percent'),
            'total_people' => Referral::sum('people')
        ];
        return view('backend.refer.refer', compact('page_title', 'trans', 'analytics'));
    }

    public function refStore(Request $request)
    {
        $this->validate($request, [
            'level*' => 'required|integer|min:1',
            'percent*' => 'required|numeric',
            'people*' => 'required|numeric',
        ]);

        Referral::truncate();

        for ($a = 0; $a < count($request->level); $a++) {
            Referral::create([
                'level' => $request->level[$a],
                'percent' => $request->percent[$a],
                'people' => $request->people[$a],
                'status' => 1,
            ]);
        }

        return back()->with('message', 'Create Successfully');
    }

    public function refUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'level' => 'required|integer|min:1',
            'percent' => 'required|numeric',
            'people' => 'required|numeric',
        ]);

        $referral = Referral::findOrFail($id);
        $referral->update([
            'level' => $request->level,
            'percent' => $request->percent,
            'people' => $request->people
        ]);

        return back()->with('message', 'Updated Successfully');
    }

    public function refDelete($id)
    {
        $referral = Referral::findOrFail($id);
        $referral->delete();

        return back()->with('message', 'Deleted Successfully');
    }

    public function refAnalytics()
    {
        $page_title = 'Referral Analytics';
        $analytics = [
            'total_referrals' => Referral::count(),
            'total_active' => Referral::where('status', 1)->count(),
            'avg_percent' => Referral::avg('percent'),
            'total_people' => Referral::sum('people'),
            'level_wise_data' => Referral::selectRaw('level, COUNT(*) as count, AVG(percent) as avg_percent, SUM(people) as total_people')
                ->groupBy('level')
                ->get(),
            'monthly_stats' => Referral::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get()
        ];
        return view('backend.refer.analytics', compact('page_title', 'analytics'));
    }}
