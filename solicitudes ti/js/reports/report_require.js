$(document).ready(function(){
	var form = $('form[name="report_form"]');
	var btn = $('#report-btn');

	$('#dataTable').hide();

	getDepartments();
	$(form).submit(getReport);

	function getDepartments() {
		$.post('../php/data/reports.php', {tipo: 'department'}, function(data) {
			$.each(JSON.parse(data), function(index, el) {
				var option = document.createElement('option');
				option.value = index;
				option.innerText = el;
				$('#department').append(option);
			});
			
		});
	}

	function getReport(e)
	{
		e.preventDefault();
		var data = e.target;
		var departamento = data.department.selectedIndex,
			fecha_ini = data.fecha_ini.value,
			fecha_fin = data.fecha_fin.value;

		if (validateDate(data)) {
			var datos = $(form).serialize();
			$.get('../php/data/reports.php?tipo=report&'+datos, function(res) {
				var dat = JSON.parse(res);

			if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
				$('#dataTable').DataTable().destroy();
			}
				$('#dataTable').DataTable( {
			        data: JSON.parse(res),
			        columns: [
			            { title: "ID" },
			            { title: "Solicitante" },
			            { title: "Departamento" },
			            { title: "Tipo" },
			            { title: "Prioridad" },
			            { title: "Estado"},
			            { title: "Fecha" },
			            { title: "Doc"}
			        ],
			        "info":     false,
			          "order": [[ 7, "desc" ]],
			          "paging" : false
			    });
			    $('#dataTable').show();

			    $('.reportId').click(function(event) {
			    	event.preventDefault();
			    	document.getElementById('reportPDF').src = '';
			    	var id = $(this).attr('id');
			    	document.getElementById('reportPDF').src = '../php/data/reportPDF.php?id='+id;
			    	console.log("reporteID: "+id);
			    });
			});
		}
	}

	function validateDate(data)
	{
		if (data.fecha_ini.value.length == 0) {
			swal('Error!', 'Seleccione una fecha Inicial' ,'error' );
			return false;
		} else if (data.fecha_fin.value.length == 0){
			swal('Error!', 'Seleccione una fecha Final' ,'error' );
			return false;
		}

		return true;
	}
})
