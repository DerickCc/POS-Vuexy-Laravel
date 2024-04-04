<?php

namespace App\Http\Controllers\pages\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    protected $columns = ['action', 'code', 'name', 'license_plate', 'phone_no', 'address'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.master.customer.customer-data');
    }

    public function browseCustomer(Request $request)
    {
        if ($request->ajax()) {
            $customer = Customer::query();

            $orderCol = $this->columns[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');

            return DataTables::eloquent($customer)
                // add column
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-between">
                        <a href="' . route('master-customer.edit', $data->id) . '">
                            <i class="ti ti-edit ti-sm text-warning me-2"></i>
                        </a>
                        <a href="' . route('master-customer.delete', $data->id) . '" onclick="confirmDelete(event, \'#customerDatatable\')">
                            <i class="ti ti-trash ti-sm text-danger"></i>
                        </a>
                    </div>
                    ';
                })
                ->addColumn('code', function ($data) {
                    return $data->code;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('license_plate', function ($data) {
                    return $data->license_plate;
                })
                ->addColumn('phone_no', function ($data) {
                    return $data->phone_no ?? '-';
                })
                ->addColumn('address', function ($data) {
                    return $data->address ?? '-';
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
                ->filterColumn('license_plate', function ($query, $keyword) {
                    $query->where('license_plate', 'like', '%' . $keyword . '%');
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
        }
    }

    public function getCustomerList()
    {
        $words = explode(' ', request('q'));

        $customerList = Customer::query();

        foreach ($words as $word) {
            $customerList->where(function ($query) use ($word) {
                $query->where('name', 'like', '%' . $word . '%');
            });
        };

        $customerList = $customerList->get();

        return response()->json($customerList);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.pages.master.customer.customer-detail');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                $customer = Customer::create([
                    'name' => $data['name'],
                    'license_plate' => $data['license_plate'],
                    'address' => $data['address'],
                    'phone_no' => $data['phone_no'],
                ]);

                $customer->createdBy()->associate(Auth::user());
                $customer->updatedBy()->associate(Auth::user());

                $customer->saveOrFail();

                if (!$customer) abort(500);
            });
        }
        catch (\Exception $e) {
            Log::error('Gagal menambah Customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal disimpan: ' . $e);
        }

        return to_route('master-customer.index')->with('success', 'Data Berhasil Disimpan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $edit = Customer::findOrFail($id);
        return view('content.pages.master.customer.customer-detail', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, $id) {
                $customer = Customer::where('id', $id)?->lockForUpdate()->first();
                if (!$customer) abort(404);

                $customer->update([
                    'name' => $data['name'],
                    'license_plate' => $data['license_plate'],
                    'address' => $data['address'],
                    'phone_no' => $data['phone_no'],
                    'updated_by' => Auth::id(),
                ]);
            });
        }
        catch (\Exception $e) {
            Log::error('Gagal Mengupdate Customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal Diupdate ' . $e);
        }

        return to_route('master-customer.index')->with('success', 'Data Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        try {
            DB::transaction(function () use ($id){
                $customer = Customer::where('id', $id)?->lockForUpdate()->first();
                $deleted = $customer->delete();

                if (!$deleted) abort(500);
            });
        }
        catch (\Exception $e) {
            Log::error('Gagal Menghapus Customer: ' . $e->getMessage());
        }
    }
}
