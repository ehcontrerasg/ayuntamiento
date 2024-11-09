$(document).ready(function(){
    getProyectos();
    getUsos();
    $("#frmFactAdeSectUso").submit(function(){

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
                    compSession(getReport);
                }
            });

    });
});

function getProyectos() {
    $.post("../datos/datos.FactRecSectorUso.php",{tip:'getProyectos'},function(data){
        for(var i=0;i<data.length;i++)
        {
            $('#cmbProyecto').append(new Option(data[i]["DESCRIPCION"],data[i]["CODIGO"],false,false));
        }},'json');

}
function getUsos(){
    $.post("../datos/datos.FactRecSectorUso.php",{tip:'getUsos'},function(data){
        $('#cmbUso').append(new Option('','',false,false));

        for(var i=0;i<data.length;i++)
        {
            $('#cmbUso').append(new Option(data[i]["DESCRIPCION"],data[i]["CODIGO"],false,false));
        }},'json');

}
function getReport()
{
    var datos  = $("#frmFactAdeSectUso").serializeArray();
    datos.push({name:'tip',value:'getReporteFacturadosAdeudados'});
    $.post("../datos/datos.FactRecSectorUso.php",datos,function(res){
        if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
            $('#dataTable').DataTable().destroy();
        }
        if (res!=null){

            console.log(res);
            swal("Mensaje!", "Has Generado correctamente el reporte", "success");
            $('#dataTable').DataTable( {
                data: JSON.parse(res),
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text:' Excel',
                        className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        title: 'Reporte de inmuebles facturados, Recaudado y adeudado'

                    },

                    {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' }
                ],
                columns: [
                    { title: "FACTURADO" },
                    { title: "POLIZAS AFECTADAS" },
                    { title: "RECAUDADOS" },
                    {title:"USUARIOS RECAUDADOS"},
                    { title: "USO" }
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
