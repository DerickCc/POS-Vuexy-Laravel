@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Transaksi Penjualan')

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
  @vite(['resources/assets/js/custom/sales-order-detail.js'])
@endsection

@section('content')
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Transaksi Penjualan</h3>
    <h2 class="mb-0 mx-3">|</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a class="text-secondary" href="javascript:void(0)">Transaksi</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('transaction-sales-order.index') }}">Data Transaksi Penjualan</a>
        </li>
        <li class="breadcrumb-item">
          <a class="text-secondary"
            href="javascript:void(0)">{{ isset($view) ? 'Lihat' : 'Tambah' }} Transaksi
            Penjualan</a>
        </li>
      </ol>
    </nav>
  </div>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="ti ti-{{ isset($view) ? 'eye' : 'pencil-plus' }} ti-md me-2"></i>
      <h4 class="card-title my-auto">{{ isset($view) ? 'Lihat' : 'Tambah' }} Transaksi Penjualan
      </h4>
    </div>
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <div class="card-body">
      <form id="soForm"
        action="{{ isset($view) ? '' : route('transaction-sales-order.store') }}"
        method="POST"
      >
        <div class="row g-4">
          <div class="col-lg-4">
            <label class="form-label" for="cashier">Kasir</label>
            <input
              class="form-control"
              id="cashier"
              name="cashier"
              type="text"
              value="{{ $view->createdBy->name ?? ($cashierName ?? '') }}"
              readonly
              placeholder="Auto Generate"
            />
          </div>

          <div class="col-lg-4">
            <label class="form-label" for="salesDate">Tanggal Penjualan</label>
            <input
              class="form-control"
              id="salesDate"
              name="sales_date"
              type="datetime-local"
              readonly
              placeholder="Tanggal Penjualan"
            />
          </div>

          <div class="col-lg-4">
            <label class="form-label required" for="customerId">Pelanggan</label>
            <select class="select2 form-select" id="customerId" name="customer_id">
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
            >{{ old('remarks', $view->remarks ?? '') }}</textarea>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    {{-- table card --}}
    <div class="col-lg-9">
      <div class="row g-4">
        <div class="col-12" id="soProductDetails">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h4 class="card-title my-auto">
                <i class="ti ti-shopping-cart ti-md me-2"></i>
                Detail Barang Penjualan
              </h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="soProductDetailTable">
                      <thead style="background: #8f8da852">
                        <tr>
                          <th class="text-center px-0" style="max-width: 50px">Aksi</th>
                          <th style="min-width: 350px">Barang</th>
                          <th style="min-width: 170px">Harga Jual</th>
                          <th style="min-width: 120px">Qty</th>
                          <th style="min-width: 180px">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>
                            <button class="btn btn-icon btn-primary" id="addProductRowBtn" type="button"
                              @if (isset($view)) style="display: none !important;" @endif
                            >
                              <i class="ti ti-plus ti-xs"></i>
                            </button>
                          </th>
                          <th></th>
                          <th colspan="2"><span class="float-end">Total</span></th>
                          <td>
                            <div class="input-group">
                              <span class="input-group-text">Rp</span>
                              <input
                                class="form-control"
                                id="totalProductPrice"
                                name="total_product_price"
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
            </div>
          </div>
        </div>

        <div class="col-12" id="soServiceDetails">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h4 class="card-title my-auto">
                <i class="ti ti-tool ti-md me-2"></i>
                Detail Jasa Penjualan
              </h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="soServiceDetailTable">
                      <thead style="background: #8f8da852">
                        <tr>
                          <th class="text-center px-0" style="max-width: 50px">Aksi</th>
                          <th style="min-width: 350px">Jasa</th>
                          <th style="min-width: 200px">Harga Jual</th>
                          <th style="min-width: 130px">Qty</th>
                          <th style="min-width: 200px">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>
                            <button class="btn btn-icon btn-primary" id="addServiceRowBtn" type="button"
                              @if (isset($view)) style="display: none !important;" @endif
                            >
                              <i class="ti ti-plus ti-xs"></i>
                            </button>
                          </th>
                          <th></th>
                          <th colspan="2"><span class="float-end">Total</span></th>
                          <td>
                            <div class="input-group">
                              <span class="input-group-text">Rp</span>
                              <input
                                class="form-control"
                                id="totalServicePrice"
                                name="total_service_price"
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
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- payment card --}}
    <div class="col-lg-3 mt-4 mt-lg-0">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 h5">
              <span class="font-weight-bolder me-1">Invoice No: </span><span
                id="soCode">{{ $view->so_code ?? ($soCode ?? '') }}</span>
            </div>
            <div class="col-12 pb-4 d-flex align-items-end border-bottom">
              <span>Rp.</span>
              <input
                class="form-control"
                id="grandTotal"
                name="grand_total"
                value="{{ old('grand_total', $view->grand_total ?? 0) }}"
                style="min-height: 50px; font-size: 32px; text-align: end; font-weight: bold;"
                readonly
              />
            </div>
            <div class="col-12 mt-3">
              <label class="form-label" for="payment_type">Tipe</label><br>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  id="DP"
                  name="payment_type"
                  type="radio"
                  value="DP"
                  checked
                />
                <label class="form-check-label" for="DP">DP</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  id="lunas"
                  name="payment_type"
                  type="radio"
                  value="Lunas"
                />
                <label class="form-check-label" for="lunas">Lunas</label>
              </div>
            </div>
            <div class="col-12 mt-3">
              <label class="form-label" for="subTotal">Sub Total</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input
                  class="form-control"
                  id="subTotal"
                  name="sub_total"
                  value="{{ old('sub_total', $view->sub_total ?? 0) }}"
                  readonly
                />
              </div>
            </div>
            <div class="col-12 mt-4">
              <label class="form-label" for="discount">Total Diskon</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input
                  class="form-control"
                  id="discount"
                  name="discount"
                  value="{{ old('discount', $view->discount ?? 0) }}"
                  readonly
                />
              </div>
            </div>
            <div class="col-12 mt-4">
              <label class="form-label" for="paidAmount">Jumlah Yang Sudah Dibayar</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input class="form-control" id="paidAmount" name="paid_amount"
                  value="{{ old('paid_amount', $view->paid_amount ?? 0) }}"
                />
              </div>
            </div>
            <div class="col-12 mt-5">
              <a class="btn btn-outline-primary float-start"
                href="{{ route('transaction-sales-order.index') }}">Kembali</a>
              <button class="btn btn-success float-end" id="submitBtn" type="button"
                @if (isset($view)) style="display: none !important;" @endif
              >Simpan</button>
              <button id="openPaymentModalBtn" type="button" hidden
                @if (isset($view) && $view->status == 'Belum Lunas') class="btn btn-warning float-end" @endif
              >Bayar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('content.pages.transaction.sales-order.modal.payment-modal')
@endsection

<script>
  var view = <?= $view ?? 'null' ?>;
</script>
