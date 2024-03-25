<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $columns = ['action', 'id', 'username', 'name', 'role', 'account_status'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.settings.user.user-data');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $customer = User::query();

            return DataTables::eloquent($customer)
                // add column
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-between">
                        <a href="' . route('settings-user.edit', $data->id) . '">
                            <i class="ti ti-edit ti-sm text-warning me-2"></i>
                        </a>
                        <a href="' . route('settings-user.change-account-status', $data->id) . '
                            "onclick="confirmChangeStatus(event, \'#user-datatable\')">
                            <i class="ti ti-refresh ti-sm text-info"></i>
                        </a>
                    </div>
                    ';
                })
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
                ->addColumn('username', function ($data) {
                    return $data->username;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('role', function ($data) {
                    return $data->role;
                })
                ->addColumn('account_status', function ($data) {
                    return $data->account_status;
                })

                // handle filter
                ->filterColumn('username', function ($query, $keyword) {
                    $query->where('username', 'like', '%' . $keyword . '%');
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.pages.settings.user.user-detail');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                $user = User::create([
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'password' => bcrypt($data['password']),
                    'role' => $data['role'],
                    'account_status' => $data['account_status'],
                ]);

                if (!$user) abort(500);
            });
        } catch (\Exception $e) {
            Log::error($data);
            Log::error('Gagal Menambah User: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal disimpan: ' . $e);
        }

        return to_route('settings-user.index')->with('success', 'Data Berhasil Disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $edit = User::findOrFail($id);
        return view('content.pages.settings.user.user-detail', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, $id) {
                $user = User::where('id', $id)?->lockForUpdate();
                if (!$user) abort(404);

                $user->update([
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'password' => bcrypt($data['password']),
                    'role' => $data['role'],
                    'account_status' => $data['account_status'],
                ]);
            });
        } catch (\Exception $e) {
            Log::error($data);
            Log::error('Gagal Mengupdate User: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data Gagal Diupdate: ' . $e);
        }

        return to_route('settings-user.index')->with('success', 'Data Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function changeAccountStatus(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $user = User::findOrFail($id);
                if (!$user) abort(404);

                $user->update([
                    'account_status' => !($user->account_status)
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal Mengupdate Data:' . $e->getMessage());
        }
    }
}
