@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js'])
@endsection

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">User</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Pengaturan</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('settings-user.index') }}">Data User</a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">{{ isset($edit) ? 'Edit' : 'Tambah' }} User</a>
        </li>
      </ol>
    </nav>
  </div>
  <div class="card col-lg-8">
    <div class="card-header d-flex align-items-center">
      <i class="ti ti-pencil-plus ti-lg me-2"></i>
      <h4 class="card-title my-auto">{{ isset($edit) ? 'Edit' : 'Tambah' }} User</h4>
    </div>
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <div class="card-body">
      <form id="userForm"
        action="{{ isset($edit) ? route('settings-user.update', $edit->id) : route('settings-user.store') }}"
        method="POST"
      >
        @if (isset($edit))
          @method('PUT')
        @endif
        @csrf
        <div class="row g-3">
          <div class="col-lg-6">
            <label class="form-label required" for="username">Username</label>
            <input
              class="form-control @error('username') is-invalid @enderror"
              id="username"
              name="username"
              type="text"
              value="{{ old('username', $edit->username ?? '') }}"
              placeholder="Username"
            />
            <div class="invalid-feedback">
              @error('username')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-6">
            <label class="form-label required" for="name">Nama</label>
            <input
              class="form-control @error('name') is-invalid @enderror"
              id="name"
              name="name"
              type="text"
              value="{{ old('name', $edit->name ?? '') }}"
              placeholder="Nama User"
            />
            <div class="invalid-feedback">
              @error('name')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-6 form-password-toggle">
            <label class="form-label required" for="password">Password</label>
            <div class="input-group input-group-merge">
              <input
                class="form-control"
                id="password"
                name="password"
                type="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
              />
              <span class="input-group-text cursor-pointer">
                <i class="ti ti-eye-off"></i>
              </span>
            </div>
          </div>

          <div class="col-lg-6 form-password-toggle">
            <label class="form-label required" for="confirm_password">Konfirmasi Password</label>
            <div class="input-group input-group-merge">
              <input
                class="form-control"
                id="confirm_password"
                name="confirm_password"
                type="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
              />
              <span class="input-group-text cursor-pointer">
                <i class="ti ti-eye-off"></i>
              </span>
            </div>
            <div class="invalid-feedback">
              @error('confirm_password')
                {{ $message }}
              @enderror
            </div>
          </div>

          <div class="col-lg-6">
            <label class="form-label required" for="role">Role</label>
            <select class="selectpicker w-100" id="role" name="role" data-style="btn-default">
              <option value="Admin" {{ isset($edit) ? ($edit->role == 'Admin' ? 'selected' : '') : '' }}>Admin</option>
              <option value="User" {{ isset($edit) ? ($edit->role == 'User' ? 'selected' : '') : '' }}>User</option>
            </select>
          </div>

          <div class="col-lg-6">
            <label class="form-label required" for="account_status">Status Akun</label>
            <select class="selectpicker w-100" id="account_status" name="account_status" data-style="btn-default">
              <option value="1" {{ isset($edit) ? ($edit->account_status == 1 ? 'selected' : '') : '' }}>Aktif
              </option>
              <option value="0" {{ isset($edit) ? ($edit->account_status == 0 ? 'selected' : '') : '' }}>Tidak Aktif
              </option>
            </select>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <a class="btn btn-outline-primary float-start" href="{{ route('settings-user.index') }}">Kembali</a>
            <button class="btn btn-success float-end" id="submitBtn" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
