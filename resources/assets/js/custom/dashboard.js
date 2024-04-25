const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function getTotalNewCustomer(period) {
  $.ajax({
    url: 'master/customer/get-total-new-customer',
    type: 'GET',
    data: {
      period: period
    },
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function (res) {
      console.log(res)
      $('#totalNewCustomer').text(res.total_new_customer);
    },
    error: function (xhr, status, e) {
      toastr.error('Gagal memuat total pelanggan baru', 'Error');
      console.log('Failed to load new Customer: ' + e)
    }
  })
}

function getTotalNewProduct(period) {
  $.ajax({
    url: 'inventory/product/get-total-new-product',
    type: 'GET',
    data: {
      period: period
    },
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function (res) {
      console.log(res)
      $('#totalNewProduct').text(res.total_new_product);
    },
    error: function (xhr, status, e) {
      toastr.error('Gagal memuat total Barang baru', 'Error');
      console.log('Failed to load new Product: ' + e)
    }
  })
}

function getTotalSales(period) {
  $.ajax({
    url: 'transaction/sales-order/get-total-sales',
    type: 'GET',
    data: {
      period: period
    },
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function (res) {
      const formattedPrice = parseFloat(res.total_sales).toLocaleString('id-ID');
      $('#totalSales').text('Rp ' + formattedPrice);
    },
    error: function (xhr, status, e) {
      toastr.error('Gagal memuat total Penjualan', 'Error');
      console.log('Failed to load total sales: ' + e)
    }
  })
}

function getTotalOnGoingPo() {
  $.ajax({
    url: 'transaction/purchase-order/get-total-on-going-po',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function (res) {
      console.log(res)
      $('#totalOnGoingPo').text(res.total_on_going_po);
    },
    error: function (xhr, status, e) {
      toastr.error('Gagal memuat total pesanan sedang dikirim', 'Error');
      console.log('Failed to load total on going PO: ' + e)
    }
  })
}

function getTopProfitGeneratingProduct() {
  $.ajax({
    url: 'inventory/product/get-top-profit-generating-product',
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function (res) {
      console.log(res)

    },
    error: function (xhr, status, e) {
      toastr.error('Gagal memuat Barang dengan Total Keuntungan Tertinggi', 'Error');
      console.log('Failed to load Top Profit Generating Product: ' + e)
    }
  })
}

// On Init
$(function() {
  getTotalNewCustomer('month');
  getTotalNewProduct('month');
  getTotalSales('month');
  getTotalOnGoingPo();
  getTopProfitGeneratingProduct();
})

var incompletePaymentTable = $('#incompletePaymentDatatable').DataTable({
  processing: true,
  serverSide: true,
  scrollX: true,
  ajax: '/transaction/sales-order/browse-incomplete-payment',
  columns: [
    {
      data: 'so_code',
      name: 'so_code',
      sortable: false
    },
    {
      data: 'customer',
      name: 'customer',
      sortable: false
    },
    {
      data: 'grand_total',
      name: 'grand_total',
      sortable: false,
      render: function (data) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return 'Rp ' + formattedPrice;
      }
    },
    {
      data: 'paid_amount',
      name: 'paid_amount',
      sortable: false,
      render: function (data) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return 'Rp ' + formattedPrice;
      }
    },
  ],
  language: {
    lengthMenu: 'Tampilkan _MENU_ data',
    zeroRecords: 'Semua Transaksi Telah Lunas 🔥',
    info: 'Halaman _PAGE_ dari _PAGES_',
    infoEmpty: 'Data tidak ditemukan ',
    infoFiltered: '(Difilter dari _MAX_ data)'
  },
  dom: '<"row"tr<"px-4 my-1 col-md-6"i><"px-4 mt-1 mb-3 col-md-6"p>>' // Customizing the layout
})

var lowStockTable = $('#lowStockDatatable').DataTable({
  processing: true,
  serverSide: true,
  scrollX: true,
  ajax: '/inventory/product/browse-low-stock-product',
  columns: [
    {
      data: 'name',
      name: 'name',
      sortable: false
    },
    {
      data: 'stock',
      name: 'stock',
      sortable: false
    },
  ],
  language: {
    lengthMenu: 'Tampilkan _MENU_ data',
    zeroRecords: 'Semua Stok Barang Masih Aman 👌',
    info: 'Halaman _PAGE_ dari _PAGES_',
    infoEmpty: 'Data tidak ditemukan ',
    infoFiltered: '(Difilter dari _MAX_ data)'
  },
  dom: '<"row"tr<"px-4 my-1 col-md-6"i><"px-4 mt-1 mb-3 col-md-6"p>>' // Customizing the layout
});
