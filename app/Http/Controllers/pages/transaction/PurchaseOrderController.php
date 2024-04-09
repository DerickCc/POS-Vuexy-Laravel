<?php

namespace App\Http\Controllers\pages\transaction;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    protected $columns = [
        'action',
        'po_code',
        'purchase_date',
        'supplier',
        'total_item',
        'total_price',
        'status',
        'remarks'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.transaction.purchase-order.purchase-order-data');
    }

    public function browsePo(Request $request)
    {
        if ($request->ajax()) {
            $customer = PurchaseOrder::query();

            $orderCol = $this->columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');

            return DataTables::eloquent($customer)
                // add column
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-between">
                        <a href="' . route('transaction-purchase-order.edit', $data->id) . '">
                            <i class="ti ti-edit ti-sm text-warning me-2"></i>
                        </a>
                        <a href="' . route('transaction-purchase-order.delete', $data->id) . '" onclick="confirmDelete(event, \'#purchaseOrderDatatable\')">
                            <i class="ti ti-trash ti-sm text-danger"></i>
                        </a>
                    </div>
                    ';
                })
                ->addColumn('po_code', function ($data) {
                    return $data->po_code;
                })
                ->addColumn('purchase_date', function ($data) {
                    return $data->purchase_date;
                })
                ->addColumn('supplier', function ($data) {
                    return 'supplier';
                })
                ->addColumn('grand_total', function ($data) {
                    return $data->grand_total;
                })
                ->addColumn('total_price', function ($data) {
                    return $data->total_price;
                })
                ->addColumn('status', function ($data) {
                    return $data->status;
                })
                ->addColumn('remarks', function ($data) {
                    return $data->remarks;
                })

                // handle filter
                // ->filterColumn('name', function ($query, $keyword) {
                //     $words = explode(' ', $keyword);

                //     $query->where(function ($query) use ($words) {
                //         foreach ($words as $word) {
                //             $query->where('name', 'like', '%' . $word . '%');
                //         }
                //     });
                // })
                // ->filterColumn('license_plate', function ($query, $keyword) {
                //     $query->where('license_plate', 'like', '%' . $keyword . '%');
                // })
                // ->filterColumn('phone_no', function ($query, $keyword) {
                //     $query->where('phone_no', 'like', '%' . $keyword . '%');
                // })
                ->rawColumns(['action'])

                //handle sorting
                ->order(function ($query) use ($orderCol, $orderDir) {
                    $query->orderBy($orderCol, $orderDir); // Order by the specified column
                })
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.pages.transaction.purchase-order.purchase-order-detail');
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
                $PO = PurchaseOrder::create([
                    'purchase_date' => $data['purchase_date'],
                    'supplier_id' => $data['supplier_id'],
                    'total_item' => count($data['po_detail']),
                    'grand_total' => $data['grand_total'],
                    'remarks' => $data['remarks'],
                ]);

                $PO->createdBy()->associate(Auth::user());
                $PO->updatedBy()->associate(Auth::user());

                $PO->saveOrFail();
                if (!$PO) abort(500);

                foreach ($data['po_detail'] as $detail) {
                    $POD = PurchaseOrderDetail::create([
                        'po_id' => $PO->id,
                        'product_id' => $detail['product_id'],
                        'purchase_price' => $detail['purchase_price'],
                        'quantity' => $detail['quantity'],
                        'total_price' => $detail['total_price'],
                    ]);

                    $POD->createdBy()->associate(Auth::user());
                    $POD->updatedBy()->associate(Auth::user());

                    $POD->saveOrFail();
                    if (!$POD) abort(500);
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Transaksi Pembelian: ' . $e->getMessage());
            return response()->json(['error', 'Gagal menyimpan Transaksi Pembelian: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaksi Pembelian Berhasil Disimpan'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        //
    }
}
