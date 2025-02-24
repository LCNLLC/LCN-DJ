<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:view_all_customers'])->only('index');
        $this->middleware(['permission:create_customer'])->only(['create', 'store']);
        $this->middleware(['permission:edit_customer'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_customer'])->only('destroy');
        $this->middleware(['permission:login_as_customer'])->only('login');
        $this->middleware(['permission:ban_customer'])->only('ban');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $users = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%')->orWhere('phone', 'like', '%'.$sort_search.'%');
            });
        }
        $users = $users->paginate(15);
        return view('backend.customer.customers.index', compact('users', 'sort_search'));
    }

    public function create()
    {
        return view('backend.customer.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->user_type = 'customer';
        $user->email_verified_at = now();
        $user->referred_by = auth()->user()->id;
        $user->referral_code = unique_reference_link();
        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();

        flash(translate('Customer has been created successfully'))->success();
        return redirect()->route('customers.index');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.customer.customers.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|unique:users,phone,'.$user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if($request->password) {
            $request->validate([
                'password' => 'required|min:6'
            ]);
            $user->password = bcrypt($request->password);
        }

        $user->save();

        flash(translate('Customer has been updated successfully'))->success();
        return redirect()->route('customers.index');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->customer_products()->delete();
        $customer->customer()->delete();
        $customer->delete();

        flash(translate('Customer has been deleted successfully'))->success();
        return redirect()->route('customers.index');
    }

    public function bulk_customer_delete(Request $request) {
        if($request->id) {
            foreach ($request->id as $customer_id) {
                $customer = User::findOrFail($customer_id);
                $customer->customer_products()->delete();
                $customer->customer()->delete();
                $customer->delete();
            }
        }

        return 1;
    }

    public function login($id)
    {
        $user = User::findOrFail(decrypt($id));
        auth()->login($user, true);
        return redirect()->route('dashboard');
    }

    public function ban($id) {
        $user = User::findOrFail(decrypt($id));

        if($user->banned == 1) {
            $user->banned = 0;
            $account_status = 'UnBanned';
            $value = 0;
            flash(translate('Customer UnBanned Successfully'))->success();
        } else {
            $user->banned = 1;
            $account_status = 'Banned';
            $value = 1;
            flash(translate('Customer Banned Successfully'))->success();
        }

        $user->save();
        CustomerBanUnbendNotification($user->id, $account_status, $value);
        return back();
    }
}
