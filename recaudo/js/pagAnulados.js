$(document).ready(function(){
	/*$('#dataTable tfoot th').each( function (i) {
        var title = $('#dataTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" data-index="'+i+'" />' );
    } );*/

	var table = $('#dataTable').DataTable({
		"bAutoWidth": false,
		"bJQueryUI": false,
        "bAutoWidth": false,
		"dom"	 	 : 'Bfrtip',
        "buttons"	 : [
            'copy', 'excel', 'print'
        ],
		"processing": "true",
		"ajax" : {
			"method" : "GET",
			"url"	 : "../datos/datos.pagAnulados.php"
		},
		"columns":[
			{"data"	: "ID_Pago"},
			{"data"	: "INM_CODIGO"},
			{"data"	: "IMPORTE"},
			{"data"	: "MOTIVO_REV"},
			{"data"	: "FECHA_PAGO"},
			{"data"	: "ID_USUARIO"},
			{"data"	: "FECHA_REV"},
			{"data"	: "USR_REV"}
		]/*,
		"aoColumns": [
           { "sWidth": "140px" },
           { "sWidth": "300px" },
           { "sWidth": "50px" }       
        ]*/,
        "fnInitComplete": function() {
            $("#credentials-table").css("width","100%");
        }
        
	
        /*"aoColumns" : [
            { sWidth: '50px' },
            { sWidth: '100px' },
            { sWidth: '120px' },
            { sWidth: '30px' },
            { sWidth: '30px' },
            { sWidth: '30px' },
            { sWidth: '30px' },
            { sWidth: '30px' }
        ]  */
	});
    // Filter event handler
    $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
        table
            .column( $(this).data('index') )
            .search( this.value )
            .draw();
    } );
    $("#dataTable").attr('width', '300px');
});
/*

 "bJQueryUI": false,
        "bAutoWidth": false,
        "bDestroy": true,
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
    "aoColumns": [
           { "sWidth": "140px" },
           { "sWidth": "300px" },
           { "sWidth": "50px" }       
        ],
        "fnInitComplete": function() {

            $("#credentials-table").css("width","100%");
        }
        */
	