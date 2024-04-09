var userTable = $('#userDatatable').DataTable({
  processing: true,
  serverSide: true,
  ajax: '/settings/user/browse-user',
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
      data: 'username',
      name: 'username',
      sortable: false
    },
    {
      data: 'name',
      name: 'name',
      sortable: false
    },
    {
      data: 'role',
      name: 'role',
      sortable: false
    },
    {
      data: 'account_status',
      name: 'account_status',
      sortable: false,
      render: function (data, type, row) {
        if (data == 1) {
          return '<span class="badge rounded-pill bg-success">Aktif</span>';
        } else {
          return '<span class="badge rounded-pill bg-danger">Tidak Aktif</span>';
        }
      }
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

var addButton = $('<a class="btn btn-primary float-end" href="user/create">Tambah</a>');
$('.dataTables_length').append(addButton);

$('input.dt-input').on('keyup', function () {
  userTable.column($(this).attr('data-column')).search($(this).val()).draw();
});
