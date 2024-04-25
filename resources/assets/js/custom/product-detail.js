function formatToCurrency(id) {
  new Cleave($(id), {
    numeral: true,
    numeralDecimalMark: ',',
    delimiter: '.',
    numeralThousandsGroupStyle: 'thousand',
    numeralDecimalScale: 2
  });
}

$('#restockThreshold').on('keyup', function () {
  if ($('#restockThreshold').val() < 0) {
    $('#restockThreshold').val(0.0);
  }
})

if ($('#purchasePrice')) {
  formatToCurrency($('#purchasePrice'));
}

$('#purchasePrice').on('keyup', function () {
  if ($('#purchasePrice').val() < 0) {
    $('#purchasePrice').val(0);
  }
})

if ($('#sellingPrice')) {
  formatToCurrency($('#sellingPrice'));
}

$('#sellingPrice').on('keyup', function () {
  if ($('#sellingPrice').val() < 0) {
    $('#sellingPrice').val(0);
  }
})

$('#photo').on('change', function() {
  if (this.files && this.files[0]) {
    const reader = new FileReader();

    reader.onload = function(e) {
      $('#previewImage').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);
  }
})
