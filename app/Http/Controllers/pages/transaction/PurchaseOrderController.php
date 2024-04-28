<?php

namespace App\Http\Controllers\pages\transaction;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStockDetail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
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
        'grand_total',
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
            $po = PurchaseOrder::query();

            $orderCol = $this->columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');

            return DataTables::eloquent($po)
                // add column
                ->addColumn('action', function ($data) {
                    $hidden = $data->status == 'Selesai' ? 'hidden' : '';
                    return '
                        <div class="btn-group d-flex justify-content-center">
                            <a href="javascript:void(0)" class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu" container="body">
                                <li>
                                    <a class="dropdown-item" href="' . route('transaction-purchase-order.view', $data->id) . '">
                                        <i class="ti ti-eye ti-sm text-primary me-2"></i> Lihat
                                    </a>
                                </li>
                                <li ' . $hidden . '>
                                    <a class="dropdown-item" href="' . route('transaction-purchase-order.edit', $data->id) . '">
                                        <i class="ti ti-edit ti-sm text-warning me-2"></i> Edit
                                    </a>
                                </li>
                                <li ' . $hidden . '>
                                    <a class="finish-po dropdown-item" href="' . route('transaction-purchase-order.finish', $data->id) . '">
                                        <i class="ti ti-checks ti-sm text-info me-2"></i> Selesai
                                    </a>
                                </li>
                                <li ' . $hidden . '>
                                    <a class="dropdown-item" href="' . route('transaction-purchase-order.delete', $data->id) . '" onclick="confirmDelete(event, \'#poDatatable\')">
                                        <i class="ti ti-trash ti-sm text-danger me-2"></i> Delete
                                    </a>
                                </li>
                            </ul>
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
                    return $data->supplierId->name;
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
                    return $data->remarks ? $data->remarks : '-';
                })

                // handle filter
                ->filterColumn('po_code', function ($query, $keyword) {
                    $query->where('po_code', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('supplier', function ($query, $keyword) {
                    if ($keyword != 'null') {
                        $query->where('supplier_id', '=',  $keyword);
                    }
                })
                ->filterColumn('purchase_date', function ($query, $keyword) {
                    list($startDate, $endDate) = explode(',', $keyword);

                    list($startDay, $startMonth, $startYear) = explode('-', $startDate);
                    $startDate = "$startYear-$startMonth-$startDay";

                    list($endDay, $endMonth, $endYear) = explode('-', $endDate);
                    $endDate = "$endYear-$endMonth-$endDay";

                    $query
                        ->whereDate('purchase_date', '>=', $startDate)
                        ->whereDate('purchase_date', '<=', $endDate);
                })
                ->filterColumn('status', function ($query, $keyword) {
                    if ($keyword != 'Semua') $query->where('status', '=', $keyword);
                })

                ->rawColumns(['action'])

                //handle sorting
                ->order(function ($query) use ($orderCol, $orderDir) {
                    $query->orderBy($orderCol, $orderDir); // Order by the specified column
                })
                ->make(true);
        }
    }

    public function getTotalOnGoingPo()
    {
        $totalOnGoingPo = PurchaseOrder::where('status', 'Dalam Proses')->count();
        return response()->json(['total_on_going_po' => $totalOnGoingPo]);
    }

    public function export(Request $request)
    {
        $filter = $request->all();

        $exportData = PurchaseOrder::select('id', 'po_code', 'purchase_date', 'supplier_id', 'total_item', 'grand_total', 'status')
            ->with(['poDetail' => function ($query) {
                $query->select('id', 'po_id', 'product_id', 'purchase_price', 'quantity', 'total_price');
            }]);

        if (isset($filter['po_code'])) {
            $exportData->where('po_code', 'LIKE', '%' . $filter['po_code'] . '%');
        }

        if (isset($filter['supplier_id'])) {
            $exportData->where('supplier_id', '=', $filter['supplier_id']);
        }

        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            list($startDay, $startMonth, $startYear) = explode('-', $filter['start_date']);
            $startDate = "$startYear-$startMonth-$startDay";

            list($endDay, $endMonth, $endYear) = explode('-', $filter['end_date']);
            $endDate = "$endYear-$endMonth-$endDay";

            $exportData
                ->whereDate('purchase_date', '>=', $startDate)
                ->whereDate('purchase_date', '<=', $endDate);
        }

        if ($filter['status'] != 'Semua') {
            $exportData->where('status', '=',  $filter['status']);
        }

        $exportData = $exportData->get();

        $exportData->each(function ($po) {
            $po->supplier_name = $po->supplierId->name;
            unset($po->supplierId);
            unset($po->id);
            unset($po->supplier_id);

            $po->poDetail->each(function ($detail) {
                $detail->product_name = $detail->productId->name;
                $detail->product_uom = $detail->productId->uom;
                unset($detail->productId);
                unset($detail->id);
                unset($detail->po_id);
                unset($detail->product_id);

            });
        });

        return $exportData;
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
                $po = PurchaseOrder::create([
                    'purchase_date' => $data['purchase_date'],
                    'supplier_id' => $data['supplier_id'],
                    'total_item' => count($data['po_detail']),
                    'grand_total' => $data['grand_total'],
                    'remarks' => $data['remarks'],
                ]);

                $po->createdBy()->associate(Auth::user());
                $po->updatedBy()->associate(Auth::user());

                $po->saveOrFail();
                if (!$po) abort(500);

                foreach ($data['po_detail'] as $detail) {
                    $pod = PurchaseOrderDetail::create([
                        'po_id' => $po->id,
                        'product_id' => $detail['product_id'],
                        'purchase_price' => $detail['purchase_price'],
                        'quantity' => $detail['quantity'],
                        'total_price' => $detail['total_price'],
                    ]);

                    $pod->createdBy()->associate(Auth::user());
                    $pod->updatedBy()->associate(Auth::user());

                    $pod->saveOrFail();
                    if (!$pod) abort(500);
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Transaksi Pembelian: ' . $e->getMessage());
            return response()->json(['error', 'Gagal menyimpan Transaksi Pembelian: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaksi Pembelian Berhasil Disimpan'], 200);
    }

    public function view(int $id)
    {
        $selectColumns = ['id', 'po_code', 'purchase_date', 'supplier_id', 'remarks', 'grand_total'];
        $view = PurchaseOrder::select($selectColumns)->with('supplierId')->with('poDetail.productId')->findOrFail($id);
        return view('content.pages.transaction.purchase-order.purchase-order-detail', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $selectColumns = ['id', 'po_code', 'purchase_date', 'supplier_id', 'remarks', 'grand_total'];
        $edit = PurchaseOrder::select($selectColumns)->with('supplierId')->with('poDetail.productId')->findOrFail($id);
        return view('content.pages.transaction.purchase-order.purchase-order-detail', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->json()->all();

        try {
            DB::transaction(function () use ($data, $id) {
                $po = PurchaseOrder::where('id', $id)?->lockForUpdate()->first();
                if (!$po) abort(404);

                $po->update([
                    'supplier_id' => $data['supplier_id'],
                    'total_item' => count($data['po_detail']),
                    'grand_total' => $data['grand_total'],
                    'remarks' => $data['remarks'],
                    'updated_by' => Auth::id(),
                ]);

                foreach ($data['po_detail'] as $detail) {
                    // create
                    if ($detail['id'] == 0) {
                        $newPod = PurchaseOrderDetail::create([
                            'po_id' => $id,
                            'product_id' => $detail['product_id'],
                            'purchase_price' => $detail['purchase_price'],
                            'quantity' => $detail['quantity'],
                            'total_price' => $detail['total_price'],
                        ]);

                        $newPod->createdBy()->associate(Auth::user());
                        $newPod->updatedBy()->associate(Auth::user());

                        $newPod->saveOrFail();
                    }
                    // update
                    else {
                        $pod = PurchaseOrderDetail::find($detail['id']);

                        $pod->update([
                            'product_id' => $detail['product_id'],
                            'purchase_price' => $detail['purchase_price'],
                            'quantity' => $detail['quantity'],
                            'total_price' => $detail['total_price'],
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }

                // delete
                foreach ($data['deleted_detail'] as $podId) {
                    $pod = PurchaseOrderDetail::where('id', $podId)?->lockForUpdate()->first();
                    $pod->delete();
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal Mengupdate Transaksi Pembelian: ' . $e->getMessage());
            return response()->json(['error', 'Gagal Mengupdate Transaksi Pembelian: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaksi Pembelian Berhasil Diupdate'], 200);
    }

    public function finish(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = PurchaseOrder::where('id', $id)?->lockForUpdate()->first();

                // poDetail from PurchaseOrder relationship
                foreach ($po->poDetail as $detail) {
                    $product = Product::where('id', $detail['product_id'])?->lockForUpdate()->first();
                    $product->update([
                        'stock' => $product['stock'] + $detail['quantity']
                    ]);

                    ProductStockDetail::create([
                        'product_id' => $detail['product_id'],
                        'purchase_price' => $detail['purchase_price'],
                        'quantity' => $detail['quantity'],
                    ]);
                }

                $po->update([
                    'status' => 'Selesai'
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Menyelesaikan Transaksi Pembelian: ' . $e->getMessage());
            return response()->json(['error', 'Gagal Menyelesaikan Transaksi Pembelian: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaksi Pembelian Berhasil Diselesaikan'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $po = PurchaseOrder::where('id', $id)?->lockForUpdate()->first();
                $deleted = $po->delete();

                if (!$deleted) abort(500);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Menghapus Transaksi Pembelian: ' . $e->getMessage());
        }
    }
}
