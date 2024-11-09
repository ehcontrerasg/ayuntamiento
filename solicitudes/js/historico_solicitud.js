const
frmHistoricoSolicitud = $("#frmHistoricoSolicitud");
URL_DATOS = '../Datos/datos.historico_solicitudes.php';

let 
spnEstado = $("#spnEstado");
spnPrioridad = $("#spnPrioridad");
spnFechaCompromiso = $("#spnFechaCompromiso");
spnFechaSolicitud = $("#spnFechaSolicitud");
spnDesarrollador = $("#spnDesarrollador");
spnModuloPantalla = $("#spnModuloPantalla");
spnSolicitante = $(".spnSolicitante");
spnRequerimiento = $("#spnRequerimiento");
spnTipoRequerimiento = $("#spnTipoRequerimiento");
spnCodigoSolicitud = $("#spnCodigoSolicitud");
dvInformacion = $("#dvInformacion");
cuerpoResumen = $("#fstInformacionGeneral > .cuerpo");
fstHistorico = $("#fstHistorico");
cuerpoHistorico = $("#fstHistorico > .cuerpo");
spnFechaInicio = $("#spnFechaInicio");
spnFechaConclusion = $("#spnFechaConclusion");

/* Funciones */
const getResumenSolicitud = (idSolicitud)=>{
    let options = {url:URL_DATOS, type:"POST", dataType:"json", data:{type:"getResumenSolicitud", id_solicitud:idSolicitud}};
    $.ajax(options)
    .done((solicitud)=>{                
        mostrarResumenSolicitud(solicitud);
    })
    .fail((error)=>{console.error(error);});
};

const mostrarResumenSolicitud = (solicitud)=>{
    console.log(solicitud);
    if (solicitud.codigo_solicitud == null) { 
        swal("Mensaje",`No se encontraron datos para la solicitud especificada.`,"error"); 
        dvInformacion.css('visibility','hidden');
        return;
    }

    spnCodigoSolicitud.text(solicitud.codigo_solicitud);
    spnSolicitante.text(solicitud.solicitante);
    spnEstado.text(solicitud.estado);
    spnPrioridad.text(solicitud.prioridad);
    spnFechaCompromiso.text(solicitud.fecha_compromiso);
    spnFechaSolicitud.text(solicitud.fecha_solicitud);
    spnDesarrollador.text(solicitud.desarrollador);
    spnModuloPantalla.text(solicitud.pantalla_modulo);    
    spnRequerimiento.text(solicitud.descripcion);
    spnTipoRequerimiento.text(solicitud.tipo_requerimiento);
    spnFechaInicio.text(solicitud.fecha_inicio);
    spnFechaConclusion.text(solicitud.fecha_conclusion);

    let archivos = showFiles(solicitud);

    cuerpoResumen.append(archivos);

    dvInformacion.css('visibility','visible');

};

const getHistoricoSolicitud = (idSolicitud)=>{
    let options = {url:URL_DATOS, type:"POST", dataType:"json", data:{type:"getHistoricoSolicitud", id_solicitud:idSolicitud}};
    $.ajax(options)
    .done((historial)=>{        
        mostrarHistoricoSolicitud(historial);
    })
    .fail((error)=>{console.error(error);});
}

function movimientoSolicitud(movimiento){

    return $("<li/>",{
        text:`${movimiento.fecha}, ${movimiento.descripcion}`,
        class:"tituloMovimiento"
    }).append(
        $("<ul/>",{
            id:"ulMovimiento",
            class:"row justify-content-around"
        }).append(
            $("<li/>").append(
                $("<p/>",{
                    text:"Usuario que realiza el movimiento: "
                }).append(
                    $("<span/>",{
                        text:movimiento.usuario,
                        class:"col-sm-6 spnMovimiento"
                    })
                )
            ),
            $("<li/>").append(
                $("<p/>",{
                    text:"Estado de la solicitud: "
                }).append(
                    $("<span/>",{
                        text:movimiento.estado_solicitud,
                        class:"col-sm-6 spnMovimiento"
                    })
                )
            ),
            $("<li/>").append(
                liComentario(movimiento)
            )
        )
    );
}

function liComentario(movimiento){
    
    if (movimiento.comentario == null) { return; }

    return $("<p/>",{
        text:`Comentario de ${movimiento.usuario}: `
    }).append(
        $("<span/>",{
            text:movimiento.comentario,
            class:"col-sm-12 spnMovimiento"
        })
    );
}

function mostrarHistoricoSolicitud(historial){
    cuerpoHistorico.empty();

    if (historial.length == 0 ) {
        fstHistorico.css('display', 'none');
        return;
    }

    fstHistorico.css('display', 'block');

    let ul = $("<ul/>",{
        class:"col-sm-12",
        id:"ulHistorial"
    });
    historial.forEach(movimiento => {
        ul.append(
            movimientoSolicitud(movimiento)
        );        
    });

    cuerpoHistorico.append(ul);
}

function showFiles(solicitud) {

    let arrayArchivos = solicitud.archivos;

    let dvArchivos = $("#dvArchivos");    
    if (dvArchivos != undefined) { dvArchivos.remove();}    

    if (arrayArchivos.length == 0) { return; }

    let div = $("<div/>", {
        id:"dvArchivos",
        class: "col-sm-6"
    });

    $("<label/>", {
        class:"col-sm-12",
        style:"padding:0;"
    }).append(
        $("<strong/>",{
            text: "Archivo(s)"
        })
    ).appendTo(div);

    arrayArchivos.forEach(file => {
        let
            a = $("<a/>", {
                href: file.ruta,
                text: file.nombre,
                class: "file col-sm-12",
                download: file.nombre,

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
/* Fin de funciones */


/* Eventos */
frmHistoricoSolicitud.on('submit', (e)=>{
    e.preventDefault();
    let idSolicitud = $(e.target[0]).val();
    getResumenSolicitud(idSolicitud);
    getHistoricoSolicitud(idSolicitud);
});
/* Fin de eventos */