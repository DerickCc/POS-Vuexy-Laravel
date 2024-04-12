@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User')

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
  @vite(['resources/assets/js/custom/user-data.js'])
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
      </ol>
    </nav>
  </div>
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter ti-lg me-2"></i>
        <h4 class="card-title my-auto">
          Filter User
        </h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4 mb-4">
          <label class="form-label" for="username">Username</label>
          <input class="form-control dt-input" id="username" data-column="2" placeholder="Username" />
        </div>
        <div class="col-md-4 mb-4">
          <label class="form-label" for="name">Nama</label>
          <input class="form-control dt-input" id="name" data-column="3" placeholder="Nama User" />
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
    <div class="text-nowrap">
      <table class="table table-hover" id="userDatatable">
        <thead style="background: #8f8da852">
          <tr>
            <th class="text-center" style="max-width: 50px">Aksi</th>
            <th>Id</th>
            <th>Username</th> <!-- 2 -->
            <th>Nama</th> <!-- 3 -->
            <th>Role</th>
            <th style="max-width: 200px">Status Akun</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

<script type="text/javascript">
  function confirmChangeStatus(event, tableId) {
    event.preventDefault();
    const action = event.currentTarget.getAttribute('href');
    const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: 'Perubahan Status Akun akan berpengaruh pada user!',
      icon: 'warning',
      confirmButtonText: 'Ya, ubah!',
      customClass: {
        confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
      }
    }).then(confirm => {
      // if user choose yes
      if (confirm.isConfirmed) {
        fetch(action, {
            method: 'PUT',
            headers: {
              'X-CSRF-TOKEN': csrf_token
            }
          })
          .then(res => {
            if (res.ok) {
              Swal.fire({
                title: 'Success',
                text: 'Status Akun Berhasil Diubah',
                icon: 'success',
                customClass: {
                  confirmButton: 'btn btn-primary me waves-effect waves-light'
                }
              });

              // refresh table after successful delete
              var table = $(tableId).DataTable();
              const pageInfo = table.page.info();
              const searchValue = table.search();

              table.ajax.reload(function() {
                // restore previous filter, search
                table.page(pageInfo.page).draw(false);
                table.search(searchValue).draw(false);
              });
            } else
              Swal.fire({
                title: 'Error',
                text: 'Status Akun Gagal Diubah',
                icon: 'error',
                customClass: {
                  confirmButton: 'btn btn-primary me waves-effect waves-light'
                }
              });
          })
          .catch(e => {
            Swal.fire({
              title: 'Error',
              text: e,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary me waves-effect waves-light'
              }
            });
          });
      }
    });
  }
</script>
