var prdTable = $('#productDatatable').DataTable({
  processing: true,
  serverSide: true,
  scrollX: true,
  ajax: '/inventory/product/browse-product',
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
        const imgPath = data ? `../storage/${data}` : '../assets/img/illustrations/image-placeholder.png';
        return `
          <img
            style="width: 100px; height: 100px"
            class="img-fluid rounded"
            src="${imgPath}" />
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
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
        return `<span>Rp ${formattedPrice}</span>`;
      }
    },
    {
      data: 'selling_price',
      name: 'selling_price',
      render: function (data, type, row) {
        const formattedPrice = parseFloat(data).toLocaleString('id-ID');
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

var addButton = $('<a class="btn btn-primary float-end" href="product/create">Tambah</a>');
$('.dataTables_length').append(addButton);

$('input.dt-input').on('keyup', function () {
  prdTable.column($(this).attr('data-column')).search($(this).val()).draw();
});

$('#stockOperatorList').on('click', function (event) {
  const operator = event.target.textContent;
  if (operator == '<' || operator == '>' || operator == '=') {
    $('#stockOperatorBtn').text(event.target.textContent);
    filterStock();
  }
});

$('input.dt-input-stock').on('keyup change', filterStock);

// filter stock
function filterStock() {
  const input = $('input.dt-input-stock').val();
  const operator = $('#stockOperatorBtn').text();
  console.log(operator + ' ' + input);

  // if is number
  if (!isNaN(input) && input != 'e') {
    console.log(operator + ' ' + input);
    prdTable
      .column($('input.dt-input-stock').attr('data-column'))
      .search(operator + ' ' + input)
      .draw();
  }
  // if empty string
  else if (input == '') {
    prdTable.column($('input.dt-input-stock').attr('data-column')).search(null).draw();
  }
}
