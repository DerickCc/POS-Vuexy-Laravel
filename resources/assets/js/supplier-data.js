$(function () {
  $('#supplier-datatable').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    ajax: '/supplier/get-data',
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
        data: 'address',
        name: 'address'
      },
      {
        data: 'phone_no',
        name: 'phone_no'
      },
      {
        data: 'remarks',
        name: 'remarks'
      }
    ],
    dom: '<"row"<"px-4 my-2 col-12"l>>tip' // Customizing the layout
  });

  var addButton = $('<a class="btn btn-success float-end" href="supplier/create">Tambah</a>');
  $('.dataTables_length').append(addButton);
});
