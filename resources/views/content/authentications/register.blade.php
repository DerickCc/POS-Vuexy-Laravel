@php
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">

        <!-- Register Card -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
              <a class="app-brand-link gap-2" href="{{ url('/') }}">
                <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                <span class="app-brand-text demo text-body fw-bold ms-1">{{ config('variables.templateName') }}</span>
              </a>
            </div>
            <!-- /Logo -->

            <form class="mb-3" id="formAuthentication" action="{{ route('register') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input
                  class="form-control"
                  id="username"
                  name="username"
                  type="text"
                  placeholder="Masukkan Username Anda"
                  autofocus
                >
              </div>
              <div class="mb-3">
                <label class="form-label" for="name">Nama</label>
                <input
                  class="form-control"
                  id="name"
                  name="name"
                  type="text"
                  placeholder="Masukkan Nama Anda"
                >
              </div>
              <div class="mb-4 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input
                    class="form-control"
                    id="password"
                    name="password"
                    type="password"
                    aria-describedby="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  />
                  <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                </div>
              </div>

              <button class="btn btn-primary d-grid w-100">
                Daftar
              </button>
            </form>

            <p class="text-center">
              <span>Already have an account?</span>
              <a href="{{ route('login') }}">
                <span>Login</span>
              </a>
            </p>
          </div>
        </div>
        <!-- Register Card -->
      </div>
    </div>
  </div>
@endsection
