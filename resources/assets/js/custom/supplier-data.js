var supTable = $('#supplierDatatable').DataTable({
  processing: true,
  serverSide: true,
  ajax: '/master/supplier/browse-supplier',
  columns: [
    {
      data: 'action',
      name: 'action',
      sortable: false
    },
    {
      data: 'code',
      name: 'code'
    },
    {
      data: 'name',
      name: 'name'
    },
    {
      data: 'pic',
      name: 'pic'
    },
    {
      data: 'phone_no',
      name: 'phone_no',
      sortable: false
    },
    {
      data: 'address',
      name: 'address'
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

var addButton = $('<a class="btn btn-primary float-end" href="supplier/create">Tambah</a>');
$('.dataTables_length').append(addButton);

$('input.dt-input').on('keyup', function () {
  supTable.column($(this).attr('data-column')).search($(this).val()).draw();
});
