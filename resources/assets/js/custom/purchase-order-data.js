var poTable = $('#purchaseOrderDatatable').DataTable({
  processing: true,
  serverSide: true,
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
      name: 'purchase_date'
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
      data: 'total_price',
      name: 'total_price'
    },
    {
      data: 'status',
      name: 'status'
    },
    {
      data: 'remarks',
      name: 'remarks',
      sortable: false,
    },
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

// $('input.dt-input').on('keyup', function () {
//   cusTable.column($(this).attr('data-column')).search($(this).val()).draw();
// });
