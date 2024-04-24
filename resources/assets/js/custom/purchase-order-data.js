$('#supplierId').select2({
  // minimumInputLength: 1,
  placeholder: 'Pilih Supplier',
  dropdownParent: $('#supplierId').parent(),
  ajax: {
    url: '/master/supplier/get-supplier-list',
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
      return 'Supplier tidak ditemukan...';
    }
  }
});

var poTable = $('#poDatatable').DataTable({
  processing: true,
  serverSide: true,
  scrollX: true,
  scrollY: '415px',
  ajax: '/transaction/purchase-order/browse-po',
  columns: [
    {
      data: 'action',
      name: 'action',
      sortable: false
    },
    {
      data: 'po_code',
      name: 'po_code'
    },
    {
      data: 'purchase_date',
      name: 'purchase_date',
      render: function (data, type, row) {
        return moment(data).format('DD-MM-YYYY HH:mm:ss');
      }
    },
    {
      data: 'supplier',
      name: 'supplier'
    },
    {
      data: 'total_item',
      name: 'total_item'
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
      data: 'status',
      name: 'status',
      render: function (data, type, row) {
        const color = data == 'Dalam Proses' ? 'info' : 'success';
        return `<span class="d-flex justify-content-center badge rounded-pill bg-label-${color}">${data}</span>`;
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

var addButton = $('<a class="btn btn-primary float-end" href="purchase-order/create">Tambah</a>');
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
  poTable.column($(this).attr('data-column')).search($(this).val()).draw();
});

$('input.dt-date-input').on('change', function () {
  if ($('#startDate').val() != '' && $('#endDate').val() != '') {
    const dateFilter = $('#startDate').val() + ',' + $('#endDate').val();

    poTable.column(2).search(dateFilter).draw();
  }
});

$(document).on('click', '.finish-po', function (e) {
  e.preventDefault();
  const action = $(this).attr('href');
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Transaksi yang telah diselesaikan tidak dapat diedit atau dihapus lagi!',
    icon: 'warning',
    confirmButtonText: 'Ya, selesaikan!',
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
            text: 'Transaksi Berhasil Diselesaikan',
            icon: 'success',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });

          // refresh table after successful delete
          const pageInfo = poTable.page.info();
          const sortInfo = poTable.order();
          const searchValue = poTable.search();

          poTable.ajax.reload(function () {
            // restore previous filter, sort, search
            poTable.page(pageInfo.page).draw(false);
            poTable.order(sortInfo).draw(false);
            poTable.search(searchValue).draw(false);
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
