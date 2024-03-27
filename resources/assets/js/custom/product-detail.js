if ($('#purchase_price')) {
  new Cleave($('#purchase_price'), {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
  });
}

if ($('#selling_price')) {
  new Cleave($('#selling_price'), {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
  });
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
