@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Transaksi Penjualan')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/js/dropdown-hover.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/cleavejs/cleave.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/custom/sales-order-data.js'])
@endsection

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Transaksi Penjualan</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Transaksi</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('master-customer.index') }}">Data Transaksi Penjualan</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter ti-md me-2"></i>
        <h4 class="card-title my-auto">
          Filter Transaksi Penjualan
        </h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="soCode">Kode</label>
          <input class="form-control dt-input" id="soCode" data-column="1" placeholder="Kode Transaksi" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="customerId">Customer</label>
          <select class="select2 form-select dt-input" id="customerId" data-column="4" data-allow-clear="true">
          </select>
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="startDate">Tanggal Mulai</label>
          <input class="form-control dt-date-input" id="startDate" placeholder="Tanggal Mulai" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="endDate">Tanggal Akhir</label>
          <input class="form-control dt-date-input" id="endDate" placeholder="Tanggal Akhir" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="status">Status</label>
          <select class="selectpicker dt-input w-100" data-column="9" data-style="btn-default">
            <option value="Semua">Semua</option>
            <option value="Belum Lunas">Belum Lunas</option>
            <option value="Lunas">Lunas</option>
            <option value="Dibatalkan">Batal</option>
          </select>
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
    <div class="text-nowrap table-responsive">
      <table class="table table-hover" id="soDatatable">
        <thead style="background:
        #8f8da852">
          <tr>
            <th class="text-center" style="max-width: 50px">Aksi</th>
            <th width="10%">No. Invoice</th>
            <th width="17%">Kasir</th>
            <th width="17%">Tanggal Penjualan</th>
            <th>Pelanggan</th>
            <th width="5%">Sub Total</th>
            <th width="5%">Diskon</th>
            <th width="5%">Grand Total</th>
            <th width="15%">Dibayar</th>
            <th class="text-center" width="10%">Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  @include('content.pages.transaction.sales-order.modal.payment-modal')
@endsection
