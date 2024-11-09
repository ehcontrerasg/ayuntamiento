var 
slcEstado 				   = $("#slcEstado"),
usuario 				   = {},
accion 					   = "",
btnAnular				   = $(".btnAnular"),
filaDesde                  = 1,
filaHasta                  = filaDesde+2,
dvOcultar                  = "",
btnValidar = $(".btnValidar");

const 
dvPaginacion       = $("#dvPaginacion"),
btnAtrás           = $("#btnAtrás"),
btnAdelante        = $("#btnAdelante"),
txtCodigoSolicitud = $("#txtCodigoSolicitud"),
btnBuscar          = $("#btnBuscar"),
dpFechaDesde       = $("input[name=fechaDesde]"),
dpFechaHasta       = $("input[name=fechaHasta]"),
btnLimpiar         = $("#btnLimpiar"),
URL_DATOS_SOLICITUDES	= '../Datos/datos.solicitudes.php';

$(document).ready(function() {
	checkStatus();
    getUserData();
});

function checkStatus(){
   $.get('../webServices/ws.getSession.php', function(respuesta) {

            var resp = JSON.parse(respuesta)
            if (typeof(resp.usuario) === "undefined") {
                $('#myModal').modal('show', function() {
                    $('#main').html(' ');
                });
            } 
    });
}

function asigDesarollador(obj, id_scms, programer, type) {
	$.post('../Datos/datos.solicitudes.php', {type: 'programmer',id_scms: id_scms, programer: programer, valida: type}, function(data) {
		if (data == 1) {
			if (type == 'D') {
				swal({title: 'Desasignado',
  						text: 'Solicitud desasignada correctamente!',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
				$(obj).parents().eq(3).remove();
			} else if(type == 'A') {
				swal({title: 'Asignado',
  						text: 'Solicitud asignada correctamente!',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
				var soli = 'lstSolicitudes_'+id_scms;
				$('#'+soli).remove();

                enviarCorreo(id_scms,'F');
			}
		} else {
			swal('ERROR','Solicitud no pudo ser desasignada!','error');
		}
	});
}

function mostOcu(id) {
	id = '#detalle'+id;
	$(id).toggle('slow');
}

function pausaSoli(id){
	$.post('../Datos/datos.solicitudes.php', {type: 'status', scms: id, status: 'PAU'}, function(data) {
		if (data) {
			swal({title: 'Pausado',
  						text: 'Solicitud pausada!',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
		} else {
			swal('ERROR','Solicitud no pudo ser pausada!','error');
		}
	});
}

function iniciaSoli(id){
	$.post('../Datos/datos.solicitudes.php', {type: 'status', scms: id, status: 'PRO'}, function(data) {
		if (data) {
			swal({title: 'Iniciado',
  						text: 'Solicitud Inicializada correctamente!',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
		} else {
			swal('ERROR','Solicitud no pudo ser inicializada!','error');
		}
	});
}

function finalizaSoli(id){
	$.post('../Datos/datos.solicitudes.php', {type: 'cierre_scms'/*type: 'status'*/, id_scms: id/*, status: 'FIN'*/}, function(data) {
		if (data) {
			swal({title: 'Finalizado',
  						text: 'Solicitud finalizada correctamente!',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
		} else {
			swal('ERROR','Solicitud no pudo ser finalizada!','error');
		}
	});
}

function aprobarSoli(id){
	$.post('../Datos/datos.solicitudes.php', {type: 'validaUsr', scms: id}, function(data) {
		if (data) {
			swal({title: 'Aprobado',
  						text: 'Solicitud Aprobada correctamente!',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
		} else {
			swal('ERROR','Solicitud no pudo ser aprobada!','error');
		}
	});
}

function desaprobarSoli(id,comentario){
    $.post('../Datos/datos.solicitudes.php', {type: 'invalidaUsr', scms: id,comment:comentario}, function(data) {
        if (data) {
            swal({title: 'Desaprobada',
                    text: 'Solicitud desaprobada exitosamente!',
                    type: 'success',
                    showLoaderOnConfirm: true
                },
                function(isConfirm){
                    if (isConfirm) {
                        location.reload();
                    }
                });
        } else {
            swal('ERROR','Solicitud no pudo ser aprobada!','error');
        }
    });
}

function validarDesarrollo(idSolicitud){
	let options = {
		url:URL_DATOS_SOLICITUDES,
		type:'POST',
		data:{type:'validarDesarrollo',id_solicitud:idSolicitud},
		dataType: 'json'
	};
	$.ajax(options)
	.done((res)=>{
		swal("Información",res.mensaje,'warning')
	})
	.fail((error)=>{
		console.error(error);
	});
}

function terminaSoli(id){
	$.post('../Datos/datos.solicitudes.php', {type: 'valFin', scms: id}, function(data) {
		if (data) {
			swal({title: 'Revisado',
  						text: 'Solicitud terminada correctamente',
  						type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
		} else {
			swal('ERROR','Solicitud no pudo ser terminada!','error');
		}
	});
}

function btnAprobar(id){ 
		$('#cmt_'+id).hide();
		$("#btns"+id).fadeOut();
		$("#alrt_"+id).fadeIn(function(){
        
            /* Eliminar los eventos click asociados previamente. */        
            $("#btn-acept"+id).off('click');
            $("#btn-rech"+id).off('click');        
            /* Fin eliminar los eventos click asociados previamente. */

			$("#btn-acept"+id).click(function(){
					respCalidad(id, '', 'S');
				$('#ctn'+id).slideUp('slow');
				$("#btn-acept"+id).off('click');
			});
			$("#btn-rech"+id).click(function(){
				$("#alrt_"+id).fadeOut(function(){
					$('#btns'+id).fadeIn();
				});
				//$('#ctn'+id).slideUp('slow');
				$("#btn-rech"+id).off('click');
			});
		});
}

function btnRechazar(id){
		$('#cmt_'+id).show();
		$("#btns"+id).fadeOut();
		$("#alrt_"+id).fadeIn(function(){

            /* Eliminar los eventos click asociados previamente. */            
            $("#btn-acept"+id).off('click');
            $("#btn-rech"+id).off('click');        
            /* Fin eliminar los eventos click asociados previamente. */

			$("#btn-acept"+id).click(function(){
				$("#btn-acept"+id).off('click');
				var comentario = $('#cmt_'+id).val().toString();
				
				if (comentario != '') {
					respCalidad(id, comentario, 'N');
					$('#ctn'+id).slideUp('slow');
					$("#btn-rech"+id).off('click');
				}else{
					$('#cmt_'+id).focus();
					$('#cmt_'+id).attr('placeholder', 'Debe escribir la razón por la cual está rechazando esta solicitud.');
				}
				
			});
			$("#btn-rech"+id).click(function(){
				$("#alrt_"+id).fadeOut(function(){
					$('#btns'+id).fadeIn();
					$("#btn-rech"+id).off('click');
				});
				
			});
		});
}

function respCalidad(id, comment, resp) {
	$.post('../Datos/datos.solicitudes.php', {type: 'validate', scms: id, comment: comment, resp: resp}, function(data) {
		if (data) {
			swal({title: 'Finalizado',
					text: 'Solicitud validada correctamente!',
					type: 'success'},
  					function(isConfirm){
						if (isConfirm) {
							location.reload();
						}
					});
		} else {
			swal('ERROR','Solicitud no pudo ser validada!','error');
		}
	});
}

function anulascms(idSCMS,comment){
    $.post('../Datos/datos.solicitudes.php', {type: 'anularSCMS', id_scms: idSCMS, comment: comment}, function(data) {
        if (data) {
            swal({title: 'Revisado',
                    text: 'Solicitud anulada correctamente',
                    type: 'success'},
                function(isConfirm){
                    if (isConfirm) {
                        location.reload();
                    }
                });
        } else {
            swal('ERROR','Solicitud no pudo ser anulada!','error');
        }
    });
}

function btnAnularSCMS(id){
    $('#cmt_'+id).show();
    $("#btns"+id).fadeOut();
    $("#alrt_"+id).fadeIn(function(){

        /* Eliminar los eventos click asociados previamente. */            
       $("#btn-acept"+id).off('click');
       $("#btn-rech"+id).off('click');        
       /* Fin eliminar los eventos click asociados previamente. */

        $("#btn-acept"+id).click(function(){
            $("#btn-acept"+id).off('click');

            var comentario = $('#cmt_'+id).val().toString();
            if (comentario != '') {
                anulascms(id,comentario);
                $('#ctn'+id).slideUp('slow');
                $("#btn-rech"+id).off('click');
            }else{
                $('#cmt_'+id).focus();
                $('#cmt_'+id).attr('placeholder', 'Debe escribir la razon por la cual está anulando esta solicitud.');
            }

        });
        $("#btn-rech"+id).click(function(){            
            $("#alrt_"+id).fadeOut(function(){
                $('#btns'+id).fadeIn();
                $("#btn-rech"+id).off('click');
            });
        });
    });    
}

function btnDesaprobarSCMS(id){
    $('#cmt_'+id).show();
    $("#btns"+id).fadeOut();
    $("#alrt_"+id).fadeIn(function(){

        /* Eliminar los eventos click asociados previamente. */            
       $("#btn-acept"+id).off('click');
       $("#btn-rech"+id).off('click');        
       /* Fin eliminar los eventos click asociados previamente. */

        $("#btn-acept"+id).click(function(){
            $("#btn-acept"+id).off('click');
            var comentario = $('#cmt_'+id).val().toString();
            if (comentario != '') {
                desaprobarSoli(id,comentario);
                $('#ctn'+id).slideUp('slow');
                $("#btn-rech"+id).off('click');
            }else{
                $('#cmt_'+id).focus();
                $('#cmt_'+id).attr('placeholder', 'Debe escribir la razon por la cual está anulando esta solicitud.');
            }
        });
        $("#btn-rech"+id).click(function(){
            //$('#ctn'+id).slideUp('slow');
            $("#alrt_"+id).fadeOut(function(){
                $('#btns'+id).fadeIn();
                $("#btn-rech"+id).off('click');
            });

        });
    });
}

function getEstadosSCMS(tipo_estado = 'A'){

    $.ajax({
        type:"POST",
        url: "../Datos/datos.solicitudes.php",
        data: {type:"estadosSCMS",tipo_estado:tipo_estado },
        success:function(res){
            var json = JSON.parse(res);
            slcEstado.empty();

            var option = "<option value=''>Todos los estados</option>";
            slcEstado.append(option);
            json.forEach(function(estado){
                option = "<option value ="+estado.CODIGO+">"+estado.DESCRIPCION+"</option>";
                slcEstado.append(option);
			});

            getSCMS();
        },
        error:function(settings,jqXHR){
            console.error(jqXHR);
        }
    });
}

function getUserData(){
	
	$.ajax({
		   type:"POST",
		   url: "../Datos/datos.solicitudes.php",
		   data: {type:"getUserData"},
		   success: function(res){

		     usuario = {};
		   	 usuario = JSON.parse(res);

             if(usuario.ID_CARGO == 9){
				getEstadosSCMS('B');
             }else{
				getEstadosSCMS();
             }
		   },error:function(settings, jqXHR){
		   	 console.error(jqXHR);
		  }
	});
	
}

function habilitarDescripcionSolicitud(idSolicitud){
    getSCMSData(idSolicitud);
}


function cargarSCMS(json){
	
	json.forEach(function(solicitud){
        addSolicitud(solicitud,usuario);
    });

}

function getSCMS(usuario = {}, codigoSolicitud=0 ,estado = null, filaDesde = 1, filaHasta = 4,fechaDesde = '',fechaHasta = ''){
    
    var tipoSCMS = (usuario.ID_CARGO == 9 || usuario.ID_CARGO == 112) ? 'S' : 'F';

	if(codigoSolicitud == ""){
	    codigoSolicitud = 0;
    }else{
        estado = "";
    }

    $.ajax({

        type:"POST",
        url: "../Datos/datos.solicitudes.php",
        data: {type:"getSCMS", tipoSCMS:tipoSCMS, codigoSolicitud:codigoSolicitud,estado:estado,filaDesde: filaDesde, filaHasta: filaHasta,fechaDesde:fechaDesde, fechaHasta:fechaHasta},
		async: false,
        success: function(res){

        	var json = JSON.parse(res);
            dvSolicitudes.empty();
			
            if(json.length>0){
                if(json.length>1){
                    dvPaginacion.css('display','inline-block');
                }else{
                    dvPaginacion.css('display','inline-block');
                }

            	compSession(function(){
					cargarSCMS(json);
				});
            }else if (json.length==0 && filaDesde>1){
                btnAtrás.click();
            }else{
                dvSolicitudes.load("../Vistas/vista.Solicitudes_no_encontradas.php");
                dvPaginacion.css('display','none');
            }

        },error:function(settings, jqXHR){
            console.error(jqXHR);
        }
    });

}

function getParentElementId(elemento){
    var parentElement 	   = elemento.parentElement;
    var parentElementIdArr = parentElement.id.split("_");
    var parentElementId    = parentElementIdArr[1];

    return parentElementId;
}

function closeCmt(btnId){
    var id = btnId.attr("id").split("_")[1];
    $('#alrt_'+id).toggle("hide");
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
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();
                        }
                    });
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


function getSCMSData(idSCMS){

    $.ajax({
            type:"POST",
            data:{type:"getSCMSData", idSCMS: idSCMS},
            url: "../Datos/datos.solicitudes.php",
            success:function(res){
                localStorage.clear();
                localStorage.setItem('EditarSolicitud', JSON.stringify({idSCMS:idSCMS, edita:"true", data:JSON.parse(res)}));
                document.location.href = '../Vistas/solicitar.php';
            },
            error:function(s, jqXHR){
                console.error(jqXHR);
            }
    })

}

function enviarCorreo(idSCMS,tipoSolicitud){

    $.ajax({
            type:"POST",
            url:  "../Datos/datos.solicitudes.php",
            data:{type:"enviarCorreo", idSCMS:idSCMS,tipoSolicitud:tipoSolicitud },
            success:function(res){
                console.log(res);
            },
            error:function(settings, jqXHR){
                console.error(jqXHR);
            }
    });
}

slcEstado.on("change",function(){
    txtCodigoSolicitud.val('');
});

btnAnular.on("click",function(){
	console.log($(this).parents().eq(4).attr("id"));
	/* var parentId 	  = $(this).parents().eq(4).attr("id");
	var parentIdArray = parentId.split("_");
	var id 			  = parentIdArray[1];


    btnAnularSCMS(id); */
});

btnAtrás.on("click",function(){

    if(filaDesde-3>=0){
        filaDesde-= 3;
        filaHasta = filaDesde+3;
    }

    var estado = $(slcEstado,"option:selected").val();
    var codigoSolicitud = txtCodigoSolicitud.val();
    getSCMS(usuario, codigoSolicitud ,estado, filaDesde,filaHasta);

});

btnAdelante.on("click",function(){

    filaDesde+= 3;
    filaHasta = filaDesde+3;

    var estado = $(slcEstado,"option:selected").val();
    var codigoSolicitud = txtCodigoSolicitud.val();
    getSCMS(usuario,codigoSolicitud ,estado, filaDesde,filaHasta);
});

btnBuscar.on("click",function(){
    var estado          = $(slcEstado,"option:selected").val()
    var codigoSolicitud = txtCodigoSolicitud.val();
    var fechaDesde       = dpFechaDesde.val();
    var fechaHasta       = dpFechaHasta.val();
    getSCMS(usuario, codigoSolicitud,estado,filaDesde,filaHasta,fechaDesde,fechaHasta);
});

txtCodigoSolicitud.on("keypress",function(e){
    if(e.key === "Enter")
        btnBuscar.click();
});

btnLimpiar.on("click",function(){
    txtCodigoSolicitud.val('');
    $("#slcEstado option[value='']").prop('selected','selected').change();
    dpFechaDesde.val('');
    dpFechaHasta.val('');
});

dpFechaDesde.on("change",function(){
    txtCodigoSolicitud.val('');
});
dpFechaHasta.on("change",function(){
    txtCodigoSolicitud.val('');
});

btnValidar.on('click',function(){
	var parentId 	  = $(this).parents().eq(4).attr("id");
	var parentIdArray = parentId.split("_");
	var id 			  = parentIdArray[1];

	validarDesarrollo(id);
});