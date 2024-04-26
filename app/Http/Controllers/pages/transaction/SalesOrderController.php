<?php

namespace App\Http\Controllers\pages\transaction;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStockDetail;
use App\Models\SalesOrder;
use App\Models\SalesOrderProductDetail;
use App\Models\SalesOrderServiceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SalesOrderController extends Controller
{
    protected $columns = [
        'action',
        'so_code',
        'sales_date',
        'customer',
        'sub_total',
        'discount',
        'grand_total',
        'paid_amount',
        'status',
        'created_by', // kasir
        'remarks'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.transaction.sales-order.sales-order-data');
    }

    public function browseSo(Request $request)
    {
        if ($request->ajax()) {
            $so = SalesOrder::query();

            $orderCol = $this->columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');

            return DataTables::eloquent($so)
                // add column
                ->addColumn('action', function ($data) {
                    $hideBayar = $data->status == 'Lunas' ? 'hidden' : '';
                    $hideCancel = Auth::user()->role != 'Admin' ? 'hidden' : '';

                    return '
                        <div class="btn-group d-flex justify-content-center">
                            <a href="javascript:void(0)" class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu" container="body">
                                <li>
                                    <a class="dropdown-item" href="' . route('transaction-sales-order.view', $data->id) . '">
                                        <i class="ti ti-eye ti-sm text-primary me-2"></i> Lihat
                                    </a>
                                </li>
                                <li ' . $hideBayar . '>
                                    <a class="payment-modal dropdown-item" href="javascript:void(0)"
                                        data-id=' . $data->id . '
                                        data-so-code=' . $data->so_code . '
                                        data-grand-total=' . $data->grand_total . '
                                        data-paid-amount=' . $data->paid_amount . '
                                    >
                                        <i class="ti ti-cash-banknote ti-sm text-warning me-2"></i> Bayar
                                    </a>
                                </li>
                                <li ' . $hideCancel . '>
                                    <a class="cancel-so dropdown-item" href="' . route('transaction-sales-order.cancel', $data->id) . '">
                                        <i class="ti ti-circle-off ti-sm text-danger me-2"></i> Cancel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    ';
                })
                ->addColumn('so_code', function ($data) {
                    return $data->so_code;
                })
                ->addColumn('sales_date', function ($data) {
                    return $data->sales_date;
                })
                ->addColumn('customer', function ($data) {
                    return $data->customerId->name;
                })
                ->addColumn('sub_total', function ($data) {
                    return $data->sub_total;
                })
                ->addColumn('discount', function ($data) {
                    return $data->discount;
                })
                ->addColumn('grand_total', function ($data) {
                    return $data->grand_total;
                })
                ->addColumn('paid_amount', function ($data) {
                    return $data->paid_amount;
                })
                ->addColumn('status', function ($data) {
                    return $data->status;
                })
                ->addColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->addColumn('remarks', function ($data) {
                    return $data->remarks ? $data->remarks : '-';
                })

                // handle filter
                ->filterColumn('so_code', function ($query, $keyword) {
                    $query->where('so_code', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('customer', function ($query, $keyword) {
                    if ($keyword != 'null') {
                        $query->where('customer_id', 'like',  $keyword);
                    }
                })
                ->filterColumn('sales_date', function ($query, $keyword) {
                    list($startDate, $endDate) = explode(',', $keyword);

                    list($startDay, $startMonth, $startYear) = explode('-', $startDate);
                    $startDate = "$startYear-$startMonth-$startDay";

                    list($endDay, $endMonth, $endYear) = explode('-', $endDate);
                    $endDate = "$endYear-$endMonth-$endDay";

                    $query
                        ->whereDate('sales_date', '>=', $startDate)
                        ->whereDate('sales_date', '<=', $endDate);
                })
                ->filterColumn('status', function ($query, $keyword) {
                    if ($keyword != 'Semua') $query->where('status', 'like', $keyword);
                })

                ->rawColumns(['action'])

                //handle sorting
                ->order(function ($query) use ($orderCol, $orderDir) {
                    $query->orderBy($orderCol, $orderDir); // Order by the specified column
                })
                ->make(true);
        }
    }

    public function getTotalSales(Request $request)
    {
        $period = $request->period;

        switch ($period) {
            case 'day':
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            default:
                return response()->json(['error' => 'Invalid Period'], 400);
        }

        $totalSales = SalesOrder::whereBetween('created_at', [$start, $end])->sum('grand_total');
        return response()->json(['total_sales' => $totalSales]);
    }

    public function browseIncompletePayment(Request $request)
    {
        if ($request->ajax()) {
            $so = SalesOrder::where('status', 'Belum Lunas');

            return DataTables::eloquent($so)
                ->addColumn('so_code', function ($data) {
                    return '
                    <a class="text-danger" href="' . route('transaction-sales-order.view', $data->id) . '">
                        ' . $data->so_code . '
                    </a>
                    ';
                })
                ->addColumn('customer', function ($data) {
                    return $data->customerId->name;
                })
                ->addColumn('grand_total', function ($data) {
                    return $data->grand_total;
                })
                ->addColumn('paid_amount', function ($data) {
                    return $data->paid_amount;
                })
                ->rawColumns(['so_code'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cashierName = Auth::user()->name;

        $lastSoId = SalesOrder::latest('id')->first();
        if ($lastSoId) $lastSoId = $lastSoId->id;
        else $lastSoId = 0;

        $soCode = 'SO' . str_pad($lastSoId + 1, 7, '0', STR_PAD_LEFT);

        return view('content.pages.transaction.sales-order.sales-order-detail', compact('cashierName', 'soCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::error($request->json()->all());

        $data = $request->json()->all();

        try {
            DB::transaction(function () use ($data) {
                $status = $data['payment_type'] == 'DP' ? 'Belum Lunas' : 'Lunas';

                $so = SalesOrder::create([
                    'sales_date' => $data['sales_date'],
                    'customer_id' => $data['customer_id'],
                    'payment_type' => $data['payment_type'],
                    'sub_total' => $data['sub_total'],
                    'grand_total' => $data['grand_total'],
                    'discount' => $data['discount'],
                    'paid_amount' => $data['paid_amount'],
                    'remarks' => $data['remarks'],
                    'status' => $status,
                ]);
                $so->createdBy()->associate(Auth::user());
                $so->updatedBy()->associate(Auth::user());
                $so->saveOrFail();

                foreach ($data['so_product_detail'] as $detail) {
                    $sod = SalesOrderProductDetail::create([
                        'so_id' => $so->id,
                        'product_id' => $detail['product_id'],
                        'ori_selling_price' => $detail['ori_selling_price'],
                        'selling_price' => $detail['selling_price'],
                        'quantity' => $detail['quantity'],
                        'total_price' => $detail['total_price'],
                    ]);

                    $sod->createdBy()->associate(Auth::user());
                    $sod->updatedBy()->associate(Auth::user());
                    $sod->saveOrFail();

                    $product = Product::where('id', $detail['product_id'])?->lockForUpdate()->first();
                    $product->update([
                        'stock' => $product['stock'] - $detail['quantity'],
                    ]);

                    // stock reduction
                    $productStock = ProductStockDetail::select('id', 'quantity')
                        ->where('product_id', $detail['product_id'])
                        ->where('quantity', '>', 0)
                        ->get();

                    $soldQuantity = $detail['quantity'];
                    foreach ($productStock as $stockDetail) {
                        if ($stockDetail->quantity >= $soldQuantity) {
                            Log::error('sikat tipis');
                            Log::error($soldQuantity);
                            break;
                        } else {
                            Log::error('sikat abis, lanjut lg');
                            $soldQuantity = $soldQuantity - $stockDetail->quantity;
                            Log::error($soldQuantity);
                        }
                    }
                    Log::error($productStock);
                }
                throw ('sa');

                foreach ($data['so_service_detail'] as $detail) {
                    $sod = SalesOrderServiceDetail::create([
                        'so_id' => $so->id,
                        'service_name' => $detail['service_name'],
                        'selling_price' => $detail['selling_price'],
                        'quantity' => $detail['quantity'],
                        'total_price' => $detail['total_price'],
                    ]);

                    $sod->createdBy()->associate(Auth::user());
                    $sod->updatedBy()->associate(Auth::user());
                    $sod->saveOrFail();
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Transaksi Penjualan: ' . $e->getMessage());
            return response()->json(['error', 'Gagal menyimpan Transaksi Penjualan: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaksi Penjualan Berhasil Disimpan'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function view(int $id)
    {
        $view = SalesOrder::with('customerId')
            ->with('createdBy')
            ->with('soProductDetail.productId')
            ->with('soServiceDetail')
            ->findOrFail($id);
        return view('content.pages.transaction.sales-order.sales-order-detail', compact('view'));
    }

    public function updatePaidAmount(Request $request, int $id)
    {
        $data = $request;

        try {
            DB::transaction(function () use ($data, $id) {
                $so = SalesOrder::where('id', $id)?->lockForUpdate()->first();

                $so->update([
                    'paid_amount' => $so['paid_amount'] + $data['paid_amount']
                ]);

                if ($so['paid_amount'] === $so['grand_total']) {
                    $so->update([
                        'status' => 'Lunas'
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal Mengupdate Pembayaran: ' . $e->getMessage());
            return response()->json(['error', 'Gagal Mengupdate Pembayaran: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Pembayaran Berhasil Diupdate'], 200);
    }

    public function cancel(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $so = SalesOrder::where('id', $id)?->lockForUpdate()->first();
                $soProductDetail = $so->soProductDetail;
                Log::error($soProductDetail);
                foreach ($soProductDetail as $detail) {
                    $product = Product::where('id', $detail['product_id'])?->lockForUpdate()->first();
                    $product->update([
                        'stock' => $product['stock'] + $detail['quantity']
                    ]);
                }

                $so->update([
                    'status' => 'Batal'
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Membatalkan Transaksi: ' . $e->getMessage());
            return response()->json(['error', 'Gagal Membatalkan Transaksi: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaksi Berhasil Dibatalkan'], 200);
    }
}
