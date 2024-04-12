var rowIndex = 0;
const table = document.getElementById('PODetailTable').getElementsByTagName('tbody')[0];
var deletedDetail = [];

function formatToCurrency(id) {
  new Cleave($(id), {
    numeral: true,
    numeralDecimalMark: ',',
    delimiter: '.',
    numeralThousandsGroupStyle: 'thousand',
    numeralDecimalScale: 2
  });
}

function calculateGrandTotal() {
  // grand total
  var grandTotal = 0;
  for (let i = 1; i <= rowIndex; i++) {
    grandTotal += parseInt($(`#prdTotal${i}`).val()?.replace(/\./g, '')) || 0;
  }
  $('#grandTotal').val(grandTotal);
  formatToCurrency(`#grandTotal`);
}

// On Init
$(function () {
  const poData = edit || view;
  console.log(poData);
  if (poData) {
    // set po code
    $('#poCode').val(poData.po_code);

    // set supplier
    $('#supplierId').append(`<option value='${poData.supplier_id.id}'>${poData.supplier_id.name}</option>`);

    // select the supplier
    $('#supplierId').val(poData.supplier_id.id).trigger('change');

    // set date
    const datetime = poData.purchase_date.split(' ');
    $('#purchaseDate').val(datetime.join('T'));

    // set disabled, readonly, and hidden if view
    if (view) {
      $('#supplierId').prop('disabled', true);
      $('#remarks').prop('readonly', true);
    }

    // set detail barang
    for (let i = 0; i < poData.po_detail.length; i++) {
      const po_detail = poData.po_detail[i];
      console.log(po_detail);

      addProductRow();

      // set po detail Id
      $(`#poDetailId${i + 1}`).val(po_detail.id);

      // set product
      $(`#prdId${i + 1}`).append(`<option value='${po_detail.product_id.id}'>${po_detail.product_id.name}</option>`);

      // set Uom
      $(`#prdUom${i + 1}`).val(po_detail.product_id.uom);

      // select the product
      $(`#prdId${i + 1}`)
        .val(po_detail.product_id.id)
        .trigger('change');

      // set the purchase price
      $(`#prdPurchasePrice${i + 1}`).val(po_detail.purchase_price);
      formatToCurrency($(`#prdPurchasePrice${i + 1}`));

      // set the quantity
      $(`#prdQuantity${i + 1}`).val(po_detail.quantity);

      // set the total
      $(`#prdTotal${i + 1}`).val(po_detail.total_price);
      formatToCurrency($(`#prdTotal${i + 1}`));

      // set disabled, readonly, and hidden if view
      if (view) {
        $(`#prdId${i + 1}`).prop('disabled', true);
        $(`#prdPurchasePrice${i + 1}`).prop('readonly', true);
        $(`#prdQuantity${i + 1}`).prop('readonly', true);

        $(`#deleteProductRow${i + 1}`).removeClass('cursor-pointer');
        $(`#deleteProductRow${i + 1}`).addClass('text-muted');
      }
    }

    // set grand total
    $('#grandTotal').val(poData.grand_total);
    formatToCurrency($('#grandTotal'));
  } else {
    // Get the current date
    let currentDate = new Date();
    currentDate.setTime(currentDate.getTime() + 7 * 60 * 60 * 1000); // Adjust for Jakarta timezone (UTC+7)
    const formattedDate = currentDate.toISOString().split('.')[0];
    console.log(formattedDate);
    $('#purchaseDate').val(formattedDate);

    addProductRow();
  }
});

// Page
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

$('#supplierId').on('change', function () {
  if ($(this).val() != null) {
    $('#supplierId').removeClass('invalid');
  }
});

function addProductRow() {
  rowIndex += 1;

  const idx = rowIndex;
  const newRow = table.insertRow();

  // Add cells to the new row
  const actionCell = newRow.insertCell(0); // Aksi
  const productCell = newRow.insertCell(1); // Product
  const purchasePriceCell = newRow.insertCell(2); // Purchase Price
  const quantityCell = newRow.insertCell(3); // Quantity
  const uomCell = newRow.insertCell(4); // Uom
  const totalCell = newRow.insertCell(5); // Total

  // Set the content of each cell
  actionCell.innerHTML = `
    <span id="deleteProductRow${idx}" class="d-flex justify-content-center cursor-pointer text-danger" title="Hapus"><i class="ti ti-trash ti-sm"></i></span>
    <input id="poDetailId${idx}" name="id" value="0" hidden />
  `;
  productCell.innerHTML = `<select id="prdId${idx}" name="product_id" class="select2 form-select">`;
  purchasePriceCell.innerHTML = `
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input id="prdPurchasePrice${idx}" name="purchase_price" value="0" class="form-control" />
    </div>
  `;
  quantityCell.innerHTML = `<input id="prdQuantity${idx}" name="quantity" value="0" class="form-control" type="number" min="0" />`;
  uomCell.innerHTML = `<input id="prdUom${idx}" name="uom" class="form-control" readonly />`;
  totalCell.innerHTML = `
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input id="prdTotal${idx}" name="total_price" value="0" class="form-control" readonly  />
    </div>  
  `;

  $(`#prdId${idx}`).select2({
    // minimumInputLength: 2,
    placeholder: 'Pilih Barang',
    dropdownParent: $(`#prdId${idx}`).parent(),
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

  // action
  $(`#deleteProductRow${idx}`).on('click', function () {
    // can delete only if not view
    if (!view) {
      // add to deletedDetail if id != 0
      const poDetailId = $(`#poDetailId${idx}`).val();
      if (poDetailId != 0) deletedDetail.push(poDetailId);

      $(this).closest('tr').remove();
      calculateGrandTotal();
    }
  });

  // product
  $(`#prdId${idx}`).on('select2:selecting', function (e) {
    // validate if selected product is already selected by other
    const selectedProductId = e.params.args.data.id;

    for (var i = 1; i <= table.rows.length; i++) {
      const id = $(`#prdId${i}`).val();

      if (selectedProductId == id) {
        toastr.warning('Produk ini telah dipilih, silakan memilih produk lain.', 'Peringatan', { timeOut: 2500 });
        return false;
      }
    }
  });

  $(`#prdId${idx}`).on('select2:select', function (e) {
    const item = e.params.data;

    $(`#prdPurchasePrice${idx}`).val(item.purchase_price);
    formatToCurrency(`#prdPurchasePrice${idx}`);
    $(`#prdQuantity${idx}`).val(0);
    $(`#prdTotal${idx}`).val(0);
    $(`#prdUom${idx}`).val(item.uom);
  });

  // purchase price
  if (`#prdPurchasePrice${idx}`) {
    formatToCurrency(`#prdPurchasePrice${idx}`);
  }

  // purchase price & quantity
  $(`#prdPurchasePrice${idx}, #prdQuantity${idx}`).on('keyup change', function () {
    const formattedPurchasePrice = $(`#prdPurchasePrice${idx}`).val().replace(/\./g, '');
    const purchasePrice = formattedPurchasePrice || 0;
    const quantity = parseFloat($(`#prdQuantity${idx}`).val()) || 0;

    $(`#prdTotal${idx}`).val(purchasePrice * quantity);
    formatToCurrency(`#prdTotal${idx}`);

    calculateGrandTotal();
  });
}

$('#addProductRowBtn').on('click', function () {
  addProductRow();
});

$('#submitBtn').on('click', function () {
  if ($('#supplierId').val() == null) {
    $('#supplierId').addClass('invalid');
    return;
  }

  const data = $('#POForm').serializeArray();
  const detailData = [];
  var valid = true;

  // get data from PODetailTable body
  $('#PODetailTable tbody tr').each(function () {
    const detailRow = {};
    $(this)
      .find('input, select')
      .each(function () {
        detailRow[$(this).attr('name')] = $(this).val();
      });
    detailData.push(detailRow);
  });
  data.push({ name: 'po_detail', value: detailData });

  // get data from PODetailTable footer
  const grandTotal = $('#grandTotal').val().replace(/\./g, '');
  data.push({ name: 'grand_total', value: grandTotal });

  // get deletedDetail if edit
  if (edit) {
    data.push({ name: 'deleted_detail', value: deletedDetail });
  }

  // format data
  const formattedData = {};
  data.forEach(function (item) {
    // validation
    if (item.name == 'po_detail') {
      item.value.forEach(subItem => {
        if (
          subItem.productId === null ||
          subItem.purchase_price === '' ||
          subItem.quantity === '0' ||
          subItem.quantity === ''
        ) {
          valid = false;
          return;
        }
        subItem.purchase_price = subItem.purchase_price.replace(/\./g, '');
        subItem.total_price = subItem.total_price.replace(/\./g, '');
      });
    } else {
      if (item.name == 'supplier_id' && item.value == null) {
        toastr.warning('Supplier harus diisi!', 'Peringatan', { timeOut: 2500 });
        valid = false;
        return;
      }
    }

    formattedData[item.name] = item.value;
  });

  if (valid) {
    fetch($('#POForm').attr('action'), {
      method: edit ? 'PUT' : 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(formattedData)
    })
      .then(res => {
        if (res.ok) {
          Swal.fire({
            title: 'Success',
            text: 'Data Berhasil Disimpan',
            icon: 'success',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          }).then(() => {
            window.location.href = '/transaction/purchase-order';
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: 'Data Gagal Disimpan',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });
        }
      })
      .catch(e => {
        Swal.fire({
          title: 'Error',
          text: e,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary me waves-effect waves-light'
          }
        });
      });
  } else {
    toastr.warning('Barang, Harga Beli, dan Kuantitas barang harus diisi.', 'Perhatian', 3500);
  }
});
