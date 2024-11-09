var slcEstado 	 = $("#slcEstadoSCMS");

$(document).ready(function(){

	var form = $('form[name="report_form"]');
	var btn = $('#report-btn');

	checkStatus();
	$('#dataTable').hide();

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
		var departamento = data.department.selectedIndex,
			fecha_ini = data.fecha_ini.value,
			fecha_fin = data.fecha_fin.value;

		if (validateDate(data)) {
			var datos = $(form).serialize();
			$.get('../Datos/reports.php?tipo=report&'+datos, function(res) {
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
			            { title: "Valida Solicitante" },
			            { title: "Doc"}
			        ],
			        "info":     false,
			          "order": [[ 7, "desc" ]],
			          "paging" : false
			    });
			    $('#dataTable').show();
                $("#acciones").show();

			    $('.reportId').click(function(event) {
			    	event.preventDefault();
			    	document.getElementById('reportPDF').src = '';
			    	var id = $(this).attr('id');
			    	document.getElementById('reportPDF').src = '../Datos/reportPDF.php?id='+id;

			    });

                $("#acciones").css('display','block');
			});
		}
	}

	function validateDate(data)
	{
		if (data.fecha_ini.value.length == 0) {
			swal('Error!', 'Seleccione una fecha inicial.' ,'error' );
			return false;
		} else if (data.fecha_fin.value.length == 0){
			swal('Error!', 'Seleccione una fecha final.' ,'error' );
			return false;
		}

		return true;
	}
    function getEstadosSCMS(){

        $.ajax({
            type:"POST",
            url: "../Datos/datos.solicitudes.php",
            data: {type:"estadosSCMS"},
            success:function(res){
                var json = JSON.parse(res);
                json.forEach(function(estado){
                    var option = "<option value ="+estado.CODIGO+">"+estado.DESCRIPCION+"</option>";
                    slcEstado.append(option);
                })
            },
            error:function(settings,jqXHR){
                alert(jqXHR);
            }
        });
    }

	$("#btnExpExcel").on("click",genRepSolicitudes);
    getEstadosSCMS();
})

function genRepSolicitudes() {
    /*swal
    ({
            title: "Advertencia!",
            text: "",
            showConfirmButton: true,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                //compSession(genRepDeuGraCliDat);
                /!*compSession(genRepResPQR);*!/
				/!*genRepResPQR();*!/
                //alert("clicked");

            }
        });*/
    compSession(genRepResSolicitudes);
}
function compSession(callback)
{
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data:{tip : 'sess'},
        dataType : 'json',
        success : function(json) {
            if(json==true){
                if(callback){
                    callback();
                }
            }else if(json==false){
                $("#ingResInsInpCodSis").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password' tabindex='4' placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },

                    function(inputValue){
                        if (inputValue === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{
                            $("#ingResInsInpCodSis").focus();
                            iniSes();
                        }
                    }
                );


                return false;
            }
        },
        error : function(xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}
function genRepResSolicitudes(){
    var datos=$("#frmSolicitudes").serializeArray();

    $.ajax
    ({
        url : '../reportes/reporte.SolicitudesExcel.php',
    type : 'GET',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){
                window.location.href = urlPdf;
                //window.close();
                swal("Mensaje!", "Has Generado correctamente el reporte", "success");
            }else{
                swal
                (
                    {
                        title: "Error",
                        text: "Contacte a sistemas",
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true});

            }

        },
        error : function(xhr, status) {
            swal("Error!", "Error desconocido contacte a sistemas", "error");
        }
    });
}

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


