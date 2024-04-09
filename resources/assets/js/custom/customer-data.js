var cusTable = $('#customerDatatable').DataTable({
  processing: true,
  serverSide: true,
  ajax: '/master/customer/browse-customer',
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
      data: 'license_plate',
      name: 'license_plate'
    },
    {
      data: 'phone_no',
      name: 'phone_no'
    },
    {
      data: 'address',
      name: 'address'
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

var addButton = $('<a class="btn btn-primary float-end" href="customer/create">Tambah</a>');
$('.dataTables_length').append(addButton);

$('input.dt-input').on('keyup', function () {
  cusTable.column($(this).attr('data-column')).search($(this).val()).draw();
});
