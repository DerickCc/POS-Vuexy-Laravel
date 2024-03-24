@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Supplier')

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
  @vite(['resources/assets/js/custom/supplier-data.js'])
@endsection

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Supplier</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Master</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('master-supplier.index') }}">Data Supplier</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter ti-lg me-2"></i>
        <h4 class="card-title my-auto">
          Filter Supplier
        </h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <label class="form-label" for="name">Nama</label>
          <input class="form-control dt-input" id="name" data-column="2" placeholder="Nama Supplier" />
        </div>
        <div class="col-lg-4 mb-4">
          <label class="form-label" for="pic">PIC</label>
          <input class="form-control dt-input" id="pic" data-column="3" placeholder="PIC Supplier" />
        </div>
        <div class="col-lg-4 mb-4">
          <label class="form-label" for="phoneNo">No. Telepon</label>
          <input class="form-control dt-input" id="phoneNo" data-column="4" placeholder="No. Telepon Supplier" />
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
      <table class="table table-bordered table-striped table-hover" id="supplier-datatable">
        <thead style="background: #8f8da852">
          <tr>
            <th class="text-center" style="max-width: 50px">Aksi</th>
            <th style="max-width: 80px">Kode</th>
            <th>Nama</th> <!-- 2 -->
            <th>PIC</th> <!-- 3 -->
            <th>No. Telepon</th> <!-- 4 -->
            <th>Alamat</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection
