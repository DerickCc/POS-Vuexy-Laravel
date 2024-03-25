$(function () {
  var prdTable = $('#product-datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/inventory/product/get-data',
    columns: [
      {
        data: 'action',
        name: 'action',
        sortable: false
      },
      {
        data: 'id',
        name: 'id',
        visible: false
      },
      {
        data: 'photo',
        name: 'photo',
        sortable: false,
        render: function (data, type, row) {
          return `
            <img
              style="max-width: 100px; min-width: 100px"
              class="img-fluid rounded" 
              src="${data ? data : '../assets/img/illustrations/image-placeholder.png'}" />
          `;
        }
      },
      {
        data: 'name',
        name: 'name'
      },
      {
        data: 'stock',
        name: 'stock'
      },
      {
        data: 'uom',
        name: 'uom'
      },
      {
        data: 'purchase_price',
        name: 'purchase_price',
        render: function (data, type, row) {
          const formattedPrice = parseFloat(data).toLocaleString('en-US');
          return `<span>Rp ${formattedPrice}</span>`;
        }
      },
      {
        data: 'selling_price',
        name: 'selling_price',
        render: function (data, type, row) {
          const formattedPrice = parseFloat(data).toLocaleString('en-US');
          return `<span>Rp ${formattedPrice}</span>`;
        }
      },
      {
        data: 'remarks',
        name: 'remarks'
      }
    ],
    order: [[1, 'desc']],
    language: {
      lengthMenu: 'Tampilkan _MENU_ data',
      zeroRecords: 'Data tidak ditemukan...',
      info: 'Halaman _PAGE_ dari _PAGES_',
      infoEmpty: 'Data tidak ditemukan ',
      infoFiltered: '(Difilter dari _MAX_ data)'
    },
    dom: '<"row"<"px-4 my-2 col-12"l>tr<"px-4 my-1 col-md-6"i><"px-4 mt-1 mb-3 col-md-6"p>>' // Customizing the layout
  });

  var addButton = $('<a class="btn btn-success float-end" href="product/create">Tambah</a>');
  $('.dataTables_length').append(addButton);

  $('input.dt-input').on('keyup', function () {
    prdTable.column($(this).attr('data-column')).search($(this).val()).draw();
  });
});
