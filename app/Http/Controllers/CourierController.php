<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Courier;
use App\Models\User;
use App\Models\BusinessSetting;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;

class CourierController extends Controller
{
    public function __construct()
    {
        // $this->middleware('user', ['only' => ['index']]);
        $this->middleware(['permission:view_all_courier'])->only('index');
        $this->middleware(['permission:view_courier_profile'])->only('profile_modal');
        $this->middleware(['permission:login_as_courier'])->only('login');
        $this->middleware(['permission:pay_to_courier'])->only('payment_modal');
        $this->middleware(['permission:edit_courier'])->only('edit');
        $this->middleware(['permission:delete_courier'])->only('destroy');
        $this->middleware(['permission:ban_courier'])->only('ban');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $approved = null;
        $couriers = Courier::whereIn('user_id', function ($query) {
         $query->select('id')
         ->from(with(new User)->getTable())
         ->where('user_type', 'courier');
     })->latest();

        if ($request->has('search')) {
            $sort_search = $request->search;
            $user_ids = User::where('user_type', 'courier')->where(function ($user) use ($sort_search) {
                $user->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%' . $sort_search . '%')->orWhere('phone', 'like', '%' . $sort_search . '%');
            })->pluck('id')->toArray();
            $couriers = $couriers->where(function ($couriers) use ($user_ids) {
                $couriers->whereIn('user_id', $user_ids);
            });
        }
        if ($request->approved_status != null) {
            $approved = $request->approved_status;
            $couriers = $couriers->where('verification_status', $approved);
        }
        $couriers = $couriers->paginate(15);
        return view('backend.courier.index', compact('couriers', 'sort_search', 'approved'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            if((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'seller')) {
                flash(translate('Admin, Seller and Customer can not be a courier'))->error();
                return back();
            } if(Auth::user()->user_type == 'courier'){
                flash(translate('This user already a courier'))->error();
                return back();
            }

        } else {
            return view('frontend.courier_form');
        }
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users|max:255',
            'password'  => 'required|string|min:6|confirmed',
            // 'shop_name' => 'required|max:255',
            // 'address'   => 'required',
        ]);

        $user = null;
        if (!Auth::check()) {
            if (User::where('email', $request->email)->first() != null) {
                flash(translate('Email already exists!'))->error();
                return back();
            }
            if ($request->password == $request->password_confirmation) {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->user_type = "courier";
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                flash(translate('Sorry! Password did not match.'))->error();
                return back();
            }
        } else {
            $user = Auth::user();
            if ($user->customer != null) {
                $user->customer->delete();
            }
            $user->user_type = "courier";
            $user->save();
        }

        if (Courier::where('user_id', $user->id)->first() == null) {
            $courier = new Courier;
            $courier->user_id = $user->id;
            $courier->name = $request->name;
            $courier->address = $request->address;
            $courier->slug = preg_replace('/\s+/', '-', str_replace("/"," ", $request->name));

            if ($courier->save()) {
                auth()->login($user, false);
                if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                    $user->email_verified_at = date('Y-m-d H:m:s');
                    $user->save();
                } else {
                    $user->notify(new EmailVerificationNotification());
                }

                flash(translate('Your Courier account has been created successfully!'))->success();
                return redirect()->route('courier.dashboard');
            } else {
                $user->user_type == 'courier';
                $user->save();
            }
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function update(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);
        $user = $courier->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            if ($courier->save()) {
                flash(translate('Courier has been updated successfully'))->success();
                return redirect()->route('couriers.index');
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $courier = Courier::findOrFail(decrypt($id));
        return view('backend.courier.edit', compact('courier'));
    }

    public function destroy($id)
    {
        $courier = Courier::findOrFail($id);
        // SellerAccountStatusBanUnbendDeleteNotification($courier->user_id,'deleted');

        User::destroy($courier->user->id);

        if (Courier::destroy($id)) {
            flash(translate('Courier has been deleted successfully'))->success();
            return redirect()->route('couriers.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_courier_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $courier_id) {
                $this->destroy($courier_id);
            }
        }

        return 1;
    }

    public function approve_courier($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->verification_status = 1;
        if ($courier->save()) {
            Cache::forget('verified_sellers_id');
            flash(translate('Courier has been approved successfully'))->success();
            return redirect()->route('couriers.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function reject_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 0;
        $shop->verification_info = null;
        if ($shop->save()) {
            Cache::forget('verified_sellers_id');
            flash(translate('Seller verification request has been rejected successfully'))->success();
            return redirect()->route('sellers.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }
    public function login($id)
    {
        $courier = Courier::findOrFail(decrypt($id));
        $user  = $courier->user;
        auth()->login($user, true);

        return redirect()->route('courier.dashboard');
    }
    public function payment_modal(Request $request)
    {
        $courier = Courier::findOrFail($request->id);
        return view('backend.courier.payment_modal', compact('courier'));
    }

    public function profile_modal(Request $request)
    {
        $courier = Courier::findOrFail($request->id);
        return view('backend.courier.profile_modal', compact('courier'));
    }


    public function updateApproved(Request $request)
    {
        $courier = Courier::findOrFail($request->id);
        $courier->verification_status = $request->status;
        if ($courier->save()) {
            SellerAccountStatusNotification($courier->user_id,$request->status);
            // Cache::forget('verified_sellers_id');
            return 1;
        }
        return 0;
    }
    public function ban($id)
    {
        $courier = Courier::findOrFail($id);

        if ($courier->user->banned == 1) {
            $courier->user->banned = 0;
            $status ='unbanned';
            flash(translate('Courier has been unbanned successfully'))->success();
        } else {
            $courier->user->banned = 1;
            $status ='banned';
            flash(translate('Courier has been banned successfully'))->success();
        }

        $courier->user->save();
        SellerAccountStatusBanUnbendDeleteNotification($courier->user_id,$status);
        return back();
    }
}
