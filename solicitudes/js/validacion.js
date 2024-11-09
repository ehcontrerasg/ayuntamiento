//DECLARACION DE VARIABLES
var usuario              = {};
var dvSolicitudes        = $("#dvSolicitudes");
const dvPaginacion       = $("#dvPaginacion");
const btnAtrás           = $("#btnAtrás");
const btnAdelante        = $("#btnAdelante");
var filaDesde            = 1;
var filaHasta            = filaDesde+2;
const txtCodigoSolicitud = $("#txtCodigoSolicitud");
const btnBuscar          = $("#btnBuscar");

$(document).ready(function(){
    compSession(function() {
        getUserData();
    })
});

//DECLARACION DE FUNCIONES
function getUserData(){

    $.ajax({
        type:"POST",
        url: "../Datos/datos.solicitudes.php",
        data: {type:"getUserData"},
        success: function(res){
            usuario = {};
            usuario = JSON.parse(res);
            compSession(function() {
                getSCMS(usuario);
            });
        },error:function(settings, jqXHR){
            alert(jqXHR);
        }
    });

}

function btnAprobar(id){
    $('#cmt'+id).hide();
    $("#btns"+id).fadeOut();
    $("#alrt"+id).fadeIn(function(){
        
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

            $("#alrt"+id).fadeOut(function(){
                $('#btns'+id).fadeIn();
            });

            $("#btn-rech"+id).off('click');
        });
        
    });
}

function btnRechazar(id){
    $('#cmt'+id).show();
    $("#btns"+id).fadeOut();
    $("#alrt"+id).fadeIn(function(){

        /* Eliminar los eventos click asociados previamente. */            
        $("#btn-acept"+id).off('click');
        $("#btn-rech"+id).off('click');        
        /* Fin eliminar los eventos click asociados previamente. */

        $("#btn-acept"+id).click(function(){
            var comentario = $('#cmt'+id).val().toString();
            if (comentario != '') {
                respCalidad(id, comentario, 'N');
                $('#ctn'+id).slideUp('slow');
                $("#btn-rech"+id).off('click');
            }else{
                $('#cmt'+id).focus();
                $('#cmt'+id).attr('placeholder', 'Debe escribir la razón por la cual está rechazando esta solicitud.');
            }

        });
        $("#btn-rech"+id).click(function(){
            //$('#ctn'+id).slideUp('slow');
            $("#alrt"+id).fadeOut(function(){
                $('#btns'+id).fadeIn();
                $("#btn-rech"+id).off('click');
            });

        });
    });
}

function showFiles(solicitud){
    if (solicitud.ARCHIVOS.length == 0) { return; }
    
    let 
    div = $("<div/>",{
        id:`#dvArchivos${solicitud.ID_SCMS}`,
        class:"col-sm-12"
    });

    $("<label/>",{
		text: "Archivo(s)",
        class:"col-sm-12"
	})
	.appendTo(div);
	
	solicitud.ARCHIVOS.forEach(file => {
		let 
		a = $("<a/>",{
			href: file.ruta,
			text: file.nombre,
			class: "file col-sm-4"

		}),
		img = $("<img/>",{
			src:file.icono,
			class: "col-sm-6"
		});

		a.prepend(img);
        div.append(a);
	});	

    return div[0].outerHTML;
}

function cargarSCMS(json){

    json.forEach(function(solicitud){

        //Botón de detalle
        var spnDetalle 			   	   = document.createElement("span");
        spnDetalle.setAttribute("class","btnDetalle");
        var spanTextNode 			   = document.createTextNode("Detalle");
        spnDetalle.appendChild(spanTextNode);

        var spnIcono 			   	   = document.createElement("span");
        spnIcono.setAttribute("class","glyphicon glyphicon-menu-down");
        spnIcono.setAttribute("id","spnIconoDetalle");

        spnDetalle.appendChild(spnIcono);

        /* Div archivos */
           var dvArchivos =  showFiles(solicitud);
        /* Div archivos */


        var datosSolicitud = `<div class="form-horizontal lstSolicitudes" id=lstSolicitudes_${solicitud.ID_SCMS}>
										<div class='row'>
                							<div class='col-xs-1 col-sm-1 col-md-1 col-lg-1'>
                				    			<div class='idSCMS' draggable='true' id=${solicitud.ID_SCMS}>
                				    				<div class='dropup'>
            											<div>
															${solicitud.ID_SCMS}
        												</div>
            										</div>
           								 		</div>
										 	</div>
                							<div class='col-xs-11 col-sm-11 col-md-11 col-lg-11'>
                								<div class='row'>
                									<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                										<div class='row'>
                											<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                												<div class='form-group'>
                													<label class='control-label col-xs-4 col-sm-4 col-md-4 col-lg-4' for=>Estado:</label>
            														<div class='col-xs-8 col-sm-8 col-md-8 col-lg-8'>
            														    <input type='text' class='form-control input-sm' value=${solicitud.ESTADO} readonly>
            														</div>
																</div>
															</div>
            												<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                												<div class='form-group'>
                													<label class='control-label col-xs-4 col-sm-4 col-md-4 col-lg-4' for=''>Prioridad: </label>
            														<div class='col-xs-8 col-sm-8 col-md-8 col-lg-8'>
            														    <input type='text' class='form-control input-sm' value= ${solicitud.DESC_PRIORIDAD} readonly>
            														</div>
            													</div>
            												</div>
            											</div>
													</div>
            										<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                										<div class='row'>
                											<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                												<div class='form-group'>
                													<label class='control-label col-xs-5 col-sm-5 col-md-5 col-lg-5' for=''>Fecha Compromiso: </label>
            														<div class='col-xs-7 col-sm-7 col-md-7 col-lg-7'>
                														<input type='text' class='form-control input-sm' value=${solicitud.FECHA_COMPROMISO} readonly>
            														</div>
            													</div>
            												</div>
           												 </div>
            										</div>
            									</div>
            									<div class='row'>
                									<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                										<div class='form-group'>
                											<label class='control-label col-xs-3 col-sm-3 col-md-3 col-lg-3' for=''>Desarrollador: </label>
            												<div class='col-xs-9 col-sm-9 col-md-9 col-lg-9'>
                												<input type='text' class='form-control input-sm' value='${solicitud.DESARROLLADOR}' readonly>
															</div>
            											</div>
													</div>
            										<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                										<div class='form-group'>
                											<label class='control-label col-xs-5 col-sm-5 col-md-5 col-lg-5' for=''>Modulo/Pantalla: </label>
            					 							<div class='col-xs-7 col-sm-7 col-md-7 col-lg-7' >
            													<p style='font-size: 14px;color: #555;'>${solicitud.MODULO}/${solicitud.PANTALLA}</p>
            												</div>
            											</div>
            										</div>
            									</div>
            							<div class='dvButtons row' id= dvButtons_${solicitud.ID_SCMS}>`;

        dvSolicitudes.append(datosSolicitud);
        var dvButtons 		    = $("#dvButtons_"+solicitud.ID_SCMS);


        if(usuario.ID_CARGO == 600 || usuario.ID_CARGO == 10 ){
            creaBotonesAcciones(btnAprobar,dvButtons,'btn btn-success btn-aceptar', 'Aceptar',$("<span/>",{class:"glyphicon glyphicon-ok"}),"prepend");
            creaBotonesAcciones(btnRechazar,dvButtons,'btn btn-danger btn-rechazar', 'Rechazar',$("<span/>",{class:"glyphicon glyphicon-remove"}),"prepend");
        }else if((usuario.ID_CARGO == 111 || usuario.ID_CARGO == 112) && solicitud.ID_ESTADO == "FIN" ){
            creaBotonesAcciones(terminaSoli,dvButtons,'btn btn-warning btnTerminar', 'Terminar');
        }
        
        datosSolicitud = '</div>';
        dvButtons.append(/* datosSolicitud, */spnDetalle);
        datosSolicitud = `<div id="detalle${solicitud.ID_SCMS}" class="ocultar row">
    											<div class="row">
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Solicitador:</label>
										    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
										    					<input type="text" class="form-control input-sm" value= ${solicitud.SOLICITADOR} readonly>
										    				</div>
														</div>
									    			</div>
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Fecha Solicitud:</label>
										    				<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
										    					<input type="text" class="form-control input-sm" value=${solicitud.FECHA_SOLICITUD} readonly>
										    				</div>
														</div>
	    											</div>
	    										</div>
	    										<div class="row">
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Obs. Requerimiento:</label>
										    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" id=dvDescripcion_${solicitud.ID_SCMS}>
										    					<textarea class="form-control" rows="4" readonly id=txaDescripcion_${solicitud.ID_SCMS}>
										    						${solicitud.DESCRIPCION}
										    					</textarea>
										    				</div>
														</div>
	    											</div>
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Tipo Requerimiento:</label>
										    				<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
										    					<input type="text" class="form-control input-sm" value=${solicitud.DESC_REQUERIMIENTO} readonly>
										    				</div>
														</div>
	    											</div>
                                                    ${dvArchivos}
	    										</div>`;

        $(`#lstSolicitudes_${solicitud.ID_SCMS}`).append(datosSolicitud);
        datosSolicitud = `		</div>
    											<div id="detalle${solicitud.ID_SCMS}" class="ocultar">
    											<div class="row">
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Solicitador:</label>
										    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
										    					<input type="text" class="form-control input-sm" value= ${solicitud.SOLICITADOR} readonly>
										    				</div>
														</div>
									    			</div>
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Fecha Solicitud:</label>
										    				<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
										    					<input type="text" class="form-control input-sm" value=${solicitud.FECHA_SOLICITUD} readonly>
										    				</div>
														</div>
	    											</div>
	    										</div>
	    										<div class="row">
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-3 col-sm-3 col-md-3 col-lg-3">Obs. Requerimiento:</label>
										    				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" id=dvDescripcion_${solicitud.ID_SCMS}>
										    					<textarea class="form-control" rows="4" readonly id=txaDescripcion_${solicitud.ID_SCMS}>
										    						${solicitud.DESCRIPCION}
										    					</textarea>
										    				</div>
														</div>
	    											</div>
	    											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div class="form-group">
															<label class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Tipo Requerimiento:</label>
										    				<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
										    					<input type="text" class="form-control input-sm" value=${solicitud.DESC_REQUERIMIENTO} readonly>
										    				</div>
														</div>
	    											</div>
                                                </div>`;
        
        $(`#lstSolicitudes_${solicitud.ID_SCMS}`).append(datosSolicitud);

        datosSolicitud = `<div id="alrt${solicitud.ID_SCMS}" class="alert alert-dismissible fade in ocultar" role="alert" style="width: 95%; margin-top: 12px;">
                		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                		        <span aria-hidden="true">&times;</span>
                		    </button>
                		    <div class="row">
                		        <center>
                		            <h3 style="font-weight: bold; margin-bottom: 8px" align="center" id="msg${solicitud.ID_SCMS}"></h3>
                		            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 1%;">
                		                <textarea name="cmt${solicitud.ID_SCMS}" id="cmt${solicitud.ID_SCMS}" rows="8" maxlength="2500" placeholder="Expliquenos la razón." cols="40"></textarea>
                		            </div>
                		            <button id="btn-acept${solicitud.ID_SCMS}" class="btn btn-success">Si</button>
                		            <button id="btn-rech${solicitud.ID_SCMS}" class="btn btn-danger">No</button>
                		        </center>
                		    </div>
                		</div>`;
                        
        

        $(`#lstSolicitudes_${solicitud.ID_SCMS}`).append(datosSolicitud);

        spnDetalle.addEventListener("click",function(){
            var parentElement 	   = this.parentElement;
            var parentElementIdArr = parentElement.id.split("_");
            var parentElementId    = parentElementIdArr[1];
            mostOcu(parentElementId);
        });
    });

}

function getSCMS(usuario, codigoSolicitud="" ,estado = null, filaDesde = 1, filaHasta = 4) {

    var tipoSCMS = '';

    if(codigoSolicitud == ""){
         codigoSolicitud = 0;
    }

    if(usuario.ID_CARGO === "600" || usuario.ID_CARGO === "10"){
        tipoSCMS = 'C';
    }else if(usuario.ID_CARGO === "111" || usuario.ID_CARGO === "112"){
        tipoSCMS= 'R';
    }

    $.ajax({
        type:"POST",
        url: "../Datos/datos.solicitudes.php",
        data: {type:"getSCMS", tipoSCMS:tipoSCMS, codigoSolicitud:codigoSolicitud,estado:estado,filaDesde: filaDesde, filaHasta: filaHasta },
        async: false,
        success: function(res){

            var json = JSON.parse(res);
            dvSolicitudes.empty();

            if(json.length>0){
                if(json.length>1){
                    dvPaginacion.css('display','inline-block');
                }else if(json.length == 1 && codigoSolicitud!= ""){
                    dvPaginacion.css('display','none');
                }

                compSession(function(){cargarSCMS(json);});
            }else if (json.length==0 && filaDesde>1){
                btnAtrás.click();
            }else{
                dvSolicitudes.load("../Vistas/vista.Solicitudes_no_encontradas.php");
                dvPaginacion.css('display','none');
            }

        },error:function(settings, jqXHR){
            alert(jqXHR);
        }
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

function respEncargadoTi(id, comment) {
    $.post('../Datos/datos.solicitudes.php', {type: 'valFin', scms: id, comentario: comment}, function(data) {
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

//Función que crea los botones de acciones
function creaBotonesAcciones(callback,divPadre = '', clase = '', texto='', elementoHijo = '',posicionHijo='append') {

    if(divPadre != '' ){
        var btn = $("<button />",{class:clase, text:texto});
        if(posicionHijo =="append") {
            btn.append(elementoHijo);
        }else if(posicionHijo == 'prepend'){
            btn.prepend(elementoHijo);
        }

        btn.on("click",function(){
            var idSolicitud = getParentElementId(this);
            callback(idSolicitud);
        });
        divPadre.append(btn);
    }

}

function getParentElementId(elemento){
    var parentElement 	   = elemento.parentElement;
    var parentElementIdArr = parentElement.id.split("_");
    var parentElementId    = parentElementIdArr[1];

    return parentElementId;
}

function mostOcu(id) {
    id = '#detalle'+id;
    $(id).toggle('slow');
}

function terminaSoli(id){

    $('#cmt'+id).show();
    $("#btns"+id).fadeOut();
    $("#alrt"+id).fadeIn(function(){

        /* Eliminar los eventos click asociados previamente. */            
        $("#btn-acept"+id).off('click');
        $("#btn-rech"+id).off('click');        
        /* Fin eliminar los eventos click asociados previamente. */
        
        $("#btn-acept"+id).click(function(){
            $("#btn-acept"+id).off('click');
            var comentario = $('#cmt'+id).val().toString();
            respEncargadoTi(id, comentario);
            $('#ctn'+id).slideUp('slow');
            $("#btn-rech"+id).off('click');

        });
        $("#btn-rech"+id).click(function(){
            //$('#ctn'+id).slideUp('slow');
            $("#alrt"+id).fadeOut(function(){
                $('#btns'+id).fadeIn();
                $("#btn-rech"+id).off('click');
            });

        });
    });
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


btnAtrás.on("click",function(){

    if(filaDesde-3>=0){
        filaDesde-= 3;
        filaHasta = filaDesde+3;
    }

    getSCMS(usuario,"",""/*,estado*/, filaDesde,filaHasta);
});

btnAdelante.on("click",function(){
    filaDesde+= 3;
    filaHasta = filaDesde+3;
    getSCMS(usuario,"","",filaDesde,filaHasta);
});

btnBuscar.on("click",function(){
    //var estado = $(slcEstado,"option:selected").val()
    var codigoSolicitud = txtCodigoSolicitud.val();
    getSCMS(usuario,codigoSolicitud/* ,estado*/);
});

txtCodigoSolicitud.on("keypress",function(e){
    if(e.key === "Enter")
        btnBuscar.click();
})