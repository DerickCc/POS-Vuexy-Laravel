@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Transaksi Pembelian')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/js/dropdown-hover.js', 'resources/assets/vendor/libs/select2/select2.js'])
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
          <label class="form-label" for="poCode">Kode</label>
          <input class="form-control dt-input" id="poCode" data-column="1" placeholder="Kode Transaksi" />
        </div>
        <div class="col-lg-3 mb-4">
          <label class="form-label" for="supplierId">Supplier</label>
          <select class="select2 form-select dt-input" id="supplierId" data-column="3" data-allow-clear="true">
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
          <select class="selectpicker dt-input w-100" data-column="6" data-style="btn-default">
            <option value="Semua">Semua</option>
            <option value="Dalam Proses">Dalam Proses</option>
            <option value="Selesai">Selesai</option>
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
    <div class="text-nowrap">
      <table class="table table-hover" id="poDatatable">
        <thead style="background: #8f8da852">
          <tr>
            <th class="text-center" width="5%">Aksi</th>
            <th width="10%">Kode</th>
            <th width="17%">Tanggal Pembelian</th>
            <th>Supplier</th>
            <th width="5%">Item</th>
            <th width="15%">Total Harga</th>
            <th class="text-center" width="10%">Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection
