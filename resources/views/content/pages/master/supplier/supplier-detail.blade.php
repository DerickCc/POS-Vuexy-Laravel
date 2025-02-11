@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Supplier')

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
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">{{ isset($edit) ? 'Edit' : 'Tambah' }} Supplier</a>
        </li>
      </ol>
    </nav>
  </div>
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
      <form id="supplierForm"
        action="{{ isset($edit) ? route('master-supplier.update', $edit->id) : route('master-supplier.store') }}"
        method="POST"
      >
        @if (isset($edit))
          @method('PUT')
        @endif
        @csrf
        <div class="row g-3">
          <div class="col-lg-4">
            <label class="form-label required" for="name">Nama</label>
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
            <label class="form-label required" for="pic">PIC</label>
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
            <label class="form-label required" for="phone_no">No. Telepon</label>
            <input
              class="form-control @error('phone_no') is-invalid @enderror"
              id="phone_no"
              name="phone_no"
              type="text"
              value="{{ old('phoneNo', $edit->phone_no ?? '') }}"
              placeholder="No. Telepon Supplier"
            />
            <div class="invalid-feedback">
              @error('phone_no')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-6">
            <label class="form-label" for="address">Alamat</label>
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
            <a class="btn btn-outline-primary float-start" href="{{ route('master-supplier.index') }}">Kembali</a>
            <button class="btn btn-success float-end" id="submitBtn" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
