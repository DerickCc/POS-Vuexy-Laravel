@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Transaksi Penjualan')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/custom/purchase-order-data.js'])
@endsection

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Transaksi Pembelian</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Transaksi</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('master-customer.index') }}">Data Transaksi Pembelian</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter ti-lg me-2"></i>
        <h4 class="card-title my-auto">
          Filter Transaksi Pembelian
        </h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="po_code">Kode</label>
          <input class="form-control dt-input" id="po_code" data-column="2" placeholder="Kode Transaksi" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="start_date">Tanggal Mulai</label>
          <input class="form-control dt-input" id="start_date" placeholder="Tanggal Mulai" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="end_date">Tanggal Akhir</label>
          <input class="form-control dt-input" id="end_date" placeholder="Tanggal Akhir" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="status">Status</label>
          <input class="form-control dt-input" id="status" data-column="4" placeholder="Status Transaksi" />
        </div>
      </div>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover" id="purchaseOrderDatatable">
        <thead style="background: #8f8da852">
          <tr>
            <th class="text-center" style="max-width: 60px">Aksi</th>
            <th style="max-width: 80px">Kode</th>
            <th>Tanggal Pembelian</th>
            <th>Supplier</th>
            <th>Total Item</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection
