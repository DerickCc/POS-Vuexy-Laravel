@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/cleavejs/cleave.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/custom/dashboard.js'])
@endsection

@section('content')
  <h4>Dashboard</h4>

  <div class="row">
    {{-- new customer card --}}
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-info">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <span class="rounded bg-label-info p-2 me-2">
              <a class="text-info" href="{{ route('master-customer.index') }}">
                <i class="ti ti-users ti-lg"></i>
              </a>
            </span>
            <h4 class="ms-1 mb-0" id="totalNewCustomer">0</h4>
          </div>
          <p class="mb-0">Pelanggan Baru Bulan Ini</p>
        </div>
      </div>
    </div>

    {{-- new product card --}}
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-primary">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <span class="rounded bg-label-primary p-2 me-2">
              <a class="text-primary" href="{{ route('inventory-product.index') }}">
                <i class="ti ti-box ti-lg"></i>
              </a>
            </span>
            <h4 class="ms-1 mb-0" id="totalNewProduct">0</h4>
          </div>
          <p class="mb-0">Barang Baru Bulan Ini</p>
        </div>
      </div>
    </div>

    {{-- total sales card --}}
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-success">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <span class="rounded bg-label-success p-2 me-2">
              <a class="text-success" href="{{ route('transaction-sales-order.index') }}">
                <i class="ti ti-cash ti-lg"></i>
              </a>
            </span>
            <h4 class="ms-1 mb-0" id="totalSales">Rp 0</h4>
          </div>
          <p class="mb-0">Total Sales Bulan Ini</p>
        </div>
      </div>
    </div>

    {{-- on going po card --}}
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card card-border-shadow-danger">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <span class="rounded bg-label-danger p-2 me-2">
              <a class="text-danger" href="{{ route('transaction-purchase-order.index') }}">
                <i class="ti ti-truck ti-lg"></i>
              </a>
            </span>
            <h4 class="ms-1 mb-0" id="totalOnGoingPo">0</h4>
          </div>
          <p class="mb-0">Pesanan Sedang Dikirim</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-0 d-flex align-items-center">
            <i class="ti ti-award ti-lg me-2 text-warning"></i>
            Barang dengan Total Keuntungan Tertinggi
          </h5>
          <div id="topProfitGeneratingProductChart"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      {{-- Incomplete Payment Table --}}
      <div class="card">
        <div class="card-body">
          <h5 class="mb-0 d-flex align-items-center">
            <i class="ti ti-cash-banknote ti-md me-2 text-danger"></i>
            Transaksi Penjualan Belum Lunas
          </h5>
        </div>

        <div class="text-nowrap">
          <table class="table table-hover" id="incompletePaymentDatatable">
            <thead style="background:
                #8f8da852">
              <tr>
                <th>No. Invoice</th>
                <th>Pelanggan</th>
                <th>Grand Total</th>
                <th>Dibayar</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

    {{-- Low Stock Table --}}
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-0 d-flex align-items-center">
            <i class="ti ti-alert-triangle ti-md me-2 text-warning"></i>
            Segera Restok
          </h5>
        </div>

        <div class="text-nowrap">
          <table class="table table-hover" id="lowStockDatatable">
            <thead style="background:
						#8f8da852">
              <tr>
                <th>Barang</th>
                <th>Sisa Stok</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
