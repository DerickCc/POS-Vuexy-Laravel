@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pelanggan')

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Pelanggan</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Master</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('master-customer.index') }}">Data Pelanggan</a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">{{ isset($edit) ? 'Edit' : 'Tambah' }} Pelanggan</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card">
    <div class="card-header d-flex align-items-center">
      <i class="ti ti-pencil-plus ti-lg me-2"></i>
      <h4 class="card-title my-auto">{{ isset($edit) ? 'Edit' : 'Tambah' }} Pelanggan</h4>
    </div>
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <div class="card-body">
      <form id="customerForm"
        action="{{ isset($edit) ? route('master-customer.update', $edit->id) : route('master-customer.store') }}"
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
              placeholder="Nama Pelanggan"
            />
            <div class="invalid-feedback">
              @error('name')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-4">
            <label class="form-label required" for="license_plate">No. Plat</label>
            <input
              class="form-control @error('license_plate') is-invalid @enderror"
              id="license_plate"
              name="license_plate"
              type="text"
              value="{{ old('license_plate', $edit->license_plate ?? '') }}"
              placeholder="No. Plat Pelanggan"
            />
            <div class="invalid-feedback">
              @error('license_plate')
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
              value="{{ old('phone_no', $edit->phone_no ?? '') }}"
              placeholder="No. Telepon Pelanggan"
            />
            <div class="invalid-feedback">
              @error('phone_no')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-12">
            <label class="form-label" for="address">Alamat</label>
            <textarea
              class="form-control @error('address') is-invalid @enderror"
              id="address"
              name="address"
              placeholder="Alamat Pelanggan"
              rows="5"
            >{{ old('address', $edit->address ?? '') }}</textarea>
            <div class="invalid-feedback">
              @error('address')
                {{ $message }}
              @enderror
            </div>
          </div>

        </div>
        <div class="row mt-4">
          <div class="col-12">
            <a class="btn btn-outline-primary float-start" href="{{ route('master-customer.index') }}">Kembali</a>
            <button class="btn btn-success float-end" id="submitBtn" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
