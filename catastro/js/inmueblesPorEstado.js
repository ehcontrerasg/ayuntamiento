$(document).ready(function(){

    compSession(cargarProyectos());
    compSession(cargarUsos());
    compSession(cargarTipoCliente());
    $("#selectUso").change(function(){
        cargarActividades($(this).val());
    });
    $("#selectProyecto").change(function(){
        cargarZonas($(this).val());
    });
    compSession(cargarServicios());

    $("#frmReporteInmueblePorEstado").submit(getReporte);
    $("#btnExportarExcel").click(function(){
       swal
       ({
               title: "Advertencia!",
               text: "El reporte puede tardar algunos minutos en generarse.",
               type: "info",
               showConfirmButton: true,
               confirmButtonText: "Continuar!",
               cancelButtonText: "Cancelar!",
               showCancelButton: true,
               showLoaderOnConfirm: false,
               closeOnConfirm: false,
               closeOnCancel: true
           },
           function(isConfirm)
           {
               if (isConfirm) {
                   swal("Mensaje!", "Has Generado correctamente el reporte", "success");
                   compSession(exportarReporteExcel(datos));
                   //setInterval(borrarTemporarl,60000);

                   //window.close();
               }
           });

    });

});



function cargarProyectos(){
    $.ajax({
        method:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data:{tip:"getProyectos"},
        datatype:"JSON",
        success:function(json){
            var proyectos=  JSON.parse(json);
            var selectProyecto=  $("#selectProyecto");
            selectProyecto.append("<option value=''></option>");
            for(var indice = 0; indice<proyectos.length;indice++){
                $("#selectProyecto").append(
                    "<option value="+proyectos[indice][0]+">"+proyectos[indice][1]+"</option>"

                );
            }
        },
        error:function(jqXHR,exeption){
            console.log(jqXHR+" "+exeption);
        }
    });
}
function cargarUsos(){
    $.ajax({
        type:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data:{tip:"getUsos"},
        datatype:'json',
        success:function(res){

            var usos = JSON.parse(res);
            var selectUso=  $("#selectUso");
            selectUso.append("<option value=''></option>");

            for(var indice=0;indice<usos.length;indice++){
                    selectUso.append(
                    "<option value="+usos[indice][0]+">"+usos[indice][1]+"</option>"
                );
            }},
        error:function(jqXHR,exeption){
            console.log(jqXHR+" "+exeption);
        }
    })
}

function cargarTipoCliente(){
    $.ajax({
        type:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data:{tip:"getTipoCliente"},
        datatype:'json',
        success:function(res){

            var clientes = JSON.parse(res);
            var selectCliente=  $("#selectCliente");
            selectCliente.append("<option value=''></option>");

            for(var indice=0;indice<clientes.length;indice++){
                selectCliente.append(
                    "<option value="+clientes[indice][0]+">"+clientes[indice][1]+"</option>"
                );
            }},
        error:function(jqXHR,exeption){
            console.log(jqXHR+" "+exeption);
        }
    })
}

function cargarActividades(idUso){
    $.ajax({
        type:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data:{tip:"getActividadPorUso",uso:idUso},
        datatype:'json',
        success:function(res){

            var actividades = JSON.parse(res);
            var selectActividades=  $("#selectActividad");
            selectActividades.empty();
            selectActividades.append("<option value=''></option>");
            for(var indice=0;indice<actividades.length;indice++){
                selectActividades.append(
                    "<option value="+actividades[indice][0]+">"+actividades[indice][1]+"</option>"
                );
            }},
        error:function(jqXHR,exeption){
            console.log(jqXHR+" "+exeption);
        }
    })
}

function cargarZonas(idProyecto){
    $.ajax({
        type:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data:{tip:"getZonaPorProyecto",proyecto:idProyecto},
        datatype:'json',
        success:function(res){

            var zonas = JSON.parse(res);
            var selectZonas=  $("#selectZona");
            selectZonas.empty();
            selectZonas.append("<option value=''></option>");
            for(var indice=0;indice<zonas.length;indice++){
                selectZonas.append(
                    "<option value="+zonas[indice][0]+">"+zonas[indice][1]+"</option>"
                );
            }},
        error:function(jqXHR,exeption){
            console.log(jqXHR+" "+exeption);
        }
    })
}


function cargarServicios(){
    $.ajax({
        type:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data:{tip:"getServicios"},
        datatype:'json',
        success:function(res){

            var servicios = JSON.parse(res);
            var selectServicio=$("#selectServicio");
            selectServicio.append("<option value=''></option>");
            for(var indice=0;indice<servicios.length;indice++){
                selectServicio.append(
                    "<option value="+servicios[indice][0]+">"+servicios[indice][1]+"</option>"
                );
            }},
        error:function(jqXHR,exeption){
            console.log(jqXHR+" "+exeption);
        }
    })
}


var datos;
function getReporte(){

    datos = $("#frmReporteInmueblePorEstado").serializeArray();
    datos.push({name:"tip",value:"getReporte"});
    var datatable= $('#dataTable').dataTable();

    console.log(datos);
    datatable.css("display",'block');
    $("#btnExportarExcel").css("display",'block');

    if ( $.fn.dataTable.isDataTable( datatable ) ) {
        $('#dataTable').DataTable().destroy();
    }
    $('#dataTable').DataTable({
        "bProcessing":    true,
        "serverSide":     true,
        "searching":      false,
        "lengthChange":   false,
        //"scrollY":        "700px",
        //"scrollX":        "100%",
        "scrollCollapse": true,
        "autoWidth":      false,
        //"fixedColumns":   true,
        "columnDefs": [
            { "width": "20%", "targets": 0 }
        ],
        "ajax":{
            url :"../datos/datos.inmueblesPorEstado.php", // json datasource
            type: "post",  // type of method  , by default would be get
            data:{proyecto:     datos[0]["value"],
                proceso_inicial:datos[1]["value"],
                proceso_final:  datos[2]["value"],
                manzana_inicial:datos[3]["value"],
                manzana_final:  datos[4]["value"],
                estado_inicial: datos[5]["value"],
                estado_final:   datos[6]["value"],
                uso:            datos[7]["value"],
                actividad:      datos[8]["value"],
                servicio:       datos[9]["value"],
                idzona:         datos[10]["value"],
                tipcliente:     datos[11]["value"],
                tip:            datos[12]["value"]




            },
            error: function(jsxhr,exception){  // error handling code
               console.log(jsxhr+" "+exception);
            }
        }
    });
}

function exportarReporteExcel(datos){


    $("#tablaExportar").empty();
    $.ajax({
        type:"POST",
        url: "../datos/datos.inmueblesPorEstado.php",
        async: false,
        data:{
            proyecto:datos[0]["value"],
            proceso_inicial:datos[1]["value"],
            proceso_final:datos[2]["value"],
            manzana_inicial:datos[3]["value"],
            manzana_final:datos[4]["value"],
            estado_inicial:datos[5]["value"],
            estado_final:datos[6]["value"],
            uso:datos[7]["value"],
            actividad:datos[8]["value"],
            servicio:datos[9]["value"],
            idzona:datos[10]["value"],
            tipcliente:datos[11]["value"],


            tip:"exportarTabla"
        },
        success:function(res){
            if (res.substr(0,11)=="../../temp/"){

                window.location.href = res;


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
        error:function(jsxhr,exception){
            console.log(jsxhr+" "+exception);
        }
    });
}


function borrarTemporarl(){
    $.ajax({
        type:"POST",
        url:"../datos/datos.inmueblesPorEstado.php",
        data: {tip:"borrarTemporal"},
        success:function(res){
            console.log(res);
        },
        error:function(jqxhr,exception){
            console.log(jqxhr+" "+exception);
    }
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


