$(document).ready(function(){
	var form = $('form[name="report_form"]');
	var btn = $('#report-btn');

	checkStatus();

	getDepartments();
	$(form).submit(getReport);

	function getDepartments() {
		$.post('../Datos/reports.php', {tipo: 'department'}, function(data) {
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
		var departamento = data.department.selectedIndex;

		if (validateDate(data)) {
			var datos = $(form).serialize();
			 document.getElementById('reportPDF').src = '../Datos/reportEstPDF.php?'+datos;
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

function checkStatus(){

    $.get('../webServices/ws.getSession.php', function(respuesta) {

            var resp = JSON.parse(respuesta);
            if (typeof(resp.usuario) === "undefined") {
                $('#myModal').modal('show', function() {
                    $('#main').html(' ');
                });
            }

    });
}