<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
class OrderExportController extends Controller
{
	public function excel ()
	{
		//return 'here';
		return Excel::download(new OrdersExport, 'orders.xlsx');
	}
}
