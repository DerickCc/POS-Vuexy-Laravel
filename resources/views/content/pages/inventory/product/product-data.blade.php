@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Barang')

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
  @vite(['resources/assets/js/custom/product-data.js'])
@endsection

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Barang</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Master</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('inventory-product.index') }}">Data Barang</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter ti-lg me-2"></i>
        <h4 class="card-title my-auto">
          Filter Barang
        </h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <label class="form-label" for="name">Nama</label>
          <input class="form-control dt-input" id="name" data-column="3" placeholder="Nama Barang" />
        </div>
        <div class="col-lg-4 mb-4">
          <label class="form-label" for="stock">Stok</label>
          <div class="input-group">
            <button class="btn btn-outline-primary" id="stockOperatorBtn" data-bs-toggle="dropdown"
              type="button"
            ><</button>
            <ul class="dropdown-menu" id="stockOperatorList">
              <li><a class="dropdown-item" href="javascript:void(0);"><</a></li>
              <li><a class="dropdown-item" href="javascript:void(0);">></a></li>
              <li><a class="dropdown-item" href="javascript:void(0);">=</a></li>
            </ul>
            <input
              class="form-control dt-input-stock"
              id="stock"
              data-column="4"
              type="number"
              placeholder="Stok Barang"
            />
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <label class="form-label" for="uom">Satuan</label>
          <input class="form-control dt-input" id="uom" data-column="5" placeholder="Satuan Barang" />
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
      <table class="table table-hover" id="productDatatable">
        <thead style="background: #8f8da852">
          <tr>
            <th class="text-center" style="max-width: 50px">Aksi</th>
            <th>Id</th>
            <th style="width: 100px">Foto</th>
            <th>Nama Barang</th> <!-- 3 -->
            <th>Stok</th> <!-- 4 -->
            <th>Satuan</th> <!-- 5 -->
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection
