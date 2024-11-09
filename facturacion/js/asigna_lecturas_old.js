var xid=0;
$(document).ready(function(){
    //desContr();
    compSession();
    compSession(llenarSpinerPro);



    $("#asiLecFecPla").focus(
        function () {
            var d = new Date();
            var montMin=(d.getMonth()+1);
            var montMax= (d.getMonth()+1);
            if(montMin<10){
                montMin='0'+montMin
            }
            if(montMax<10){
                montMax='0'+montMax
            }
            var strDate = d.getFullYear() + "-" + montMin + "-" + d.getDate();
            var strDate2 = d.getFullYear() + "-" + montMax + "-" + (d.getDate()+1);
            $("#asiLecFecPla").prop('min',strDate);
            $("#asiLecFecPla").prop('max',strDate2);
        }
    )

    $("#asiLecForm").submit(
        function(){
            swal
            ({
                title: "Advertencia!",
                text: "El listado puede demorar algunos segundos en salir.",
                html: true,
                type: "info",
                showConfirmButton: true,
                showCancelButton: true,
                closeOnConfirm: false,
                closeOnCancel: true,
                confirmButtonColor: "#17a22b",
                cancelButtonColor: "#d55151",
                confirmButtonText: "OK!",
                cancelButtonText: "Cancelar",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    compSession(getOrdenes);
                }
            });
        }
    )

    $('#asiLecRutForm').submit(
        function(){
            compSession(asignaRutas);
        }
    )

    $('#asiLecPro').change(
        function(){
            compSession(llenarSpinerPer);
        }
    )

    $('#asiLecPer').change(
        function(){
            compSession(llenarSpinerZon);
        }
    )


});

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

function llenarSpinerPro()
{
    $.ajax
    ({
        url : '../datos/datos.asigna_lecturas_old.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#asiLecPro').append(new Option('Seleccione Proyecto..', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiLecPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSpinerPer()
{
    $.ajax
    ({
        url : '../datos/datos.asigna_lecturas_old.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPer' ,pro:$('#asiLecPro').val()},
        success : function(json) {
            $('#asiLecPer').empty();
            $('#asiLecPer').append(new Option('Seleccione Periodo...', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiLecPer').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSpinerZon()
{
    $.ajax
    ({
        url : '../datos/datos.asigna_lecturas_old.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selZon' ,pro:$('#asiLecPro').val() ,per:$('#asiLecPer').val()},
        success : function(json) {
            $('#asiLecZon').empty();
            $('#asiLecZon').append(new Option('Seleccione Zona...', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiLecZon').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function getOrdenes()
{
    var campos= $('#asiLecForm').serializeArray();
    campos.push({name: 'tip', value: 'selRutAsig'});
    $.ajax
    ({
        url : '../datos/datos.asigna_lecturas_old.php',
        type : 'POST',
        dataType : 'json',
        data :  campos,
        success : function(json) {
            if (json) {
                swal("Mensaje!", "Has Generado correctamente las rutas", "success");

                $('#asiLecRutForm').empty();

                var pEspacio = $('<br/>', {
                    'class': ''
                });

                var divTitulo = $('<div/>', {
                    'class': 'subCabecera ',
                    'text': 'Listado Rutas de Lecturas - Periodo ' + $("#asiLecPer").val() + ' Zona ' + $("#asiLecZon").val()
                });

                $('#asiLecRutForm').append(pEspacio);
                $('#asiLecRutForm').append(divTitulo);

                var divTable = $('<div/>', {
                    'class': 'table-responsive'
                });
                var table = $('<table/>', {
                    'id': 'asiLecTable',
                    'class': 'display table',
                    'cellspacing': '0',
                    'width': '100%'
                });
                var tHead = $('<thead/>', {
                    'class': ''
                });
                var trHead = $('<tr/>', {
                    'class': ''
                });
                var th1 = $('<th/>', {
                    'class': 'text-left',
                    'text': 'No'
                });
                var th2 = $('<th/>', {
                    'class': 'text-left',
                    'text': 'Ruta'
                });
                var th3 = $('<th/>', {
                    'class': 'text-left',
                    'text': 'Cantidad'
                });
                var th4 = $('<th/>', {
                    'class': 'text-left',
                    'text': 'Operario'
                });

                trHead.append(th1);
                trHead.append(th2);
                trHead.append(th3);
                trHead.append(th4);
                tHead.append(trHead);
                table.append(tHead);

                var tBody = $('<tbody/>', {
                    'class': ''
                });

                table.append(tBody);

                for (var x = 0; x < json.length; x++) {
                    var trBody = $('<tr/>', {
                        'class': ''
                    });
                    var tdDatos1= $('<td/>', {
                        'class': 'text-left',
                        'text': x + 1,
                        'id': 'ruta' + x
                    });
                    var tdDatos2= $('<td/>', {
                        'class': 'text-left',
                        'text': json[x]["RUTA"]
                    });
                    var tdDatos3 = $('<td/>', {
                        'class': 'text-left ',
                        'text': json[x]["CANTIDAD"]
                    });
                   var tdDatos4 = $('<td/>', {
                        'class': 'text-left '
                    });

                   var sel1=$('<select/>', {
                       'class': 'form-control',
                       'id':'usu'+x

                     });

                    trBody.append(tdDatos1);
                    trBody.append(tdDatos2);
                    trBody.append(tdDatos3);
                    trBody.append(tdDatos4);
                    tdDatos4.append(sel1);
                    llenarSpinerUsu(sel1,json[x]["USUARIO"]);
                    tBody.append(trBody);
                    table.append(tBody);
                }
                table.append(trBody);
                divTable.append(table);

                $('#asiLecRutForm').append(divTable);
                $('#asiLecTable').DataTable();

                xid = x;
                var spanExt = $('<div/>', {
                    'class': 'col-lg-12 col-md-12 col-sm-12 col-xs-12'
                });

                var inp = $('<button/>', {
                    'id': 'asiRutLecButAsig',
                    'class': 'btn btn-primary btn-lg ',
                    'type': 'submit',
                    'text': 'Asignar'
                });
                spanExt.append(inp);
                $('#asiLecRutForm').append(spanExt);

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
        error : function(xhr, status) {

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
                swal({
                    title: "Loggin Exitoso!",
                    html: true,
                    type: "success",
                    showCancelButton: false,
                    closeOnConfirm: true,
                    confirmButtonColor: "#17a22b",
                    confirmButtonText: "OK!",
                    cancelButtonText: "No!"
                });
                compSession(llenarSpinerPro);
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
                            $("#ingResInsInpCodSis").focus();
                            iniSes();
                        }
                    });
            }
        },
        error : function(xhr, status) {
            alert("error");
            return false;
        }
    });

}

function asignaRutas(){
    for(i=0;i<xid;i++){
        if($("#usu"+i).val().trim()!=''){
            asignador($("#usu"+i).val(),$("#ruta"+i).text());
        }


    }
}

function llenarSpinerUsu(selUsr,idusr)
{
    $.ajax
    ({
        url : '../datos/datos.asigna_lecturas_old.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'SelUsu',proyecto:$('#asiLecPro').val()},
        success : function(json) {
            selUsr.empty();
            selUsr.append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                if(idusr==json[x]["CODIGO"]){
                    selUsr.append(new Option(json[x]["NOMBRE"], json[x]["CODIGO"], true, true));
                }else{
                    selUsr.append(new Option(json[x]["NOMBRE"], json[x]["CODIGO"], false, false));
                }

            }
        },
        error : function(xhr, status) {
        }
    });
}

function asignador(usu,rut){
    var datos =$("#asiLecForm").serializeArray();
    datos.push({name: 'tip', value: 'asig'});
    datos.push({name: 'usu', value: usu});
    datos.push({name: 'rut', value: rut});

    $.ajax
    ({
        url : '../datos/datos.asigna_lecturas_old.php',
        type : 'POST',
        data :datos,
        dataType : 'json',
        success : function(json) {
            if (json["res"]=="true"){

            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
            }
        },
        error : function(xhr, status) {

            return false;
        }
    });

}