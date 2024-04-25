<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        
        $today = Carbon::today();
        $so = SalesOrder::whereDate('created_at', $today)->get();
        $pod = PurchaseOrderDetail::whereDate('created_at', $today)->get();
        // Log::error($so);
        // Log::error($pod);
        return view('content.pages.dashboard');
    }
}
