@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Transaksi Penbelian')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/custom/purchase-order-detail.js'])
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
          <a href="{{ route('transaction-purchase-order.index') }}">Data Transaksi Pembelian</a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">{{ isset($edit) ? 'Edit' : 'Tambah' }} Transaksi
            Pembelian</a>
        </li>
      </ol>
    </nav>
  </div>

  <form id="POForm"
    action="{{ isset($edit) ? route('transaction-purchase-order.update', $edit->id) : route('transaction-purchase-order.store') }}"
    method="POST"
  >
    @if (isset($edit))
      @method('PUT')
    @endif
    @csrf

    <div class="card mb-4">
      <div class="card-header d-flex align-items-center">
        <i class="ti ti-pencil-plus ti-lg me-2"></i>
        <h4 class="card-title my-auto">{{ isset($edit) ? 'Edit' : 'Tambah' }} Transaksi Pembelian</h4>
      </div>
      @if (session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif
      <div class="card-body">

        <div class="row g-4">
          <div class="col-lg-4">
            <label class="form-label" for="po_code">Kode Transaksi Pembelian</label>
            <input
              class="form-control @error('po_code') is-invalid @enderror"
              id="po_code"
              name="po_code"
              type="text"
              value="{{ old('po_code', $edit->po_code ?? '') }}"
              readonly
              placeholder="Auto Generate"
            />
            <div class="invalid-feedback">
              @error('po_code')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-4">
            <label class="form-label" for="purchase_date">Tanggal Pembelian</label>
            <input
              class="form-control @error('purchase_date') is-invalid @enderror"
              id="purchase_date"
              name="purchase_date"
              type="date"
              value="{{ old('purchase_date', $edit->purchase_date ?? '') }}"
              readonly
              placeholder="Tanggal Pembelian"
            />
            <div class="invalid-feedback">
              @error('purchase_date')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-4">
            <label class="form-label required" for="selectSupplier">Supplier</label>
            <select class="select2 form-select" id="selectSupplier" name="supplier_id">
            </select>
          </div>

          <div class="col-12">
            <label class="form-label" for="remarks">Keterangan</label>
            <textarea
              class="form-control"
              id="remarks"
              name="remarks"
              rows="4"
              placeholder="Keterangan"
            ></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="card-title my-auto">
          <i class="ti ti-shopping-cart ti-lg me-2"></i>
          Detail Barang Pembelian
        </h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" type="button">Tambah
          Barang</button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <table id="PODetailTable" class="table">
              <tr>
                <th>Aksi</th>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Harga Beli</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Satuan</th>
                <th>Keterangan</th>
              </tr>
            </table>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <a class="btn btn-outline-primary float-start"
              href="{{ route('transaction-purchase-order.index') }}">Kembali</a>
            <button class="btn btn-success float-end" id="submitBtn" type="submit">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  @include('content.pages.transaction.purchase-order.modal.product-modal')
@endsection
