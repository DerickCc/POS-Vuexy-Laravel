<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $columns = [
        'action',
        'id',
        'photo',
        'name',
        'stock',
        'uom',
        'purchase_price',
        'selling_price',
        'remarks'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.inventory.product.product-data');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::query();

            $orderCol = $this->columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');

            return DataTables::eloquent($product)
                // add column
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-between">
                        <a href="' . route('inventory-product.edit', $data->id) . '">
                            <i class="ti ti-edit ti-sm text-warning me-2"></i>
                        </a>
                        <a href="' . route('inventory-product.delete', $data->id) . '" onclick="confirmDelete(event, \'#product-datatable\')">
                            <i class="ti ti-trash ti-sm text-danger"></i>
                        </a>
                    </div>
                    ';
                })
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
                ->addColumn('photo', function ($data) {
                    return $data->photo;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('stock', function ($data) {
                    return $data->stock;
                })
                ->addColumn('uom', function ($data) {
                    return $data->uom;
                })
                ->addColumn('purchase_price', function ($data) {
                    return $data->purchase_price;
                })
                ->addColumn('selling_price', function ($data) {
                    return $data->selling_price;
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
                ->filterColumn('uom', function ($query, $keyword) {
                    $query->where('uom', 'like', '%' . $keyword . '%');
                })
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
        return view('content.pages.inventory.product.product-detail');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        Log::error($request);
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                $product = Product::create([
                    'photo' => $data['photo'],
                    'name' => $data['name'],
                    'stock' => $data['stock'],
                    'uom' => $data['uom'],
                    'purchase_price' => $data['purchase_price'],
                    'selling_price' => $data['selling_price'],
                    'remarks' => $data['remarks'],
                ]);

                $product->createdBy()->associate(Auth::user());
                $product->updatedBy()->associate(Auth::user());

                $product->saveOrFail();

                if (!$product) abort(500);
            });
        }
        catch (\Exception $e) {
            Log::error('Gagal menambah Product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal disimpan: ' . $e);
        }

        return to_route('inventory-product.index')->with('success', 'Data Berhasil Disimpan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $edit = Product::findOrFail($id);
        return view('content.pages.inventory.product.product-detail', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, int $id)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, $id) {
                $product = Product::where('id', $id)?->lockForUpdate();
                if (!$product) abort(404);

                $product->update([
                    'photo' => $data['photo'],
                    'name' => $data['name'],
                    'stock' => $data['stock'],
                    'uom' => $data['uom'],
                    'purchase_price' => $data['purchase_price'],
                    'selling_price' => $data['selling_price'],
                    'remarks' => $data['remarks'],
                ]);
            });
        }
        catch (\Exception $e) {
            Log::error('Gagal Mengupdate Product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal Diupdate ' . $e);
        }

        return to_route('inventory-product.index')->with('success', 'Data Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            DB::transaction(function () use ($id){
                $product = Product::findOrFail($id);
                $deleted = $product->delete();

                if (!$deleted) abort(500);
            });
        }
        catch (\Exception $e) {
            Log::error('Gagal Menghapus Product: ' . $e->getMessage());
        }
    }
}
