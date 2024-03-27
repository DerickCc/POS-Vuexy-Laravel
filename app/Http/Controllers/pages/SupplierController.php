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
    protected $columns = ['action', 'code', 'name', 'pic', 'phone_no', 'address', 'remarks'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.master.supplier.supplier-data');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $supplier = Supplier::query();

            //can't sort by action, so set it to code
            $orderCol = $this->columns[$request->input('order.0.column')];
            // set default sorting to desc
            $orderDir = $request->input('order.0.dir');

            return DataTables::eloquent($supplier)
                // add column
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-between">
                        <a href="' . route('master-supplier.edit', $data->id) . '">
                            <i class="ti ti-edit ti-sm text-warning me-2"></i>
                        </a>
                        <a href="' . route('master-supplier.delete', $data->id) . '" onclick="confirmDelete(event, \'#supplierDatatable\')">
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

                // handle filter
                ->filterColumn('name', function ($query, $keyword) {
                    $words = explode(' ', $keyword);

                    $query->where(function ($query) use ($words) {
                        foreach ($words as $word) {
                            $query->where('name', 'like', '%' . $word . '%');
                        }
                    });
                })
                ->filterColumn('pic', function ($query, $keyword) {
                    $words = explode(' ', $keyword);

                    $query->where(function ($query) use ($words) {
                        foreach ($words as $word) {
                            $query->where('pic', 'like', '%' . $word . '%');
                        }
                    });
                })
                ->filterColumn('phone_no', function ($query, $keyword) {
                    $query->where('phone_no', 'like', '%' . $keyword . '%');
                })
                ->rawColumns(['action'])

                //handle sorting
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
        return view('content.pages.master.supplier.supplier-detail');
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
                    'phone_no' => $data['phone_no'],
                    'remarks' => $data['remarks'],
                ]);

                $supplier->createdBy()->associate(Auth::user());
                $supplier->updatedBy()->associate(Auth::user());

                $supplier->saveOrFail();

                if (!$supplier) abort(500);
            });
        } catch (\Exception $e) {
            Log::error('Gagal menambah Supplier: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal disimpan: ' . $e);
        }

        return to_route('master-supplier.index')->with('success', 'Data Berhasil Disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $edit = Supplier::findOrFail($id);
        return view('content.pages.master.supplier.supplier-detail', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, int $id)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, $id) {
                $supplier = Supplier::where('id', $id)?->lockForUpdate()->first();
                if (!$supplier) abort(404);

                $supplier->update([
                    'name' => $data['name'],
                    'pic' => $data['pic'],
                    'address' => $data['address'],
                    'phone_no' => $data['phone_no'],
                    'remarks' => $data['remarks'],
                    'updated_by' => Auth::id(),
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Mengupdate Supplier: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal Diupdate ' . $e);
        }

        return to_route('master-supplier.index')->with('success', 'Data Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $supplier = Supplier::where('id', $id)?->lockForUpdate()->first();
                $deleted = $supplier->delete();

                if (!$deleted) abort(500);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Menghapus Supplier: ' . $e->getMessage());
        }
    }
}
