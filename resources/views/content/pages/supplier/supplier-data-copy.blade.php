@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Supplier')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite(['resources/assets/js/supplier-data.js'])
@endsection

@section('content')
  <h3>Supplier</h3>
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter ti-lg me-2"></i>
        <h4 class="card-title my-auto">
          Data Supplier
        </h4>
      </div>
    </div>
    <div class="card-body">
      <form id="filterForm">
        <div class="row">
          <div class="col-lg-4 mb-4">
            <label class="form-label" for="name">Nama</label>
            <input class="form-control" id="name" type="text" placeholder="Nama Supplier" />
          </div>
          <div class="col-lg-4 mb-4">
            <label class="form-label" for="pic">PIC</label>
            <input class="form-control" id="pic" type="text" placeholder="PIC Supplier" />
          </div>
          <div class="col-lg-4 mb-4">
            <label class="form-label" for="phoneNo">No. Telepon</label>
            <input class="form-control" id="phoneNo" type="text" placeholder="No. Telepon Supplier" />
          </div>
          <div class="col-12">
            <button class="btn btn-primary float-end" type="submit" style="width: 97px">Cari</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="card">
    <div>
      <div class="row mx-2 my-4">
        <div class="col-12">
          <a class="btn btn-success float-end" href="supplier/create">Tambah</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="supplier-datatable">
          <thead style="background: #aba4f963">
            <tr>
              <th class="text-center">Aksi</th>
              <th>Kode</th>
              <th>Nama</th>
              <th>PIC</th>
              <th>No. Telepon</th>
              <th>Alamat</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($suppliers as $sup)
              <tr>
                <td class="text-center">
                  <div class="d-flex justify-content-between">
                    <a href="{{ route('supplier.edit', $sup->id) }}">
                      <i class="ti ti-edit ti-sm text-warning me-2"></i>
                    </a>
                    <a href="{{ route('supplier.delete', $sup->id) }}" onclick="confirmDelete(event)">
                      <i class="ti ti-trash ti-sm text-danger"></i>
                    </a>
                  </div>
                </td>
                <td>{{ $sup->code }}</td>
                <td>{{ $sup->name }}</td>
                <td>{{ $sup->pic }}</td>
                <td>{{ $sup->phone_no }}</td>
                <td style="min-width: 200px">{{ $sup->address }}</td>
                <td>{{ $sup->remarks ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7">Tidak Ada Data...</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-2">
        {{ $suppliers->links() }}
      </div>
    </div>
  </div>
@endsection
