@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Supplier')

@section('content')
  <h3>Supplier</h3>
  <div class="card">
    <div class="card-header d-flex align-items-center">
      <i class="ti ti-pencil-plus ti-lg me-2"></i>
      <h4 class="card-title my-auto">{{ isset($edit) ? 'Edit' : 'Tambah' }} Supplier</h4>
    </div>
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <div class="card-body">
      <form id="supplierForm" action="{{ isset($edit) ? route('supplier.update', $edit->id) : route('supplier.store') }}"
        method="POST"
      >
        @if (isset($edit))
          @method('PUT')
        @endif
        @csrf
        <div class="row g-3">
          <div class="col-lg-4">
            <label class="form-label" for="name">Nama Supplier</label>
            <input
              class="form-control @error('name') is-invalid @enderror"
              id="name"
              name="name"
              type="text"
              value="{{ old('name', $edit->name ?? '') }}"
              placeholder="Nama Supplier"
            />
            <div class="invalid-feedback">
              @error('name')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-4">
            <label class="form-label" for="pic">PIC Supplier</label>
            <input
              class="form-control @error('pic') is-invalid @enderror"
              id="pic"
              name="pic"
              type="text"
              value="{{ old('pic', $edit->pic ?? '') }}"
              placeholder="PIC Supplier"
            />
            <div class="invalid-feedback">
              @error('pic')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-4">
            <label class="form-label" for="phoneNo">No. Telepon Supplier</label>
            <input
              class="form-control @error('phoneNo') is-invalid @enderror"
              id="phoneNo"
              name="phoneNo"
              type="text"
              value="{{ old('phoneNo', $edit->phone_no ?? '') }}"
              placeholder="No. Telepon Supplier"
            />
            <div class="invalid-feedback">
              @error('phoneNo')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-6">
            <label class="form-label" for="address">Alamat Supplier</label>
            <textarea
              class="form-control @error('address') is-invalid @enderror"
              id="address"
              name="address"
              placeholder="Alamat Supplier"
              rows="4"
            >{{ old('address', $edit->address ?? '') }}</textarea>
            <div class="invalid-feedback">
              @error('address')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-6">
            <label class="form-label" for="remarks">Keterangan</label>
            <textarea
              class="form-control @error('remarks') is-invalid @enderror"
              id="remarks"
              name="remarks"
              placeholder="Keterangan Tambahan"
              rows="4"
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
            <a class="btn btn-outline-primary float-start" href="{{ route('master-supplier') }}">Kembali</a>
            <button class="btn btn-success float-end" id="submitBtn" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
