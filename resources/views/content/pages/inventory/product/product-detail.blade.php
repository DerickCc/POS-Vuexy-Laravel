@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Barang')

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/cleavejs/cleave.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/custom/product-detail.js'])
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
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">{{ isset($edit) ? 'Edit' : 'Tambah' }} Barang</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card">
    <div class="card-header d-flex align-items-center">
      <i class="ti ti-pencil-plus ti-lg me-2"></i>
      <h4 class="card-title my-auto">{{ isset($edit) ? 'Edit' : 'Tambah' }} Barang</h4>
    </div>
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <div class="card-body">
      <form id="productForm"
        action="{{ isset($edit) ? route('inventory-product.update', $edit->id) : route('inventory-product.store') }}"
        enctype="multipart/form-data" method="POST"
      >
        @if (isset($edit))
          @method('PUT')
        @endif
        @csrf
        <div class="row g-4">
          <div class="col-lg-3">
            <div class="row g-4">
              <div class="col-12">
                <label class="form-label" for="photo">Foto Barang</label>
                <input
                  class="form-control @error('photo') is-invalid @enderror"
                  id="photo"
                  name="photo"
                  type="file"
                  accept="image/png, image/jpeg, image/jpg, image/svg"
                  hidden
                />
              </div>
              <div class="col-12 text-center">
                <img
                  class="img-fluid rounded"
                  id="previewImage"
                  src="{{ isset($edit) && $edit->photo ? asset('storage/' . $edit->photo) : asset('assets/img/illustrations/image-placeholder.png') }}"
                  style="width: 180px; height: 180px; cursor: pointer"
                  onclick="document.getElementById('photo').click()"
                >
                <div class="text-danger">
                  @error('photo')
                    {{ $message }}
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-9">
            <div class="row g-4">
              <div class="col-12">
                <label class="form-label required" for="name">Nama Barang</label>
                <input
                  class="form-control @error('name') is-invalid @enderror"
                  id="name"
                  name="name"
                  type="text"
                  value="{{ old('name', $edit->name ?? '') }}"
                  placeholder="Nama Barang"
                />
                <div class="invalid-feedback">
                  @error('name')
                    {{ $message }}
                  @enderror
                </div>
              </div>
              <div class="col-6">
                <label class="form-label required" for="stock">Stok</label>
                <input
                  class="form-control @error('stock') is-invalid @enderror"
                  id="stock"
                  name="stock"
                  type="text"
                  value="{{ old('stock', $edit->stock ?? '0.0') }}"
                  placeholder="Stok Barang"
                  readonly
                />
                <div class="invalid-feedback">
                  @error('stock')
                    {{ $message }}
                  @enderror
                </div>
              </div>
              <div class="col-6">
                <label class="form-label required" for="uom">Satuan</label>
                <input
                  class="form-control @error('uom') is-invalid @enderror"
                  id="uom"
                  name="uom"
                  type="text"
                  value="{{ old('uom', $edit->uom ?? '') }}"
                  placeholder="Satuan Barang"
                />
                <div class="invalid-feedback">
                  @error('uom')
                    {{ $message }}
                  @enderror
                </div>
              </div>
              <div class="col-6">
                <label class="form-label required" for="purchase_price">Harga Beli</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input
                    class="form-control @error('purchase_price') is-invalid @enderror"
                    id="purchase_price"
                    name="purchase_price"
                    type="text"
                    value="{{ old('purchase_price', $edit->purchase_price ?? 0) }}"
                    placeholder="Harga Beli"
                  />
                  <div class="invalid-feedback">
                    @error('purchase_price')
                      {{ $message }}
                    @enderror
                  </div>
                </div>
              </div>
              <div class="col-6">
                <label class="form-label required" for="selling_price">Harga Jual</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input
                    class="form-control @error('selling_price') is-invalid @enderror"
                    id="selling_price"
                    name="selling_price"
                    type="text"
                    value="{{ old('selling_price', $edit->selling_price ?? 0) }}"
                    placeholder="Harga Jual"
                  />
                  <div class="invalid-feedback">
                    @error('selling_price')
                      {{ $message }}
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <label class="form-label" for="remarks">Keterangan</label>
            <textarea
              class="form-control @error('remarks') is-invalid @enderror"
              id="remarks"
              name="remarks"
              placeholder="Keterangan"
              rows="5"
            >{{ old('remarks', $edit->remarks ?? '') }}</textarea>
            <div class="invalid-feedback">
              @error('remarks')
                {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <a class="btn btn-outline-primary float-start" href="{{ route('inventory-product.index') }}">Kembali</a>
            <button class="btn btn-success float-end" id="submitBtn" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
