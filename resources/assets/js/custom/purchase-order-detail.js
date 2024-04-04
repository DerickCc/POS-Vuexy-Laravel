// Page
$('#selectSupplier').select2({
  minimumInputLength: 1,
  placeholder: 'Pilih Supplier',
  dropdownParent: $('#selectSupplier').parent(),
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

// Modal
$('#selectProduct').select2({
  minimumInputLength: 2,
  placeholder: 'Pilih Barang',
  dropdownParent: $('#selectProduct').parent(),
  ajax: {
    url: '/inventory/product/get-product-list',
    processResults: function (data) {
      return {
        results: data.map(function (item) {
          return {
            id: item.id,
            text: item.name,
            code: item.code,
            uom: item.uom,
            purchase_price: item.purchase_price,
            selling_price: item.selling_price,
            remarks: item.remarks
          };
        })
      };
    }
  },
  language: {
    inputTooShort: function (args) {
      const remainingChars = args.minimum - args.input.length;
      return 'Mohon input ' + remainingChars + ' karakter lagi. ';
    },
    noResults: function () {
      return 'Barang tidak ditemukan...';
    }
  }
});

$('#selectProduct').on('select2:select', function (e) {
  const item = e.params.data;

  $('#prdPurchasePrice').val(item.purchase_price);
  $('#prdSellingPrice').val(item.selling_price);
  $('#prdUom').val(item.uom);
});

$('#productModalForm').on('submit', function (event) {
  event.preventDefault();
  // Serialize form data into an array of objects
  var formDataArray = $(this).serialize();

  // Convert the array into JSON format
  var formDataJSON = JSON.stringify(formDataArray);
  console.log(formDataJSON);
});
