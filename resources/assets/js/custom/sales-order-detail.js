var productRowIndex = 0;
var serviceRowIndex = 0;
const productTable = document.getElementById('soProductDetailTable').getElementsByTagName('tbody')[0];
const serviceTable = document.getElementById('soServiceDetailTable').getElementsByTagName('tbody')[0];
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

function calculateTotalProductPrice() {
  // total product price
  var totalProductPrice = 0;
  var totalOriProductPrice = 0;
  for (let i = 1; i <= productRowIndex; i++) {
    totalProductPrice += parseInt($(`#prdTotal${i}`).val()?.replace(/\./g, '')) || 0;
    totalOriProductPrice += $(`#prdOriSellingPrice${i}`).val() * parseFloat($(`#prdQuantity${i}`).val()) || 0;
  }

  $('#totalProductPrice').val(totalProductPrice);
  formatToCurrency(`#totalProductPrice`);
}

function calculateTotalServicePrice() {
  // total service price
  var totalServicePrice = 0;
  for (let i = 1; i <= serviceRowIndex; i++) {
    totalServicePrice += parseInt($(`#svcTotal${i}`).val()?.replace(/\./g, '')) || 0;
  }
  console.log(totalServicePrice);
  $('#totalServicePrice').val(totalServicePrice);
  formatToCurrency(`#totalServicePrice`);
}

function calculatePayment() {
  var totalOriProductPrice = 0;
  for (let i = 1; i <= productRowIndex; i++) {
    totalOriProductPrice += $(`#prdOriSellingPrice${i}`).val() * parseFloat($(`#prdQuantity${i}`).val()) || 0;
  }

  var totalServicePrice = 0;
  for (let i = 1; i <= serviceRowIndex; i++) {
    totalServicePrice += parseInt($(`#svcTotal${i}`).val()?.replace(/\./g, '')) || 0;
  }

  // subTotal
  const subTotal = totalOriProductPrice + totalServicePrice;
  $('#subTotal').val(subTotal);
  formatToCurrency(`#subTotal`);

  // discount only for product
  const discount = totalOriProductPrice - parseInt($(`#totalProductPrice`).val()?.replace(/\./g, ''));
  $('#discount').val(discount);
  formatToCurrency(`#discount`);

  // grand total
  const grandTotal = subTotal - discount;
  $('#grandTotal').val(grandTotal);
  formatToCurrency(`#grandTotal`);
}

// On Init
$(function () {
  const soData = view;
  console.log(soData);

  if (soData) {
    // set customer
    $('#customerId').append(`<option value='${soData.customer_id.id}'>${soData.customer_id.name}</option>`);

    // set date
    const datetime = soData.sales_date.split(' ');
    $('#salesDate').val(datetime.join('T'));

    // set disabled, readonly, and hidden
    $('#customerId').prop('disabled', true);
    $('#remarks').prop('readonly', true);
    $('#paidAmount').prop('readonly', true);
    $('input[name="payment_type"]').prop('disabled', true);
    $('input[name="payment_type"]').css('opacity', 1);

    if (soData.so_product_detail.length > 0) {
      var totalProductPrice = 0;
      // set product detail
      soData.so_product_detail.forEach((prd_detail, i) => {
        totalProductPrice += prd_detail.total_price;
        console.log(prd_detail);
        addProductRow();

        // set so product detail id
        $(`#soPrdDetail${i + 1}`).val(prd_detail.id);

        // set product
        $(`#prdId${i + 1}`).append(
          `<option value='${prd_detail.product_id.id}'>${prd_detail.product_id.name}</option>`
        );

        // set ori selling price
        $(`#prdOriSellingPrice${i + 1}`).val(prd_detail.ori_selling_price);

        // set selling price
        $(`#prdSellingPrice${i + 1}`).val(prd_detail.selling_price);
        formatToCurrency($(`#prdSellingPrice${i + 1}`));

        // set Uom
        $(`#prdUom${i + 1}`).text(prd_detail.product_id.uom);

        // set the quantity
        $(`#prdQuantity${i + 1}`).val(prd_detail.quantity);

        // set the total
        $(`#prdTotal${i + 1}`).val(prd_detail.total_price);
        formatToCurrency($(`#prdTotal${i + 1}`));

        // set disabled, readonly, and hidden
        $(`#prdId${i + 1}`).prop('disabled', true);
        $(`#prdSellingPrice${i + 1}`).prop('readonly', true);
        $(`#prdQuantity${i + 1}`).prop('readonly', true);

        $(`#deleteProductRow${i + 1}`).removeClass('cursor-pointer');
        $(`#deleteProductRow${i + 1}`).addClass('text-muted');
      });

      // set total product price
      $('#totalProductPrice').val(totalProductPrice);
    } else {
      $('#soProductDetails').hide();
    }

    if (soData.so_service_detail.length > 0) {
      var totalServicePrice = 0;
      // set service detail
      soData.so_service_detail.forEach((svc_detail, i) => {
        totalServicePrice += svc_detail.total_price;
        console.log(svc_detail);
        addServiceRow();
  
        // set so service detail id
        $(`#soSvcDetail${i + 1}`).val(svc_detail.id);
  
        // set service
        $(`#svcName${i + 1}`).val(svc_detail.service_name);
  
        // set the selling price
        $(`#svcSellingPrice${i + 1}`).val(svc_detail.selling_price);
        formatToCurrency($(`#svcSellingPrice${i + 1}`));
  
        // set the quantity
        $(`#svcQuantity${i + 1}`).val(svc_detail.quantity);
  
        // set the total
        $(`#svcTotal${i + 1}`).val(svc_detail.total_price);
        formatToCurrency($(`#svcTotal${i + 1}`));
  
        // set disabled, readonly, and hidden
        $(`#svcName${i + 1}`).prop('disabled', true);
        $(`#svcSellingPrice${i + 1}`).prop('readonly', true);
        $(`#svcQuantity${i + 1}`).prop('readonly', true);
  
        $(`#deleteServiceRow${i + 1}`).removeClass('cursor-pointer');
        $(`#deleteServiceRow${i + 1}`).addClass('text-muted');
      });
  
      // set total service price
      $('#totalServicePrice').val(totalServicePrice);
    }
    else {
      $('#soServiceDetails').hide();
    }

    // set detail service
  } else {
    // Get and set the current date
    let currentDate = new Date();
    currentDate.setTime(currentDate.getTime() + 7 * 60 * 60 * 1000); // Adjust for Jakarta timezone (UTC+7)
    const formattedDate = currentDate.toISOString().split('.')[0];
    $('#salesDate').val(formattedDate);
  }
});

// Page
// customer
$('#customerId').select2({
  // minimumInputLength: 1,
  placeholder: 'Pilih Customer',
  dropdownParent: $('#customerId').parent(),
  ajax: {
    url: '/master/customer/get-customer-list',
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
      return 'Customer tidak ditemukan...';
    }
  }
});

$('#customerId').on('change', function () {
  if ($(this).val() != null) {
    $('#customerId').removeClass('invalid');
  }
});

function addProductRow() {
  productRowIndex += 1;

  const idx = productRowIndex;
  const newRow = productTable.insertRow();

  // add cells
  const actionCell = newRow.insertCell(0); // aksi
  const productCell = newRow.insertCell(1); // product
  const sellingPriceCell = newRow.insertCell(2); // selling price
  const quantityCell = newRow.insertCell(3); // quantity
  const totalCell = newRow.insertCell(4); // total

  // Set the content of each cell
  actionCell.innerHTML = `
    <span id="deleteProductRow${idx}" class="d-flex justify-content-center cursor-pointer text-danger" title="Hapus"><i class="ti ti-trash ti-sm"></i></span>
    <input hidden id="soPrdDetailId${idx}" name="id" value="0" />
  `;
  productCell.innerHTML = `<select id="prdId${idx}" name="product_id" class="select2 form-select"></select>`;
  sellingPriceCell.innerHTML = `
    <input hidden id="prdOriSellingPrice${idx}" name="ori_selling_price" value="0" />
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input id="prdSellingPrice${idx}" name="selling_price" value="0" class="form-control" />
    </div>
  `;
  quantityCell.innerHTML = `
    <div class="d-flex align-items-center px-0">
      <input id="prdQuantity${idx}" name="quantity" value="0" class="form-control me-1 px-1" type="number" min="0" />
      <span id="prdUom${idx}"></span>
    </div>
  `;
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
      const soPrdDetailId = $(`#soPrdDetailId${idx}`).val();
      if (soPrdDetailId != 0) deletedDetail.push(soPrdDetailId);

      $(this).closest('tr').remove();
      calculateTotalProductPrice();
      calculatePayment();
    }
  });

  // product
  $(`#prdId${idx}`).on('select2:selecting', function (e) {
    // validate if selected product is already selected by other
    const selectedProductId = e.params.args.data.id;

    for (var i = 1; i <= productTable.rows.length; i++) {
      const id = $(`#prdId${i}`).val();

      if (selectedProductId == id) {
        toastr.warning('Produk ini telah dipilih, silakan memilih produk lain.', 'Peringatan', { timeOut: 2500 });
        return false;
      }
    }
  });

  $(`#prdId${idx}`).on('select2:select', function (e) {
    const item = e.params.data;

    $(`#prdOriSellingPrice${idx}`).val(item.selling_price);
    $(`#prdSellingPrice${idx}`).val(item.selling_price);
    formatToCurrency(`#prdSellingPrice${idx}`);
    $(`#prdQuantity${idx}`).val(0);
    $(`#prdTotal${idx}`).val(0);
    $(`#prdUom${idx}`).text(item.uom);
  });

  // selling price
  if (`#prdSellingPrice${idx}`) {
    formatToCurrency(`#prdSellingPrice${idx}`);
  }

  // selling price & quantity
  $(`#prdSellingPrice${idx}, #prdQuantity${idx}`).on('keyup change', function () {
    const formattedSellingePrice = $(`#prdSellingPrice${idx}`).val().replace(/\./g, '');
    const sellingPrice = formattedSellingePrice || 0;
    const quantity = parseFloat($(`#prdQuantity${idx}`).val()) || 0;

    $(`#prdTotal${idx}`).val(sellingPrice * quantity);
    formatToCurrency(`#prdTotal${idx}`);

    calculateTotalProductPrice();
    calculatePayment();

    $('#paidAmount').val(0);
    $('input[name="payment_type"][value="DP"]').prop('checked', true);
  });

  // quantity
  $(`#prdQuantity${idx}`).on('change', function () {
    const productId = $(`#prdId${idx}`).val();

    if ($(`#prdQuantity${idx}`).val() < 0) {
      $(`#prdQuantity${idx}`).val(0);
      toastr.warning(`Qty tidak boleh lebih kecil dari 0.`, 'Peringatan', { timeOut: 3500 });
      return;
    }

    if (productId) {
      const quantity = parseFloat($(`#prdQuantity${idx}`).val()) || 0;

      $.ajax({
        url: '/inventory/product/get-product-stock',
        method: 'GET',
        dataType: 'json',
        data: { id: productId },
        success: function (res) {
          if (quantity > res.stock) {
            toastr.warning(
              `Qty melebihi stok yang tersedia, ${res.name} tersisa ${res.stock} ${res.uom}`,
              'Peringatan',
              { timeOut: 4000 }
            );
            $(`#prdQuantity${idx}`).val(0).trigger('change');
          }
        },
        error: function (xhr, status, error) {
          toastr.error('Terjadi Error: ' + error, 'Error', { timeOut: 3500 });
          console.log(error);
        }
      });
    }
  });
}

$('#addProductRowBtn').on('click', function () {
  addProductRow();
});

function addServiceRow() {
  serviceRowIndex += 1;

  const idx = serviceRowIndex;
  const newRow = serviceTable.insertRow();

  // add cells
  const actionCell = newRow.insertCell(0); // aksi
  const serviceCell = newRow.insertCell(1); // service
  const sellingPriceCell = newRow.insertCell(2); // selling price
  const quantityCell = newRow.insertCell(3); // quantity
  const totalCell = newRow.insertCell(4); // total

  // Set the content of each cell
  actionCell.innerHTML = `
    <span id="deleteServiceRow${idx}" class="d-flex justify-content-center cursor-pointer text-danger" title="Hapus"><i class="ti ti-trash ti-sm"></i></span>
    <input hidden id="soSvcDetailId${idx}" name="id" value="0" />
  `;
  serviceCell.innerHTML = `<input id="svcName${idx}" name="service_name" class="form-control" />`;
  sellingPriceCell.innerHTML = `
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input id="svcSellingPrice${idx}" name="selling_price" value="0" class="form-control" />
    </div>
  `;
  quantityCell.innerHTML = `<input id="svcQuantity${idx}" name="quantity" value="0" class="form-control" type="number" min="0" />`;
  totalCell.innerHTML = `
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input id="svcTotal${idx}" name="total_price" value="0" class="form-control" readonly  />
    </div>  
  `;

  // action
  $(`#deleteServiceRow${idx}`).on('click', function () {
    // can delete only if not view
    if (!view) {
      // add to deletedDetail if id != 0
      const soSvcDetailId = $(`#soSvcDetailId${idx}`).val();
      if (soSvcDetailId != 0) deletedDetail.push(soSvcDetailId);

      $(this).closest('tr').remove();
      calculateTotalServicePrice();
      calculatePayment();
    }
  });

  // selling price
  if (`#svcSellingPrice${idx}`) {
    formatToCurrency(`#svcSellingPrice${idx}`);
  }

  // selling price & quantity
  $(`#svcSellingPrice${idx}, #svcQuantity${idx}`).on('keyup change', function () {
    const formattedSellingPrice = $(`#svcSellingPrice${idx}`).val().replace(/\./g, '');
    const sellingPrice = formattedSellingPrice || 0;
    const quantity = parseFloat($(`#svcQuantity${idx}`).val()) || 0;
    $(`#svcTotal${idx}`).val(sellingPrice * quantity);
    formatToCurrency(`#svcTotal${idx}`);

    calculateTotalServicePrice();
    calculatePayment();

    $('#paidAmount').val(0);
    $('input[name="payment_type"][value="DP"]').prop('checked', true);
  });

  // quantity
  $(`#svcQuantity${idx}`).on('change', function () {
    if ($(`#svcQuantity${idx}`).val() < 0) {
      $(`#svcQuantity${idx}`).val(0);
      toastr.warning(`Qty tidak boleh lebih kecil dari 0.`, 'Peringatan', { timeOut: 3500 });
      return;
    }
  });
}

$('#addServiceRowBtn').on('click', function () {
  addServiceRow();
});

// payment type
$('input[name="payment_type"]').on('change', function () {
  if ($(this).val() == 'Lunas') {
    $('#paidAmount').prop('readonly', true);
    $('#paidAmount').val($('#grandTotal').val());
  } else {
    $('#paidAmount').prop('readonly', false);
    $('#paidAmount').val(0);
  }
});

// sub total
if ($(`#subTotal`)) {
  formatToCurrency($(`#subTotal`));
}

// paid amount
if ($(`#paidAmount`)) {
  formatToCurrency($(`#paidAmount`));
}

$('#paidAmount').on('keyup', function () {
  const grandTotal = +$('#grandTotal').val().replace(/\./g, '');
  const paidAmount = +$(this).val().replace(/\./g, '');

  if (paidAmount >= grandTotal) {
    $(this).val($('#grandTotal').val());
    $('input[name="payment_type"][value="Lunas"]').prop('checked', true);
    $('#paidAmount').prop('readonly', true);
  }
});

$('#submitBtn').on('click', function () {
  if ($('#customerId').val() == null) {
    toastr.warning('Mohon memilih Pelanggan.', 'Peringatan', { timeOut: 2500 });
    $('#customerId').addClass('invalid');
    return;
  }

  const data = $('#soForm').serializeArray();
  const productDetailData = [];
  const serviceDetailData = [];
  var valid = true;

  // get data from soProductDetailTable body
  $('#soProductDetailTable tbody tr').each(function () {
    const detailRow = {};
    $(this)
      .find('input, select')
      .each(function () {
        detailRow[$(this).attr('name')] = $(this).val();
      });
    productDetailData.push(detailRow);
  });

  // get data from soProductDetailTable body
  $('#soServiceDetailTable tbody tr').each(function () {
    const detailRow = {};
    $(this)
      .find('input')
      .each(function () {
        detailRow[$(this).attr('name')] = $(this).val();
      });
    serviceDetailData.push(detailRow);
  });

  if (productDetailData.length == 0 && serviceDetailData == 0) {
    toastr.warning('Mohon menambahkan minimal 1 Barang/Jasa yang dijual.', 'Peringatan', { timeOut: 2500 });
    return;
  }
  data.push({ name: 'so_product_detail', value: productDetailData });

  data.push({ name: 'so_service_detail', value: serviceDetailData });

  // get grandTotal
  const grandTotal = $('#grandTotal').val().replace(/\./g, '');
  data.push({ name: 'grand_total', value: grandTotal });

  // get paymentType
  const paymentType = $('input[name="payment_type"]:checked').val();
  data.push({ name: 'payment_type', value: paymentType });

  // get subTotal
  const subTotal = $('#subTotal').val().replace(/\./g, '');
  data.push({ name: 'sub_total', value: subTotal });

  // get discount
  const discount = $('#discount').val().replace(/\./g, '');
  data.push({ name: 'discount', value: discount });

  // get paidAmount
  const paidAmount = $('#paidAmount').val().replace(/\./g, '');
  data.push({ name: 'paid_amount', value: paidAmount });

  // format data
  const formattedData = {};
  data.forEach(function (item) {
    // validation
    if (item.name == 'so_product_detail') {
      item.value.forEach(subItem => {
        if (
          subItem.productId === null ||
          subItem.selling_price === '' ||
          subItem.quantity === '0' ||
          subItem.quantity === ''
        ) {
          toastr.warning('Barang, Harga Jual, dan Qty Barang harus diisi.', 'Peringatan', { timeOut: 2500 });
          valid = false;
          return;
        }
        subItem.ori_selling_price = subItem.ori_selling_price.replace(/\./g, '');
        subItem.selling_price = subItem.selling_price.replace(/\./g, '');
        subItem.total_price = subItem.total_price.replace(/\./g, '');
      });
    } else if (item.name == 'so_service_detail') {
      item.value.forEach(subItem => {
        if (subItem.serviceName === null || subItem.quantity === '0' || subItem.quantity === '') {
          toastr.warning('Nama Jasa dan Qty Jasa harus diisi.', 'Peringatan', { timeOut: 2500 });
          valid = false;
          return;
        }
        subItem.selling_price = subItem.selling_price.replace(/\./g, '');
        subItem.total_price = subItem.total_price.replace(/\./g, '');
      });
    }

    formattedData[item.name] = item.value;
  });
  console.log(formattedData);

  if (valid) {
    fetch($('#soForm').attr('action'), {
      method: 'POST',
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
            window.location.href = '/transaction/sales-order';
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
  }
});

// Payment Modal
$(document).on('click', '#openPaymentModalBtn', function () {
  var soCodeM = view.so_code;
  var grandTotalM = view.grand_total;
  var paidAmountM = view.paid_amount;

  $('#soCodeM').val(soCodeM);
  $('#grandTotalM').val(grandTotalM);
  $('#paidAmountM').val(paidAmountM);
  $('#shouldBePaidM').val(grandTotalM - paidAmountM);

  formatToCurrency('#grandTotalM');
  formatToCurrency('#paidAmountM');
  formatToCurrency('#shouldBePaidM');
  formatToCurrency('#paymentAmountM');

  $('#paymentAmountM').on('keyup change', function () {
    const paymentAmount = $(this).val().replace(/\./g, '');

    if (paymentAmount > grandTotalM - paidAmountM) {
      $('#paymentAmountM').val($('#shouldBePaidM').val());
      return;
    } else if (paymentAmount < 0) {
      $('#paymentAmountM').val(0);
      return;
    }

    $('#paymentLeftM').val(grandTotalM - paidAmountM - paymentAmount);
    formatToCurrency('#paymentLeftM');
  });

  // Show the payment modal
  $('#paymentModal').modal('show');
});

// submit modal
$('#submitPaymentModalBtn').on('click', function () {
  Swal.fire({
    title: 'Cek Sekali Lagi!',
    text: 'Pastikan jumlah yang dibayarkan telah sesuai.',
    icon: 'warning',
    confirmButtonText: 'Simpan',
    customClass: {
      confirmButton: 'btn btn-warning me-3 waves-effect waves-light',
      cancelButton: 'btn btn-label-secondary waves-effect waves-light'
    }
  }).then(confirm => {
    // if confirm
    if (confirm.isConfirmed) {
      const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const paidAmount = $('#paymentAmountM').val().replace(/\./g, '');
      const soId = view.id;

      $.ajax({
        url: `/transaction/sales-order/${soId}/update-paid-amount`,
        type: 'PUT',
        headers: {
          'X-CSRF-TOKEN': csrf_token
        },
        data: {
          paid_amount: paidAmount
        },
        success: function (res) {
          $('#paymentModal').modal('hide');

          Swal.fire({
            title: 'Success',
            text: 'Pembayaran Berhasil Disimpan',
            icon: 'success',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          }).then(() => {
            history.back();
          });
        },
        error: function (xhr, status, e) {
          Swal.fire({
            title: 'Error',
            text: e,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary me waves-effect waves-light'
            }
          });
        }
      });
    }
  });
});
