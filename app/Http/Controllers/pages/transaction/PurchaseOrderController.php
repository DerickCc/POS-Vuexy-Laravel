<?php

namespace App\Http\Controllers\pages\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.pages.transaction.purchase-order-data');
    }

    public function getData(Request $request)
    {
        return null;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.pages.transaction.purchase-order-detail');
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
