/**
 * Created by PC on 7/7/2016.
 */

$(document).ready(function() {
    desContr();
    compSession();

    compSession(genOrdLleSelPro);
    compSession(genSelOperario);

    $("#selProy").change(
        function () {
            compSession(genSelZona);
        }
    );

    $("#formularioRepSup").submit(
        function(){
            compSession(getReportRepSup);
        }
    );


});

function genOrdLleSelPro(){
    $.ajax
    ({
        url : '../datos/datos.repSupervisionFac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selProy').empty();
            $('#selProy').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selProy').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function genSelZona(){
    $.ajax
    ({
        url : '../datos/datos.repSupervisionFac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selZona',proy: $("#selProy").val() },
        success : function(json) {
            $('#selZon').empty();
            $("#selZon").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#selZon").append(new Option(json[x]["ID_ZONA"], json[x]["ID_ZONA"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}



function genSelOperario(){
    $.ajax
    ({
        url : '../datos/datos.supervisionFac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selOper' },
        success : function(json) {
            $('#selOper').empty();
            $("#selOper").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#selOper").append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function getReportRepSup()
{

    $.post("../datos/datos.repSupervisionFac.php",{tip:"reporte",pro:$("#selProy").val(),zon:$("#selZon").val(),
    fech:$("#inpFecha").val(),oper:$("#selOper").val()
    },function(res){
        /*var dat = JSON.parse(res);*/
        if ( $.fn.dataTable.isDataTable( '#dataTableRepSup' ) ) {
            $('#dataTableRepSup').DataTable().destroy();
        }
        table=$('#dataTableRepSup').DataTable( {
            data: JSON.parse(res),
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', text:' Copiar',  className: 'btn btn-primary glyphicon glyphicon-duplicate' },
                { extend: 'csv',  text:' CVS', className: 'btn btn-primary glyphicon glyphicon-save-file' },
                { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt' },
                { extend: 'pdf',  text:' PDF',  className: 'btn btn-primary glyphicon glyphicon-file' },
                {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' }
            ],
            columns: [
                { title: "fecha planificacion"},
                { title: "Zona" },
                { title: "Ruta" },
                { title: "Supervisor" },
                { title: "Planificadas" },
                { title: "Ejecutadas" },
                { title: "Exitosas" },
                { title: "% de ejecucion" },
                { title: "% de exito" },
            ],



            language:
                {
                    "url":"//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },

            "info":     false,
            "order": [[ 2, "desc" ]],
            "paging" : true,

        });
        //getParametroSeleccionado("#dataTable tbody",table);
        $('#dataTable').show();

    });
}



function generaListadoRepSup(){
    var params=$('#formularioRepSup').serializeArray();
    params.push({name: 'tip', value: 'genListado'});
    $.ajax
    ({
        url : '../datos/datos.repSupervisionFac.php',
        type : 'POST',
        dataType : 'json',
        data : params,
        success : function(json) {
            if (json["res"]=="true")
            {
                swal("Mensaje!", "Has generado satisfactoriamente las ordenes", "success");
            }else if(json["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: datos["error"],
                        type: "error",
                        html:true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Aceptar!",
                        closeOnConfirm: true

                    }
                );
            }
        },
        error : function(xhr, status) {

        }
    });

}

function compSession(callback){
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

                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password'  placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
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



function iniSes(){
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data : { tip : 'iniSes',pas:$("#pass").val(),usu:$("#usr").val()},
        dataType : 'text',
        success : function(json) {
            if (json=="true"){
                swal("Loggin Exitoso!")
                compSession(RepPerf);
            }else if(json=="false"){
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        inputPlaceholder: "Write something",
                        text: "Usuario o Contraseña  incorrecta.<br>" +
                            " <input type='text' class='estilo-inp' placeholder='Usuario' id='usr'><br> <input  placeholder='Password' tabindex='4' class='estilo-inp' type='password' id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },
                    function(i){
                        if (i === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{

                            iniSes();
                        }
                    });
            }
        },
        error : function(xhr, status) {
            return false;
        }
    });

}

function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu",function(e){return false;});

}


