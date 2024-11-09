/**
 * Created by Tecnologia on 17/8/2018.
 */
$(document).ready(function(){

    $("#dataTable").hide();
    getProyectos();
 $("#frmFactAdeSectRut").submit(function(){

     swal
     ({
             title: "Advertencia!",
             text: "El reporte demorara unos minutos en salir.",
             showConfirmButton: true,
             showCancelButton: true,
             showLoaderOnConfirm: true,
             closeOnConfirm: false,
             closeOnCancel: true
         },
         function(isConfirm)
         {
             if (isConfirm)
             {
                 getReport();
             }
         });
 });


});

function getReport()
{
    var datos  =$("#frmFactAdeSectRut").serializeArray();
    datos.push({name:'tip',value:'getReporteFacturadosAdeudados'});
    $.post("../datos/datos.facturadosAdeudadosRutSect.php",datos,function(res){
        if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
            $('#dataTable').DataTable().destroy();
        }
        if (res!=null){

            swal("Mensaje!", "Has Generado correctamente el reporte", "success");
            $('#dataTable').DataTable( {
                data: JSON.parse(res),
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt'},
                    { extend: 'pdf',  text:' PDF',className: 'btn btn-primary glyphicon glyphicon-list-alt', title:'Reporte de inmuebles facturados, Recaudado y adeudado'}
                ],
                columns: [
                    { title: "FACTURADO" },
                    { title: "USUARIOS FACTURADOS" },
                    { title: "ADEUDADO" },
                    { title: "RECAUDADOS" },
                    { title: "SECTOR" },
                    { title: "RUTA"}
                ],

                "info":     false,
                "order": [[ 1, "asc" ]],
                "paging" : true
            });
            $('#dataTable').show();
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

    });

}
function getProyectos()
{
    $.post("../datos/datos.facturadosAdeudadosRutSect.php",{tip:'getProyectos'},function(data){
        for(var i=0;i<data.length;i++)
       {
           $('#cmbProyecto').append(new Option(data[i]["DESCRIPCION"],data[i]["CODIGO"],false,false));
       }},'json');

}