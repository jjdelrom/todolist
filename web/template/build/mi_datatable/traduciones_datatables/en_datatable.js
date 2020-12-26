  $(function () {
    $('#tabla_datatable').DataTable({
      'responsive'  : true,
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,

      "bDeferRender": true,
      "oLanguage": {

    //    "sUrl": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
 //   "sUrl": "../../../",
        "sEmptyTable": "No data available in table",
        "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries<br>",
        "sInfoEmpty":      "Showing 0 to 0 of 0 entries<br>",
        "sInfoFiltered":   "(filtered from _MAX_ total entries)<br>",
 //       "sInfoPostFix":    "All records shown are derived from real information<br>",
        "sInfoThousands": "'",
  //      "decimal" : ",",
        "sLoadingRecords": "Loading...",
        "sProcessing":     "Processing...",
   //     "sSearch": "Búsqueda:",
        "sSearch": "Search: _INPUT_",
     //   "sLengthMenu": "Mostrar _MENU_ por página",
        "sZeroRecords": "No matching records found",

        "sLengthMenu": 'Show <select>'+
         '<option value="5">5</option>'+
          '<option value="10">10</option>'+
          '<option value="20">20</option>'+
          '<option value="30">30</option>'+
          '<option value="40">40</option>'+
          '<option value="50">50</option>'+
          '<option value="-1">All</option>'+
        '</select> entries',

        "oPaginate": {
          "sLast": "Last",
          "sFirst": "First",
          "sNext": "Next",
          "sPrevious": "Previous",
        },
        "oAria": {
        "sSortAscending":  ": Activate to sort column ascending",
        "sSortDescending": ": Activate to sort column descending"
        },
      }

    })
  })