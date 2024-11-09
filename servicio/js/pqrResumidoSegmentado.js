/*Declaraciones globales*/
const frm = $("form");
const tdTituloArchivo   = $("#tdTituloArchivo").css({"font-weight": "bold", "text-align": "center"});
const tblReporteTbody   = $("#tblReporte tbody");
const slcDepartamento   = $("#slcDepartamento");
const slcProyecto       = $("#slcProyecto");
const dtFechaInicio     = $("#dtFechaInicio");
const dtFechaFin        = $("#dtFechaFin");
/*Fin de declaraciones globales*/

/*Funciones*/
    const proyectos = () =>{
        /*Obtiene los proyectos que existen. Ejemplo: CAASD o CORAABO*/

        let options = {url:"../datos/datos.pqrResumidoSegmentado.php", type: "GET", data:{tip:'proyectos'}};
        $.ajax(options).done((res)=>{
            let json = JSON.parse(res);
            json.forEach((proyecto)=>{
                slcProyecto.append(new Option(proyecto.descripcion, proyecto.codigo));
            });

        }).fail((error) => console.error(error));
    };

    const departamentos = () =>{

        /*Función que obtiene los departamentos y llena el select correspondiente.*/

        let options = {url:"../datos/datos.pqrResumidoSegmentado.php", type:"GET", data:{tip:"departamentos"}};
        $.ajax(options).done((res)=>{
            let json = JSON.parse(res);

            json.forEach((departamento) => {
                slcDepartamento.append(new Option(departamento.descripcion, departamento.codigo));
            });
        });
    };

    const filtrarDatos = (data,criterio) => {
        //Función que filtra datos de un arreglo dado un criterio.
        return data.filter(criterio);
    };

    const total = (data = [], buscarPor) => {
        //Función para obtener el total de una columna específica.

        let total = 0;

        if(data.length == 0) return total;

        switch (buscarPor) {
            case 'generados':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.generados);
                });
                break;
            case 'pendientes':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.pendientes);
                });
                break;
            case 'procedentes':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.procedente);
                });
                break;
            case 'no_procedentes':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.no_procedente);
                });
                break;
            case 'ps_incorrecta':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.ps_incorrecta);
                });
                break;    
            case 'total_cerrados':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.total_cerrado);
                });
                break;
            case 'dentro_tiempo':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.dentro_tiempo);
                });
                break;
            case 'fuera_tiempo':
                data.forEach((pqr) =>{
                    total += parseInt(pqr.fuera_tiempo);
                });
                break;
            case 'tiempo_promedio':
                data.forEach((pqr) =>{
                    total += parseFloat(pqr.tiempo_promedio);
                });
                break;
            case 'efectividad':
                /* En caso de que se desee que el total de la efectividad sea como promedio descomentar*/

                /* let cerrados = data
                .filter(pqr => pqr.total_cerrado > 0)
                .length; */

                data.forEach((pqr) =>{
                    total += parseFloat(pqr.efectividad);
                });

                //total  /= (cerrados == 0) ? 1 : cerrados ; */
                break;
        }

        return total;
    };

    const generarReporte = () => {

        /*Obtiene los datos del reporte dado los parámetros del formulario.*/

        let data = frm.serializeArray();
        data.push({name:'tip',value:'generarReporte'});

        let options = {url:"../datos/datos.pqrResumidoSegmentado.php",method:"GET", data:data, res:"json"};
        $.ajax(options).done((res) => {

            let json = JSON.parse(res);
            if (json.codigo == 200 ) generarTabla(json.mensaje);
            else{
                swal("Información","Ocurrió un error al intentar generar el reporte. Contacte al departamento de desarrollo.","error");
                console.error("Código: " + json.codigo +"Mensaje: "+json.mensaje);
            } 

        }).fail((error) => {
            swal("Información","Ocurrió un error al intentar generar el reporte. Contacte al departamento de desarrollo.","error");
            console.error(error)
        });
    };

    const generarTabla = (data) => {

        /*Función para dibujar la tabla que posteriormente será exportada a excel.*/

        /*Obtenemos el nombre del archivo*/
        var departamento = "";
        if (slcDepartamento.find('option:selected').val() !== "")
            departamento = slcDepartamento.find('option:selected').text();

        var proyecto = "";
        if (slcProyecto.find('option:selected').val() !== "")
            proyecto = slcProyecto.find('option:selected').text();

        let tituloArchivo = 'PQRs resumido segmentado ' + proyecto + ' ' + departamento + ' ' + dtFechaInicio.val() + ' hasta ' + dtFechaFin.val();

        tdTituloArchivo.prop("colspan", 13);
        tdTituloArchivo.html(tituloArchivo);
        /*Fin de obtención del nombre del archivo*/

        let tipoReclamo = "";
        departamento = "";

        tblReporteTbody.empty();
        data.forEach((pqrs, index) => {

            let trTipoReclamo = $("<tr/>");

            if ((tipoReclamo != pqrs.tipo_reclamo) || (pqrs.departamento != departamento)) {

                //Dibuja la palabra "Reclamo" o "solicitud"  en la tabla si el tipo de reclamo o departamento varía.

                tipoReclamo = pqrs.tipo_reclamo;
                departamento = pqrs.departamento;
                $("<td/>", {
                    text: tipoReclamo,
                    style: "border-style: none; font-weight: bold;"
                }).appendTo(trTipoReclamo);
                tblReporteTbody.append(trTipoReclamo);
            }

            // Datos según un tipo y motivo de reclamo, y departamento.
            let tr = $("<tr/>");
            $("<td/>", {text: pqrs.departamento}).appendTo(tr);
            $("<td/>", {text: pqrs.codigo_reclamo}).appendTo(tr);
            $("<td/>", {text: pqrs.tipo_pqr}).appendTo(tr);
            $("<td/>", {text: pqrs.generados}).appendTo(tr);
            $("<td/>", {text: pqrs.pendientes}).appendTo(tr);
            $("<td/>", {text: pqrs.procedente}).appendTo(tr);
            $("<td/>", {text: pqrs.no_procedente}).appendTo(tr);
            $("<td/>", {text: pqrs.ps_incorrecta}).appendTo(tr);
            $("<td/>", {text: pqrs.total_cerrado}).appendTo(tr);
            $("<td/>", {text: pqrs.dentro_tiempo}).appendTo(tr);
            $("<td/>", {text: pqrs.fuera_tiempo}).appendTo(tr);
            $("<td/>", {text: pqrs.tiempo_promedio}).appendTo(tr);
            $("<td/>", {text: pqrs.efectividad}).appendTo(tr);
            tblReporteTbody.append(tr);
            // Datos según un tipo y motivo de reclamo, y departamento.

            /*
                Total según columna (generados, pendientes, procedentes, no_procedentes, total_cerrados, dentro_tiempo, fuera_tiempo, tiempo_promedio, efectividad).
                El total se debe obtener si los datos de la próxima fila pertenecen a otro departamento, o si es la última fila del deporte.

            */
            if ((typeof data[index + 1] !== 'undefined' && (data[index + 1].tipo_reclamo != tipoReclamo || data[index + 1].departamento != departamento)) || typeof data[index + 1] === 'undefined') {

                let datosFiltrados = filtrarDatos(data, (filtrado) => {
                    return filtrado.tipo_reclamo == pqrs.tipo_reclamo && filtrado.departamento == pqrs.departamento
                });

                let trTotal = $("<tr/>");
                $("<td/>", {
                    text: "Total",
                    style: "border-right: none; font-weight:bold;background-color:#81bfe1;",
                    colspan:3
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'generados'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'pendientes'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'procedentes'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'no_procedentes'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'ps_incorrecta'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'total_cerrados'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'dentro_tiempo'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'fuera_tiempo'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'tiempo_promedio'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'efectividad'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);
                $("<td/>", {
                    text: total(datosFiltrados, 'dentro_tiempo') / total(datosFiltrados, 'total_cerrados'),
                    style: "font-weight:bold;background-color:#81bfe1;"
                }).appendTo(trTotal);

                tblReporteTbody.append(trTotal);
            }
        });


        //Filas en blanco
        $("<tr/>").appendTo(tblReporteTbody);
        $("<tr/>").appendTo(tblReporteTbody);

        //Todos los datos de reclamos y solicitudes.
        let reclamos = filtrarDatos(data, function (filtrado) {
            return filtrado.tipo_reclamo == "RECLAMO"
        });
        let solicitudes = filtrarDatos(data, function (filtrado) {
            return filtrado.tipo_reclamo == "SOLICITUD"
        });

        //Fila del total general de reclamos
        let trTotalReclamos = $("<tr/>");
        $("<td/>", {
            text: "Total de reclamos",
            style: "border-right: none; font-weight:bold;background-color:#e4e5e6;",
            colspan:3
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'generados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'pendientes'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'procedentes'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'no_procedentes'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'ps_incorrecta'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'total_cerrados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'dentro_tiempo'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'fuera_tiempo'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'tiempo_promedio'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'efectividad'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        $("<td/>", {
            text: total(reclamos, 'dentro_tiempo') / total(reclamos, 'total_cerrados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamos);
        tblReporteTbody.append(trTotalReclamos);

        //Fila del total general de solicitudes
        let trTotalSolicitudes = $("<tr/>");
        $("<td/>", {
            text: "Total de solicitudes",
            style: "border-right: none; font-weight:bold;background-color:#e4e5e6;",
            colspan:3
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'generados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(reclamos, 'pendientes'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'procedentes'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'no_procedentes'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'ps_incorrecta'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'total_cerrados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'dentro_tiempo'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'fuera_tiempo'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'tiempo_promedio'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'efectividad'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        $("<td/>", {
            text: total(solicitudes, 'dentro_tiempo') / total(solicitudes, 'total_cerrados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudes);
        tblReporteTbody.append(trTotalSolicitudes);

        //Fila del efectividad de solicitudes en pestaña incorrecta
        let trTotalSolicitudesPestInc = $("<tr/>");
        $("<td/>", {
            text: "Total de efectividad en solicitudes en pestañas incorrecta",
            style: "border-right: none; font-weight:bold;background-color:#e4e5e6;",
            colspan: 3
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: total(solicitudes, 'ps_incorrecta') / total(solicitudes, 'generados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalSolicitudesPestInc);
        tblReporteTbody.append(trTotalSolicitudesPestInc);

        //Fila del efectividad de reclamos en pestaña incorrecta
        let trTotalReclamosPestInc = $("<tr/>");
        $("<td/>", {
            text: "Total de efectividad en reclamos en pestañas incorrecta",
            style: "border-right: none; font-weight:bold;background-color:#e4e5e6;",
            colspan: 3
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: total(reclamos, 'ps_incorrecta') / total(reclamos, 'generados'),
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        $("<td/>", {
            text: "0",
            style: "font-weight:bold;background-color:#e4e5e6;"
        }).appendTo(trTotalReclamosPestInc);
        tblReporteTbody.append(trTotalReclamosPestInc);

                

        tableToExcel('tblReporte',tituloArchivo); //Genera el archivo excel dada una tabla.
        swal("Éxito","Reporte generado exitosamente.","success"); //Cuadro de mensaje.
    };

    const compSession = (callback) => {
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
};
/*Fin funciones*/


/*Eventos */
$(document).ready(()=>{
    compSession(departamentos);
    compSession(proyectos);
});

frm.on("submit",function(e){
     e.preventDefault();
    swal
    ({
            title: "Advertencia!",
            text: "El reporte puede tardar algunos minutos en generarse.",
            type: "info",
            showConfirmButton: true,
            confirmButtonText: "Continuar!",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm){
                compSession(generarReporte);
            }
        });

});
/*Fin de eventos */

