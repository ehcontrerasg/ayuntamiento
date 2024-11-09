$(document).ready(function(){
	sltTipObservaciones('#sltCodigo');
	$( "#txtAsunto, #sltCodigo, #txtObservacion" ).prop( "disabled", true);
	var crearDataTable = function(codigo_inm) {
		 table = $('#tblObservaciones').DataTable({
					"processing": "true",
					"ajax" : {
						"method" : "GET",
						"url"	 : '../datos/datos.observaciones.php?caso=1&cod_inm='+codigo_inm,
					},
					"columns":[
						{"data"	: "CONSECUTIVO"},
						{"data"	: "CODIGO_OBS"},
						{"data"	: "ASUNTO"},
						{"data"	: "DESCRIPCION"},
						{"data"	: "FECHA"},
						{"data"	: "LOGIN"},
						//{"defaultContent" : "<button type='button' class='editar' data-toggle='modal' data-target='#myModal'><i class='fa fa-plus' aria-hidden='true'></i></button><button type='button' class='ver' data-toggle='modal' data-target='#listarPDFModal'><i class='fa fa-eye-slash' aria-hidden='true'></i></button><button type='button' class='info' data-toggle='modal' data-target='#infoModal'><i class='fa fa-info' aria-hidden='true'></i></button>"},
					],
					"order": [[ 4, "desc" ]]
				});
	}
	$('#frmCodInm').submit(function(e){
		//$(this).hide();
		e.preventDefault();
		var codigo_inm = $('#cod_inm').val();
	
		if ($.fn.dataTable.isDataTable('#tblObservaciones')) {
		    table.destroy();
		   	crearDataTable(codigo_inm);
		}
		else {
		    crearDataTable(codigo_inm);
		}
	});
	$('#cod_inm').keypress(function(event) {
		var valor = $(this).val();
		if (valor != '') {
			$("#txtAsunto, #sltCodigo, #txtObservacion").attr("disabled", false);
		}else{
			$("#txtAsunto, #sltCodigo, #txtObservacion").attr("disabled", true);
		}
	});
	$('#cod_inm').focusout(function(event) {
		var valor = $(this).val();
		if (valor != '') {
			$("#txtAsunto, #sltCodigo, #txtObservacion").prop("disabled", false);
		}else{
			$("#txtAsunto, #sltCodigo, #txtObservacion").prop("disabled", true);
		}
	});
	$('#frmObservacion').submit(function(e){
		e.preventDefault();
		var asunto = $('#txtAsunto').val();
		var codigo = $('#sltCodigo').val();
		var observacion = $('#txtObservacion').val();
		var codigo_inm =  $('#cod_inm').val();
		var dataObs = {
						caso : 1,
						asunto 			 : asunto,
						cod_observacion  : codigo,
						desc_opservacion : observacion,
						cod_inm 		 : codigo_inm
					  }
	    if (asunto!='' && codigo!='' && observacion!='') {
	    	$.ajax
			({
				url		: '../transact/trans.observaciones.php',
				type 	: 'POST',
				data 	: dataObs,
				success : function(resp){
					console.log(resp);
					if (resp == 'true') {
						$('#alertaMSG').remove();
						$('#frmObservacion').prepend(msgAlert('success', ' La observacion ha sido agregada correctamente', 'Listo!'));
						$('#frmObservacion')[0].reset();
						table.ajax.reload();
					}else{
						var jresp = $.parseJSON([resp]);
						var msg = ' Codigo Error : '+jrep.codigo+'n Mensaje: '+jrep.mensaje;
						$('#alertaMSG').remove();
						$('#frmObservacion').prepend(msgAlert('danger', msg, ' Ha ocurrido un error!'));
					}
										
				},
				error : function(xhr, status) {
					/* Act on the event */
					console.log(xhr + ' : '+ status);				}
			});	
	    }else{
	    	$('#alertaMSG').remove();
	    	$('#frmObservacion').prepend(msgAlert('warning', ' Debe llenar todos los campos', 'Formulario incompleto.'));

	    }
	});
	var msgAlert = function(tipo, msg, titulo){
		return '<div id="alertaMSG" class="alert alert-'+tipo+' alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>'+titulo+'</strong>'+
					msg+
				'</div>';

	}
	
                
});
function sltTipObservaciones(id){
	$.ajax
	    ({
	        url : '../datos/datos.observaciones.php',
	        type : 'GET',
	        dataType : 'json',
	        data : { caso : 3},
	        success : function(json) {
	            var row = json.CODIGO.length;
	            var html = "<option></option>";
	            for (var i=0; i < row; i++) {
	                html += '<option value="'+json.CODIGO[i]+'">'+json.DESCRIPCION[i]+'</option>';
	            }
	            $(id).html(html);
	        },
	        error : function(xhr, status) {
	                //console.log(JSON.parse(xhr+" : "+status))
	        }
	    }); 	
}

