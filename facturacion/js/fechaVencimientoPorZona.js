$(document).ready(function(){

    getProyectos();
    $("#frmFecVencPorZona").submit(function(){

        swal
        ({
                title: "Advertencia!",
                text: "El reporte puede tardar algunos minutos en generarse.",
                type: "info",
                showConfirmButton: true,
                confirmButtonText: "Continuar!",
                cancelButtonText: "Cancelar!",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm)
            {
                if (isConfirm)
                {
                    compSession(getReporte);
                }
            });

    });


});

function getReporte(){

    var datos = $("#frmFecVencPorZona").serializeArray();
    datos.push({name:'tip',value:'getFechaVencPorZona'});
    $.post("../datos/datos.fechaVencimientoPorZona.php",datos,function(res){
        if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
            $('#dataTable').DataTable().destroy();
        }
        if (res!=null){

            swal("Mensaje!", "Has Generado correctamente el reporte", "success");
            $('#dataTable').DataTable( {
                data: res,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt'},
                    { extend: 'pdf',  text:' PDF',className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        title:'Reporte de inmuebles facturados, Recaudado y adeudado'}
                ],
                columns: [
                    { title: "Fecha de vencimiento" },
                    { title: "Fecha de corte" },
                    { title: "Sector" },
                    { title: "Zona" }
        ],

                "info":     false,
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
    },'json');
}

function getProyectos() {
    $.post("../datos/datos.facturadosAdeudadosRutSect.php",{tip:'getProyectos'},function(data){
        for(var i=0;i<data.length;i++)
        {
            $('#cmbProyecto').append(new Option(data[i]["DESCRIPCION"],data[i]["CODIGO"],false,false));
        }},'json');

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