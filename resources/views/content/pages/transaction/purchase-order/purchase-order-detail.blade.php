@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Transaksi Penbelian')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/cleavejs/cleave.js'])
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
      <form id="POForm"
        action="{{ isset($edit) ? route('transaction-purchase-order.update', $edit->id) : route('transaction-purchase-order.store') }}"
        method="POST"
      >
        <div class="row g-4">
          <div class="col-lg-4">
            <label class="form-label" for="poCode">Kode Transaksi Pembelian</label>
            <input
              class="form-control @error('po_code') is-invalid @enderror"
              id="poCode"
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
            <label class="form-label" for="purchaseDate">Tanggal Pembelian</label>
            <input
              class="form-control @error('purchase_date') is-invalid @enderror"
              id="purchaseDate"
              name="purchase_date"
              type="datetime-local"
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
            <label class="form-label required" for="supplierId">Supplier</label>
            <select class="select2 form-select" id="supplierId" name="supplier_id">
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
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h4 class="card-title my-auto">
        <i class="ti ti-shopping-cart ti-lg me-2"></i>
        Detail Barang Pembelian
      </h4>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table table-hover" id="PODetailTable">
              <thead style="background: #8f8da852">
                <tr>
                  <th class="text-center px-0" width="5%">Aksi</th>
                  <th width="27%">Barang</th>
                  <th width="20%">Harga Beli</th>
                  <th width="13%">Qty</th>
                  <th width="12%">Satuan</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th>
                    <button class="btn btn-icon btn-primary" id="addProductRowBtn" type="button">
                      <i class="ti ti-plus ti-xs"></i>
                    </button>
                  </th>
                  <th></th>
                  <th></th>
                  <th colspan="2"><span class="float-end">Grand Total</span></th>
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">Rp</span>
                      <input
                        class="form-control"
                        id="grandTotal"
                        name="grand_total"
                        value="0"
                        readonly
                      />
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <a class="btn btn-outline-primary float-start"
            href="{{ route('transaction-purchase-order.index') }}">Kembali</a>
          <button class="btn btn-success float-end" id="submitBtn" type="button">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  @include('content.pages.transaction.purchase-order.modal.product-modal')
@endsection
