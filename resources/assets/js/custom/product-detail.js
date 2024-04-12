function formatToCurrency(id) {
  new Cleave($(id), {
    numeral: true,
    numeralDecimalMark: ',',
    delimiter: '.',
    numeralThousandsGroupStyle: 'thousand',
    numeralDecimalScale: 2
  });
}

if ($('#purchase_price')) {
  formatToCurrency($('#purchase_price'));
}

if ($('#selling_price')) {
  formatToCurrency($('#selling_price'));
}

$('#photo').on('change', function() {
  if (this.files && this.files[0]) {
    const reader = new FileReader();

    reader.onload = function(e) {
      $('#previewImage').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);
  }
})
