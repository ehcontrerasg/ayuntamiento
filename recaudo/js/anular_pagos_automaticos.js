
$(document).ready(function() {

    //anularPago("11274");
    var fechaDesde = $("#fechaDesde");
    var fechaHasta = $("#fechaHasta");
    var form       = $("#frmPagosAutomaticos");

    form.submit(function(){
        var fecha_desde = fechaDesde.val();
        var fecha_hasta = fechaHasta.val();
        getPagosAutomaticos(fecha_desde,fecha_hasta)
    });

    $("#dataTable").on("click",".btnAnularPago",function(){

        var row  =  $(this).closest('tr');
        var data =  $("#dataTable").DataTable().row(row).data();
        var id_pago = data[0];
        $("#idPago").val(id_pago);
        $("#modalAnular").modal({backdrop: 'static', keyboard: false});
    });

    $("#btnAnularPagos").on("click",function(){

        if($("#txtMotivoAnulacion").val() !=" " ){
            var id_pago = $("#idPago").val();
            swal
            ({
                    title: "Advertencia!",
                    text: "¿Seguro que desea anular el pago "+id_pago+"?",
                    type: "info",
                    showConfirmButton: true,
                    confirmButtonText: "Continuar!",
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                            compSession(anularPago(id_pago));
                    }
                });
        }else{
            swal("Error!","Debe indicar un motivo de anulación.","error");
        }


    });
});

function getPagosAutomaticos(fecha_desde,fecha_hasta) {

    var datos = {tip:"getPagosAutomaticos",fechaDesde:fecha_desde ,fechaHasta:fecha_hasta};
    var dataTable  = $("#dataTable");
    $.ajax({
        type:"POST",
        url : "../datos/datos.anular_pagos_automaticos.php",
        data: datos,
        success: function(res){
            if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                dataTable.DataTable().destroy();
            }
            dataTable.DataTable( {
                data: JSON.parse(res),
                columns: [
                    { title: "CODIGO DE REFERENCIA" },
                    { title: "INMUEBLE" },
                    { title: "PROYECTO" },
                    { title: "MONTO" },
                    { title: "FECHA PAGO" },
                    { title: "ID PAGO"},
                    { title: "ID RECAUDO"},
                    { title: "ACCION"}
                ],
                "info":     false,
                "order": [[ 2, "desc" ]],
                "paging" : true
            });
            dataTable.show();
        },
        error: function(xhrjs,error){
            alert("XHRJS: "+xhrjs+", Error: "+error)
        }

    });

}


function anularPago(id_pago) {
    var fechaDesde = $("#fechaDesde");
    var fechaHasta = $("#fechaHasta");

    var motivo = $("#txtMotivoAnulacion").val();
    var fecha_desde = fechaDesde.val();
    var fecha_hasta = fechaHasta.val();

    $.ajax({
            type:"POST",
            url: "../datos/datos.anular_pagos_automaticos.php",
            data: {tip:'anularPago',codigo_referencia:id_pago,motivo:motivo},
            success:function(res){
                var data = JSON.parse(res);
                if(data.status == 0){
                swal("Éxito!",data.mensaje,"success");

                     $("#txtMotivoAnulacion").val('');
                    getPagosAutomaticos(fecha_desde,fecha_hasta);
                    $('#modalAnular').modal('toggle');

                }else{
                    swal("Error!", data.mensaje, "error");
                }

            },
             error: function(xhrjs, error){
                swal("Error!", data.mensaje, "error");
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
