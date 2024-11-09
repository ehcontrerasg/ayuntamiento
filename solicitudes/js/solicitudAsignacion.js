let dvSolicitudes 			   = $("#dvSolicitudes");

const divSolicitud = (solicitud) => {
    let dvSolicitud = $("<div/>", {
        class: "lstSolicitudes row",
        id: `lstSolicitudes_${solicitud.ID_SCMS}`
    });

    return dvSolicitud;
};

function spnDetalle() {
    return $("<span/>", {
        text: "Detalle▼",
        class: "col-sm-12 spnDetalle"
    }).on('click', (e) => {
        let idSolicitud = e
            .target
            .parentElement
            .id
            .split("_")[1];
    
        mostOcu(idSolicitud);
    });    
}

/* 
    Función que retorna el círculo que contiene el id de la solicitud.
*/
const circuloSolicitud = (idSolicitud, estadoSolicitud) => {
    let circuloSolicitud = $("<div/>", {
        id: "circuloSolicitud",
        text: idSolicitud,
        class: "dropdown-toggle",
    })
        .attr("role", "button")
        .attr("data-toggle", "dropdown")
        .attr("aria-haspopup", "true")
        .attr("aria-expanded", "false");
    let dropdownMenu = $("<div/>", {
        class: 'dropdown-menu'
    })
        .attr('aria-labelledby', "dropdownMenuLink");

    if (estadoSolicitud == "VAL") {
        getDesarrolladores()
            .done((desarrolladores) => {
                desarrolladores.forEach(desarrollador => {
                    dropdownMenu.append($("<a/>", {
                        class: "dropdown-item asignarDesarrollador",
                        text: desarrollador.nombre
                    }).on('click', function (e) {
                        asigDesarollador($(e.target), idSolicitud, desarrollador.id_usuario, 'A');
                    })
                    );
                });
            })
            .fail((error) => {
                console.error(error);
            });
    } else {
        dropdownMenu.append(
            $("<a/>", {
                class: "dropdown-item",
                text: "Debe validar esta solicitud."
            })
        );
    }

    circuloSolicitud.append(dropdownMenu);
    return circuloSolicitud;
};

const campo = (text, value, classes = "col-sm-4 row") => {
    return $("<p/>", {
        text: text,
        class: classes
    }).append(
        $("<span/>", {
            text: value
        })
    );
};

//Función que crea los botones de acciones
const creaBotonesAcciones = (callback, divPadre = '', clase = '', texto = '', posicionHijo = 'append', elementoHijo = '') => {

    if (divPadre != '') {

        var btn = $("<button />", { class: clase, text: texto });
        if (posicionHijo == "append") {
            btn.append(elementoHijo);
        } else if (posicionHijo == 'prepend') {
            btn.prepend(elementoHijo);
        }

        btn.on("click", function () {
            var idSolicitud = getParentElementId(this);            
            callback(idSolicitud);
        });
        divPadre.append(btn);
    }
}

function btnAnularSCMS(id){    
    $("#alrt_"+id).toggle('slow');    
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
    $("#alrt_"+id).toggle('slow');
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

function btnDesaprobarDesarrollo(id){    
    $("#alrt_"+id).toggle('slow');    
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
                desaprobarDesarrollo(id,comentario);
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

function desaprobarDesarrollo(idSolicitud, comentario){
    let options = {url:URL_DATOS_SOLICITUDES, type:"POST", dataType:"json", data:{type:'desaprobarDesarrollo', id_Solicitud:idSolicitud, comentario:comentario}};
    $.ajax(options).done((res)=>{
        if (res.codigo == 0) {
            enviarCorreo(idSolicitud,'L');
            swal({title: 'Revisado',
                    text: res.mensaje,
                    type: 'success'},
                function(isConfirm){
                    if (isConfirm) {
                        location.reload();
                    }
                });
        }else{
            swal({title: 'Revisado',
                    text: res.mensaje,
                    type: 'error'},
                function(isConfirm){
                    if (isConfirm) {
                        location.reload();
                    }
                });
        }
    }).fail((error)=>{
        console.error(error);
    });
}

const divButtons = (solicitud, usuario) => {
        
    let dvButtons = $("<div/>", {
        id: `dvButtons_${solicitud.ID_SCMS}`,
        class: 'dvButtons row col-sm-12'
    });

    if (solicitud.VALIDA_CALIDAD == 'Sí' && (usuario.ID_CARGO == 9 || usuario.ID_CARGO == 111 || usuario.ID_CARGO == 112)) {

        if (solicitud.ID_ESTADO == "ESP" && solicitud.DESARROLLADOR == "-") {
            creaBotonesAcciones(validarDesarrollo, dvButtons, 'btn btn-primary btnValidar col-sm-1', 'Aprobar');
            creaBotonesAcciones(btnDesaprobarDesarrollo, dvButtons, 'btn btn-warning btnAnular', 'Desaprobar');
            creaBotonesAcciones(btnAnularSCMS, dvButtons, 'btn btn-danger btnAnular col-sm-1', 'Anular');
        } else if (solicitud.ID_ESTADO == "ESP" && solicitud.DESARROLLADOR != "-" && (usuario.ID_CARGO == 9 || usuario.ID_CARGO == 112)) {
            creaBotonesAcciones(iniciaSoli, dvButtons, 'btn btn-info btnIniciar', 'Iniciar');
            creaBotonesAcciones(btnAnularSCMS, dvButtons, 'btn btn-danger btnAnular', 'Anular');
        } else if (solicitud.ID_ESTADO == "PRO" && (usuario.ID_CARGO == 9 || usuario.ID_CARGO == 112)) {
            creaBotonesAcciones(finalizaSoli, dvButtons, 'btn btn-success btnFinalizar', 'Finalizar');
            creaBotonesAcciones(btnAnularSCMS, dvButtons, 'btn btn-warning btnAnular', 'Anular');
        }
    }
    
    if (solicitud.ID_ESTADO == "FIN" && solicitud.VALIDA_SOLICITANTE != 'S' && usuario.ID_CARGO != 9) {
        creaBotonesAcciones(aprobarSoli, dvButtons, 'btn btn-success btnAprobar', 'Aprobar');
        creaBotonesAcciones(btnDesaprobarSCMS, dvButtons, 'btn btn-danger btnDesaprobar', 'Desaprobar');
    } else if ((solicitud.ID_ESTADO == "REC" || solicitud.ID_ESTADO == "DPR") && (usuario.ID_CARGO != 9 && usuario.ID_USUARIO == solicitud.ID_SOLICITADOR) ) {
        creaBotonesAcciones(finalizaSoli, dvButtons, 'btn btn-success btnFinalizar', 'Finalizar');
        creaBotonesAcciones(habilitarDescripcionSolicitud, dvButtons, 'btn btn-primary btnEditar', 'Editar');
    }

    return dvButtons;
}

function showFiles(solicitud) {

    if (solicitud.ARCHIVOS.length == 0) { return; }

    let div = $("<div/>", {
        class: "col-sm-6"
    });

    $("<label/>", {
        text: "Archivo(s)"
    })
        .appendTo(div);

    solicitud.ARCHIVOS.forEach(file => {
        let
            a = $("<a/>", {
                href: file.ruta,
                text: file.nombre,
                class: "file col-sm-12",
                download:file.nombre

            }),
            img = $("<img/>", {
                src: file.icono,
                class: "col-sm-1"
            });

        a.prepend(img);
        div.append(a);
    });

    return div;
}

const cajaComentarios = (solicitud)=>{
    return`<div id="alrt_${solicitud.ID_SCMS}" class="alert alert-dismissible cajaComentario" role="alert" style="width: 95%; margin-top: 12px;">
                		    <button type="button" class="close btnCloseCmt" id= "btnCloseCmt_${solicitud.ID_SCMS}" onclick = closeCmt($(this))>
                		       &times;
                		    </button>
                		    <div class="row justify-content-center">
                		        <center>
                		            <h3 style="font-weight: bold; margin-bottom: 8px" align="center" id="msg${solicitud.ID_SCMS}"></h3>
                		            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 1%;">
                		                <textarea name="cmt${solicitud.ID_SCMS}" id="cmt_${solicitud.ID_SCMS}" rows="8" maxlength="2500" placeholder="Expliquenos la razón." cols="40"></textarea>
                		            </div>
                		            <button id="btn-acept${solicitud.ID_SCMS}" class="btn btn-success">Si</button>
                		            <button id="btn-rech${solicitud.ID_SCMS}" class="btn btn-danger">No</button>
                		        </center>
                		    </div>
                		</div>`;
};


const dvDetalle = (solicitud) => {
    let dvDetalle = $("<div/>", {
        id: `detalle${solicitud.ID_SCMS}`,
        class: "dvDetalle col-sm-12 row",
        style:['display:none']
    });

    dvDetalle.append(
        campo("Solicitador: ", solicitud.SOLICITADOR, 'col-sm-6'),
        campo("Fecha de solicitud: ", solicitud.FECHA_SOLICITUD, 'col-sm-6'),
        campo("Obs. Requerimiento: ", solicitud.DESCRIPCION, 'col-sm-6 pObservacion'),
        campo("Fecha de inicio: ", solicitud.FECHA_INICIO, 'col-sm-6'),
        campo("Fecha de conclusión: ", solicitud.FECHA_CONCLUSION, 'col-sm-6'),
        campo("Tipo de requerimiento: ", solicitud.DESC_REQUERIMIENTO, 'col-sm-6'),
        showFiles(solicitud),
        mensajeCalidad(solicitud),        
        mensajeSolicitante(solicitud),
        mensajeDesarrollador(solicitud)
    );

    return dvDetalle;
};


const getDesarrolladores = () => {
    let options = {
        url: URL_DATOS_SOLICITUDES,
        type: "POST",
        dataType: "json",
        data: { type: "getDesarrolladores" }
    };
    return $.ajax(options);
};


const mensajeCalidad = (solicitud) => {
    let datosSolicitud = ''
    if (solicitud.MENSAJE_CALIDAD != null && solicitud.ID_ESTADO == 'REC') {

        datosSolicitud = 
        `<div class="col-sm-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Mensaje de calidad, ${solicitud.FECHA_MENSAJE_CALIDAD}:</label>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <textarea class="form-control" rows="4" readonly id=txaDescripcion_${solicitud.ID_SCMS}>
                            ${solicitud.MENSAJE_CALIDAD}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>`;        
    }

    return datosSolicitud;
};

const mensajeSolicitante = (solicitud)=>{

    if(solicitud.COMENT_DESAPRUEBA_SOLICITANTE != '-' && solicitud.ID_ESTADO == 'PRO' && usuario.ID_CARGO == 9){

        datosSolicitud =	  `<div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Mensaje del solicitante, ${solicitud.FECHA_COMENT_SOLICITANTE}:</label>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <textarea class="form-control" rows="4" readonly id=txaDescripcion_${solicitud.ID_SCMS}>
                                                                ${solicitud.COMENT_DESAPRUEBA_SOLICITANTE}
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                </div>`;

        return datosSolicitud;
    }

    return null;
};

const mensajeDesarrollador = (solicitud)=>{
    if(solicitud.COMENT_DESAPRUEBA_TI != '-' && solicitud.ID_ESTADO == 'ANU' && usuario.ID_CARGO != 9){
        datosSolicitud =	  `<div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Mensaje de ${solicitud.USUARIO_COMENT_ANULA_TI}, ${solicitud.FECHA_COMENT_ANULA_TI}:</label>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <textarea class="form-control" rows="4" readonly id=txaDescripcion_${solicitud.ID_SCMS}>
                                                                ${solicitud.COMENT_DESAPRUEBA_TI}
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                </div>`;

        return datosSolicitud;
    }

    return null;
}

const addSolicitud = (solicitud, usuario) => {

    let
    dvSolicitud = divSolicitud(solicitud)
    numeroSolicitud = circuloSolicitud(solicitud.ID_SCMS, solicitud.ID_ESTADO);
    estadoSolicitud = campo("Estado:", solicitud.ESTADO),
    prioridad = campo('Prioridad:', solicitud.DESC_PRIORIDAD);
    fechaCompromiso = campo('Fecha de compromiso:', solicitud.FECHA_COMPROMISO);
    validaCalidad = campo('Valida calidad:', solicitud.VALIDA_CALIDAD).css("margin-left", "5%");
    desarrollador = campo('Desarrollador:', solicitud.DESARROLLADOR);
    moduloPantalla = campo('Módulo/Pantalla:', `${solicitud.MODULO}/${solicitud.PANTALLA}`);
    dvBotones = divButtons(solicitud, usuario);

    idSolicitud = solicitud.ID_SCMS;

    dvSolicitud.append(
        numeroSolicitud,
        estadoSolicitud,
        prioridad,
        fechaCompromiso,
        validaCalidad,
        desarrollador,
        moduloPantalla,
        dvBotones,
        spnDetalle(),
        dvDetalle(solicitud),
        cajaComentarios(solicitud)
    );

    dvSolicitudes.append(dvSolicitud);
};