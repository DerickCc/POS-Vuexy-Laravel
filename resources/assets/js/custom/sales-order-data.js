function formatToCurrency(id) {
  new Cleave($(id), {
    numeral: true,
    numeralDecimalMark: ',',
    delimiter: '.',
    numeralThousandsGroupStyle: 'thousand',
    numeralDecimalScale: 2
  });
}

$('#customerId').select2({
  // minimumInputLength: 1,
  placeholder: 'Pilih Customer',
  dropdownParent: $('#customerId').parent(),
  ajax: {
    url: '/master/customer/get-customer-list',
    processResults: function (data) {
      return {
        results: data.map(function (item) {
          return {
            id: item.id,
            text: item.name
          };
        })
      };
    }
  },
  language: {
    noResults: function () {
      return 'Customer tidak ditemukan...';
    }
  }
});

var soTable = $('#soDatatable').DataTable({
  processing: true,
  serverSide: true,
  scrollX: true,
  scrollY: '415px',
  ajax: '/transaction/sales-order/browse-so',
  columns: [
    {
      data: 'action',
      name: 'action',
      sortable: false
    },
    {
      data: 'so_code',
      name: 'so_code'
    },
    {
      data: 'created_by',
      name: 'created_by'
    },
    {
      data: 'sales_date',
      name: 'sales_date',
      render: function (data, type, row) {
        return moment(data).format('DD-MM-YYYY HH:mm:ss');
      }
    },
    {
      data: 'customer',
      name: 'customer'
    },
    {
      data: 'sub_total',
      name: 'sub_total',
      render: function (data, type, row) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return `<span>Rp ${formattedPrice}</span>`;
      }
    },
    {
      data: 'discount',
      name: 'discount',
      render: function (data, type, row) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return `<span>Rp ${formattedPrice}</span>`;
      }
    },
    {
      data: 'grand_total',
      name: 'grand_total',
      render: function (data, type, row) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return `<span>Rp ${formattedPrice}</span>`;
      }
    },
    {
      data: 'paid_amount',
      name: 'paid_amount',
      render: function (data, type, row) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return `<span>Rp ${formattedPrice}</span>`;
      }
    },
    {
      data: 'status',
      name: 'status',
      render: function (data, type, row) {
        const color = {
          'Belum Lunas': 'info',
          Lunas: 'success',
          Batal: 'danger'
        };
        return `<span class="d-flex justify-content-center badge rounded-pill bg-label-${color[data]}">${data}</span>`;
      }
    },
    {
      data: 'remarks',
      name: 'remarks',
      sortable: false
    }
  ],
  order: [1, 'desc'],
  language: {
    lengthMenu: 'Tampilkan _MENU_ data',
    zeroRecords: 'Data tidak ditemukan...',
    info: 'Halaman _PAGE_ dari _PAGES_',
    infoEmpty: 'Data tidak ditemukan ',
    infoFiltered: '(Difilter dari _MAX_ data)'
  },
  dom: '<"row"<"px-4 my-2 col-12"l>tr<"px-4 my-1 col-md-6"i><"px-4 mt-1 mb-3 col-md-6"p>>' // Customizing the layout
});

var addButton = $('<a class="btn btn-primary float-end" href="sales-order/create">Tambah</a>');
$('.dataTables_length').append(addButton);

if ($('#startDate') && $('#endDate')) {
  $('#startDate').flatpickr({
    dateFormat: 'd-m-Y'
  });
  $('#endDate').flatpickr({
    dateFormat: 'd-m-Y'
  });
}

$('input.dt-input, select.dt-input').on('keyup change', function () {
  soTable.column($(this).attr('data-column')).search($(this).val()).draw();
});

$('input.dt-date-input').on('change', function () {
  if ($('#startDate').val() != '' && $('#endDate').val() != '') {
    const dateFilter = $('#startDate').val() + ',' + $('#endDate').val();

    soTable.column(2).search(dateFilter).draw();
  }
});

// Cancel
$(document).on('click', '.cancel-so', function (e) {
  e.preventDefault();
  const action = $(this).attr('href');
  console.log(action);
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Transaksi yang telah dibatalkan tidak dapat dikembalikan lagi!',
    icon: 'warning',
    confirmButtonText: 'Ya, batalkan!',
    customClass: {
      confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
      cancelButton: 'btn btn-label-secondary waves-effect waves-light'
    }
  }).then(confirm => {
    // if confirm
    if (confirm.isConfirmed) {
      $.ajax({
        url: action,
        type: 'PUT',
        headers: {
          'X-CSRF-TOKEN': csrf_token
        },
        success: function (res) {
          Swal.fire({
            title: 'Success',
            text: 'Transaksi Berhasil Dibatalkan',
            icon: 'success',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });

          // refresh table after successful delete
          const pageInfo = soTable.page.info();
          const sortInfo = soTable.order();
          const searchValue = soTable.search();

          soTable.ajax.reload(function () {
            // restore previous filter, sort, search
            soTable.page(pageInfo.page).draw(false);
            soTable.order(sortInfo).draw(false);
            soTable.search(searchValue).draw(false);
          });
        },
        error: function (xhr, status, e) {
          Swal.fire({
            title: 'Error',
            text: e,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });
        }
      });
    }
  });
});

// Modal Payment
var soId = 0;

$(document).on('click', '.payment-modal', function () {
  soId = $(this).data('id');
  var soCodeM = $(this).data('so-code');
  var grandTotalM = $(this).data('grand-total');
  var paidAmountM = $(this).data('paid-amount');

  $('#soCodeM').val(soCodeM);
  $('#grandTotalM').val(grandTotalM);
  $('#paidAmountM').val(paidAmountM);
  $('#shouldBePaidM').val(grandTotalM - paidAmountM);

  formatToCurrency('#grandTotalM');
  formatToCurrency('#paidAmountM');
  formatToCurrency('#shouldBePaidM');
  formatToCurrency('#paymentAmountM');

  $('#paymentAmountM').on('keyup change', function () {
    const paymentAmount = $(this).val().replace(/\./g, '');

    if (paymentAmount > grandTotalM - paidAmountM) {
      $('#paymentAmountM').val($('#shouldBePaidM').val());
      return;
    } else if (paymentAmount < 0) {
      $('#paymentAmountM').val(0);
      return;
    }

    $('#paymentLeftM').val(grandTotalM - paidAmountM - paymentAmount);
    formatToCurrency('#paymentLeftM');
  });

  // Show the payment modal
  $('#paymentModal').modal('show');
});

$('#submitPaymentModalBtn').on('click', function () {
  const paidAmount = $('#paymentAmountM').val().replace(/\./g, '');

  Swal.fire({
    title: 'Cek Sekali Lagi!',
    text: 'Pastikan jumlah yang dibayarkan telah sesuai.',
    icon: 'warning',
    confirmButtonText: 'Simpan',
    customClass: {
      confirmButton: 'btn btn-warning me-3 waves-effect waves-light',
      cancelButton: 'btn btn-label-secondary waves-effect waves-light'
    }
  }).then(confirm => {
    // if confirm
    if (confirm.isConfirmed) {
      const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      $.ajax({
        url: `/transaction/sales-order/${soId}/update-paid-amount`,
        type: 'PUT',
        headers: {
          'X-CSRF-TOKEN': csrf_token
        },
        data: {
          paid_amount: paidAmount
        },
        success: function (res) {
          $('#paymentModal').modal('hide');

          Swal.fire({
            title: 'Success',
            text: 'Pembayaran Berhasil Disimpan',
            icon: 'success',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });

          // refresh table after successfully saved
          const pageInfo = soTable.page.info();
          const sortInfo = soTable.order();
          const searchValue = soTable.search();

          soTable.ajax.reload(function () {
            // restore previous filter, sort, search
            soTable.page(pageInfo.page).draw(false);
            soTable.order(sortInfo).draw(false);
            soTable.search(searchValue).draw(false);
          });
        },
        error: function (xhr, status, e) {
          Swal.fire({
            title: 'Error',
            text: e,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });
        }
      });
    }
  });
});

$('#paymentModal').on('hidden.bs.modal', function () {
  $('#paymentAmountM').val(0);
  $('#paymentLeftM').val(0);
  soId = 0;
  console.log('hidden');
});
