<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    protected $columns = ['action', 'code', 'name', 'pic', 'address', 'phone_no', 'remarks'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.supplier.supplier-data');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            Log::error($request->draw);
            $supplier = Supplier::query();

            // gbs sort by action, gnti jd code
            $orderCol = $request->input('order.0.column') == 0 ? 'code' : $this->columns[$request->input('order.0.column')];
            // dafult nya asc, gnti jd desc
            $orderDir = $request->draw == 1 ? 'desc' : $request->input('order.0.dir');

            return DataTables::eloquent($supplier)
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-between">
                        <a href="' . route('supplier.edit', $data->id) . '">
                            <i class="ti ti-edit ti-sm text-warning me-2"></i>
                        </a>
                        <a href="' . route('supplier.delete', $data->id) . '" onclick="confirmDelete(event)">
                            <i class="ti ti-trash ti-sm text-danger"></i>
                        </a>
                    </div>';
                })
                ->addColumn('code', function ($data) {
                    return $data->code;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('pic', function ($data) {
                    return $data->pic;
                })
                ->addColumn('phone_no', function ($data) {
                    return $data->phone_no ?? '-';
                })
                ->addColumn('address', function ($data) {
                    return $data->address ?? '-';
                })
                ->addColumn('remarks', function ($data) {
                    return $data->remarks ?? '-';
                })
                ->rawColumns(['action'])
                ->order(function ($query) use ($orderCol, $orderDir) {
                    $query->orderBy($orderCol, $orderDir); // Order by the specified column
                })
                ->make(true);
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.pages.supplier.supplier-detail');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                $supplier = Supplier::create([
                    'name' => $data['name'],
                    'pic' => $data['pic'],
                    'address' => $data['address'],
                    'phone_no' => $data['phoneNo'],
                    'remarks' => $data['remarks'],
                ]);

                $supplier->createdBy()->associate(Auth::user());
                $supplier->updatedBy()->associate(Auth::user());

                $supplier->saveOrFail();

                if (!$supplier) {
                    abort(500);
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal menambah Supplier: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal disimpan: ' . $e);
        }

        return to_route('master-supplier')->with('success', 'Data Berhasil Disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $edit = Supplier::findOrFail($id);
        return view('content.pages.supplier.supplier-detail', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, int $id)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, $id) {
                $supplier = Supplier::where('id', $id)?->lockForUpdate();
                if (!$supplier) abort(404);

                $supplier->update([
                    'name' => $data['name'],
                    'pic' => $data['pic'],
                    'address' => $data['address'],
                    'phone_no' => $data['phoneNo'],
                    'remarks' => $data['remarks'],
                    'updated_by' => Auth::id(),
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Menambah Supplier: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal Disimpan ' . $e);
        }

        return to_route('master-supplier')->with('success', 'Data Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $supplier = Supplier::findOrFail($id);
                $deleted = $supplier->delete();

                if (!$deleted) abort(500);
            });
        } catch (\Exception $e) {
            Log::error('Data Gagal Dihapus: ' . $e->getMessage());
        }
    }
}
