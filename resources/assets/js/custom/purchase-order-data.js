import ExcelJS from 'exceljs';
import { Workbook, Worksheet } from 'exceljs';
import fs from 'file-saver';

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

var exportAndAddButton = $(`
  <a class="btn btn-primary float-end ms-3" href="purchase-order/create">Tambah</a>
  <a class="btn btn-success float-end export" href="purchase-order/export">Export</a>
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
  poTable.column($(this).attr('data-column')).search($(this).val()).draw();
});

$('input.dt-date-input').on('change', function () {
  if ($('#startDate').val() != '' && $('#endDate').val() != '') {
    const dateFilter = $('#startDate').val() + ',' + $('#endDate').val();

    poTable.column(2).search(dateFilter).draw();
  }
});

// finish po
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

// export
$('.export').on('click', function (e) {
  e.preventDefault();
  const action = $(this).attr('href');
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  $.ajax({
    url: action,
    type: 'GET',
    data: {
      po_code: $('#poCode').val(),
      supplier_id: $('#supplierId').val(),
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
        'Kode PO',
        'Tanggal Pembelian',
        'Supplier',
        'Grand Total',
        'Item',
        'Status',
        'Barang',
        'Harga Beli',
        'Qty',
        'Total Harga'
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

      res.forEach((po, i) => {
        // po row
        ws.addRow([
          po.po_code,
          po.purchase_date,
          po.supplier_name,
          'Rp ' + po.grand_total.toLocaleString('id-ID'),
          po.total_item,
          po.status,
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

          if (colNum < 7) {
            cell.border = {
              top: { style: 'thin' },
              bottom: { style: 'thin' },
              left: { style: 'thin' },
              right: { style: 'thin' }
            };
          }
          // border for the rightmost side of table
          if (colNum == 10) {
            cell.border = {
              right: { style: 'thin' }
            };
          }
        });

        // detail rows
        po.po_detail.forEach((detail, j) => {
          ws.addRow([
            '',
            '',
            '',
            '',
            '',
            '',
            detail.product_name,
            'Rp ' + detail.purchase_price.toLocaleString('id-ID'),
            detail.quantity + ' ' + detail.product_uom,
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

            if (colNum >= 7) {
              cell.border = {
                top: { style: 'thin' },
                bottom: { style: 'thin' },
                left: { style: 'thin' },
                right: { style: 'thin' }
              };
            }
            // border for the bottom of table
            else if (colNum < 7 && i == res.length - 1 && j == po.po_detail.length - 1) {
              cell.border = {
                bottom: { style: 'thin' }
              };
            }
          });
        });
      });

      ws.getColumn(1).width = 13;
      ws.getColumn(2).width = 20;
      ws.getColumn(3).width = 15;
      ws.getColumn(4).width = 15;
      ws.getColumn(5).width = 7;
      ws.getColumn(6).width = 15;
      ws.getColumn(7).width = 20;
      ws.getColumn(8).width = 15;
      ws.getColumn(9).width = 15;
      ws.getColumn(10).width = 15;

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
