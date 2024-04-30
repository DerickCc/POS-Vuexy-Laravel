import ExcelJS from 'exceljs';
import { Workbook, Worksheet } from 'exceljs';
import fs from 'file-saver';

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
      data: 'created_by',
      name: 'created_by'
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

var exportAndAddButton = $(`
  <a class="btn btn-primary float-end ms-3" href="sales-order/create">Tambah</a>
  <a class="${userData.role == 'Admin' ? 'btn btn-success' : ''} float-end export" href="sales-order/export" ${userData.role == 'Admin' ? '' : 'hidden'}>Export</a>
`);

$('.dataTables_length').append(exportAndAddButton);

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

// export
$('.export').on('click', function (e) {
  e.preventDefault();
  const action = $(this).attr('href');
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  $.ajax({
    url: action,
    type: 'GET',
    data: {
      so_code: $('#soCode').val(),
      customer_id: $('#customerId').val(),
      start_date: $('#startDate').val(),
      end_date: $('#endDate').val(),
      status: $('#status').val()
    },
    headers: {
      'X-CSRF-TOKEN': csrf_token
    },
    success: function (res) {
      console.log(res);

      const reportDate =
        $('#startDate').val() && $('#endDate').val() ? $('#startDate').val() + ' - ' + $('#endDate').val() : '';

      const title = 'Laporan Transaksi Penjualan ' + (reportDate ? `(${reportDate})` : '');

      const wb = new Workbook();
      const ws = wb.addWorksheet(title);

      // title
      ws.addRow([title]).eachCell(cell => {
        cell.font = {
          size: 16,
          bold: true,
          underline: true
        };
      });

      ws.addRow([]);

      // headers
      const headerRow = ws.addRow([
        'No. Invoice',
        'Tanggal Penjualan',
        'Pelanggan',
        'Jenis Pembayaran',
        'Sub Total',
        'Diskon',
        'Grand Total',
        'Telah Dibayar',
        'Status',
        'Barang / Jasa',
        'Harga Jual',
        'Qty',
        'Total Harga',
        'Keuntungan'
      ]);

      headerRow.font = { bold: true, size: 12 };
      headerRow.alignment = {
        horizontal: 'center',
        vertical: 'middle'
      };
      headerRow.eachCell(cell => {
        cell.border = {
          top: { style: 'thin' },
          bottom: { style: 'thin' },
          left: { style: 'thin' },
          right: { style: 'thin' }
        };
        cell.fill = {
          type: 'pattern',
          pattern: 'solid',
          fgColor: { argb: 'FFFFFF00' },
          bgColor: { argb: 'FF0000FF' }
        };
      });

      res.forEach((so, i) => {
        // so row
        ws.addRow([
          so.so_code,
          so.sales_date,
          so.customer_name,
          so.payment_type,
          'Rp ' + so.sub_total.toLocaleString('id-ID'),
          'Rp ' + so.discount.toLocaleString('id-ID'),
          'Rp ' + so.grand_total.toLocaleString('id-ID'),
          'Rp ' + so.paid_amount.toLocaleString('id-ID'),
          so.status,
          '',
          '',
          '',
          '',
          ''
        ]).eachCell((cell, colNum) => {
          if (i % 2 == 0) {
            cell.fill = {
              type: 'pattern',
              pattern: 'solid',
              fgColor: { argb: 'fff2f2f2' }
            };
          } else {
            cell.fill = {
              type: 'pattern',
              pattern: 'solid',
              fgColor: { argb: 'ffeeece1' }
            };
          }

          if (colNum < 10) {
            cell.border = {
              top: { style: 'thin' },
              bottom: { style: 'thin' },
              left: { style: 'thin' },
              right: { style: 'thin' }
            };
          }
          // border for the rightmost side of table
          if (colNum == 14) {
            cell.border = {
              right: { style: 'thin' }
            };
          }
        });

        // product detail rows
        so.so_product_detail.forEach((detail, j) => {
          ws.addRow([
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            detail.product_name,
            'Rp ' + detail.selling_price.toLocaleString('id-ID'),
            detail.quantity + ' ' + detail.product_uom,
            'Rp ' + detail.total_price.toLocaleString('id-ID'),
            'Rp ' + detail.profit.toLocaleString('id-ID')
          ]).eachCell((cell, colNum) => {
            if (i % 2 == 0) {
              cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'fff2f2f2' }
              };
            } else {
              cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'ffeeece1' }
              };
            }

            if (colNum >= 10) {
              cell.border = {
                top: { style: 'thin' },
                bottom: { style: 'thin' },
                left: { style: 'thin' },
                right: { style: 'thin' }
              };
            }
            // border for the bottom of table
            else if (colNum < 10 && i == res.length - 1 && j == so.so_product_detail.length - 1 && so.so_service_detail.length == 0) {
              cell.border = {
                bottom: { style: 'thin' }
              };
            }
          });
        });

        // service detail rows
        so.so_service_detail.forEach((detail, k) => {
          ws.addRow([
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            detail.service_name,
            'Rp ' + detail.selling_price.toLocaleString('id-ID'),
            detail.quantity,
            'Rp ' + detail.total_price.toLocaleString('id-ID'),
            'Rp ' + detail.total_price.toLocaleString('id-ID')
          ]).eachCell((cell, colNum) => {
            if (i % 2 == 0) {
              cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'fff2f2f2' }
              };
            } else {
              cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'ffeeece1' }
              };
            }

            if (colNum >= 10) {
              cell.border = {
                top: { style: 'thin' },
                bottom: { style: 'thin' },
                left: { style: 'thin' },
                right: { style: 'thin' }
              };
            }
            // border for the bottom of table
            else if (colNum < 10 && i == res.length - 1 && k == so.so_service_detail.length - 1) {
              cell.border = {
                bottom: { style: 'thin' }
              };
            }
          });
        });
      });

      ws.getColumn(1).width = 13;
      ws.getColumn(2).width = 20;
      ws.getColumn(3).width = 20;
      ws.getColumn(4).width = 18;
      ws.getColumn(5).width = 15;
      ws.getColumn(6).width = 15;
      ws.getColumn(7).width = 15;
      ws.getColumn(8).width = 15;
      ws.getColumn(9).width = 11;
      // detail
      ws.getColumn(10).width = 20;
      ws.getColumn(11).width = 15;
      ws.getColumn(12).width = 12;
      ws.getColumn(13).width = 15;
      ws.getColumn(14).width = 15;

      wb.xlsx.writeBuffer().then(buffer => {
        const data = new Blob([buffer], {
          type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });
        fs.saveAs(data, title + '.xlsx');
      });
    },
    error: function (xhr, status, e) {
      Swal.fire({
        title: 'Error',
        text: 'Export Gagal: ' + e,
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary me waves-effect waves-light'
        }
      });
    }
  });
});
