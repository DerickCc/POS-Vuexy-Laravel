<?php

namespace App\Http\Controllers\pages\transaction;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
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

    public function getData(Request $request)
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
                ->addColumn('total_item', function ($data) {
                    return $data->total_item;
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
        //
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
