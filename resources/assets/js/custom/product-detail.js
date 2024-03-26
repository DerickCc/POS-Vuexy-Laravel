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

// $('#productForm').on('submit', function() {
//   var purchase_price = $('#purchase_price').val().replace(/,/g, '');
//   $('#purchase_price').value(purchase_price);

//   var selling_price = $('#selling_price').val().replace(/,/g, '');
//   $('#selling_price').value(selling_price);
// })
