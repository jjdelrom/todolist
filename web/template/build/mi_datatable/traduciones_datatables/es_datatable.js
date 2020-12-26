
	 /* $(function () {
	    $('#example1').DataTable()
	    $('#example2').DataTable({
	      'paging'      : true,
	      'lengthChange': false,
	      'searching'   : false,
	      'ordering'    : true,
	      'info'        : true,
	      'autoWidth'   : false
	    })
	  })*/

//  $(function () {
    $('#tabla_datatable').DataTable({
      'responsive'  : false,
     /* 'columnDefs'  : [
        { responsivePriority: 1, targets: 0 },
        { responsivePriority: 8, targets: 1 }
      ],*/
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
        "sEmptyTable": "No hay registros disponibles",
        "sInfo": "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)<br>",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros<br>",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)<br>",
 //       "sInfoPostFix":    "Todos los registros mostrados se derivan de información real<br>",
        "sInfoThousands": "'",
  //      "decimal" : ",",
        "sLoadingRecords": "Por favor espera - Cargando...",
        "sProcessing":     "Procesando...",
   //     "sSearch": "Búsqueda:",
        "sSearch": "Buscar _INPUT_ en la tabla",
     //   "sLengthMenu": "Mostrar _MENU_ por página",
        "sZeroRecords": "No se encontraron registros",

        "sLengthMenu": 'Mostrar <select>'+
         '<option value="5">5</option>'+
          '<option value="10">10</option>'+
          '<option value="20">20</option>'+
          '<option value="30">30</option>'+
          '<option value="40">40</option>'+
          '<option value="50">50</option>'+
          '<option value="-1">Todos</option>'+
        '</select> registros',

        "oPaginate": {
          "sLast": "Última página",
          "sFirst": "Primera",
          "sNext": "Siguiente",
          "sPrevious": "Anterior",
        },
        "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
      }

    })
//  })
