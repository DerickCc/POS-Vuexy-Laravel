$(function () {
  var supTable = $('#supplier-datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/master/supplier/get-data',
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
    dom: '<"row"<"px-4 my-2 col-12"l>tr<"px-4 my-2 col-md-6"i><"px-4 my-2 col-md-6"p>>' // Customizing the layout
  });

  var addButton = $('<a class="btn btn-success float-end" href="supplier/create">Tambah</a>');
  $('.dataTables_length').append(addButton);

  $('input.dt-input').on('keyup', function () {
    supTable.column($(this).attr('data-column')).search($(this).val()).draw();
  });
});
