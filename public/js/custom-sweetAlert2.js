function confirmDelete(event, tableId) {
  event.preventDefault();
  const action = event.currentTarget.getAttribute('href');
  const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan lagi!',
    icon: 'warning',
    confirmButtonText: 'Ya, hapus!',
    customClass: {
      confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
      cancelButton: 'btn btn-label-secondary waves-effect waves-light'
    }
  }).then(confirm => {
    // if user choose yes
    if (confirm.isConfirmed) {
      fetch(action, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrf_token
        }
      })
        .then(res => {
          if (res.ok) {
            Swal.fire({
              title: 'Success',
              text: 'Data Berhasil Dihapus',
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-primary me waves-effect waves-light'
              }
            });

            // refresh table after successful delete
            var table = $(tableId).DataTable();
            const pageInfo = table.page.info();
            const sortInfo = table.order();
            const searchValue = table.search();

            table.ajax.reload(function () {
              // restore previous filter, sort, search
              table.page(pageInfo.page).draw(false);
              table.order(sortInfo).draw(false);
              table.search(searchValue).draw(false);
            });
          } else
            Swal.fire({
              title: 'Error',
              text: 'Data Gagal Dihapus',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary me waves-effect waves-light'
              }
            });
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
}

// alert auto disappear
setTimeout(function () {
  $('div.alert').remove();
}, 4000); // 4 secs
