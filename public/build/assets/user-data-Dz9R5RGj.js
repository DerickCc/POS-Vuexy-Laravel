var e=$("#userDatatable").DataTable({processing:!0,serverSide:!0,scrollX:!0,ajax:"/settings/user/browse-user",columns:[{data:"action",name:"action",sortable:!1},{data:"id",name:"id",visible:!1},{data:"username",name:"username",sortable:!1},{data:"name",name:"name",sortable:!1},{data:"role",name:"role",sortable:!1},{data:"account_status",name:"account_status",sortable:!1,render:function(a,n,r){return a==1?'<span class="badge rounded-pill bg-success">Aktif</span>':'<span class="badge rounded-pill bg-danger">Tidak Aktif</span>'}}],order:[[1,"desc"]],language:{lengthMenu:"Tampilkan _MENU_ data",zeroRecords:"Data tidak ditemukan...",info:"Halaman _PAGE_ dari _PAGES_",infoEmpty:"Data tidak ditemukan ",infoFiltered:"(Difilter dari _MAX_ data)"},dom:'<"row"<"px-4 my-2 col-12"l>tr<"px-4 my-1 col-md-6"i><"px-4 mt-1 mb-3 col-md-6"p>>'}),t=$('<a class="btn btn-primary float-end" href="user/create">Tambah</a>');$(".dataTables_length").append(t);$("input.dt-input").on("keyup",function(){e.column($(this).attr("data-column")).search($(this).val()).draw()});
