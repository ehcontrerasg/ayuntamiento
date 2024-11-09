var cantTot;
$(document).ready(function(){
    desContr();
    compSession();
    compSession(llenarSpinerPro);

    $("#revAleSelPro").change(
        function(){
            compSession(llenarSpinerUsu);
        }
    );


    $("#revAleFecFin").change(
        function(){
            $("#revAleCan").val('');
            $("#revAleCanTot").val('');
            if($("#revAleUsu").val()!="" && $("#revAleProIni").val()!="" && $("#revAleProFin").val()!="" && $("#revAleFecIni").val()!="" && $("#revAleFecFin").val()!="" ){
                compSession(obCantTot);
            }

        }
    )

    $("#revAleFecIni").change(
        function(){
            $("#revAleCan").val('');
            $("#revAleCanTot").val('');
            if($("#revAleUsu").val()!="" && $("#revAleProIni").val()!="" && $("#revAleProFin").val()!="" && $("#revAleFecIni").val()!="" && $("#revAleFecFin").val()!="" ){
                compSession(obCantTot);
            }

        }
    )

    $("#revAleUsu").change(
        function(){
            $("#revAleCan").val('');
            $("#revAleCanTot").val('');
            if($("#revAleUsu").val()!="" && $("#revAleProIni").val()!="" && $("#revAleProFin").val()!="" && $("#revAleFecIni").val()!="" && $("#revAleFecFin").val()!="" ){
                compSession(obCantTot);
            }

        }
    )

    $("#revAleProIni").change(
        function(){
            $("#revAleCan").val('');
            $("#revAleCanTot").val('');
            if($("#revAleUsu").val()!="" && $("#revAleProIni").val()!="" && $("#revAleProFin").val()!="" && $("#revAleFecIni").val()!="" && $("#revAleFecFin").val()!="" ){
                compSession(obCantTot);
            }

        }
    )
    $("#revAleProFin").change(
        function(){
            $("#revAleCan").val('');
            $("#revAleCanTot").val('');
            if($("#revAleUsu").val()!="" && $("#revAleProIni").val()!="" && $("#revAleProFin").val()!="" && $("#revAleFecIni").val()!="" && $("#revAleFecFin").val()!="" ){
                compSession(obCantTot);
            }

        }
    );


    $('#revAleForm').submit(
        function(){
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
                        compSession(generaImp);
                    }
                });


        }
    );


    function generaImp(){
        var datos=$("#revAleForm").serializeArray();
        $.ajax
        ({
            url : '../reportes/reporte.revAleatoriaCorte.php',
            type : 'POST',
            dataType : 'text',
            data : datos ,
            success : function(urlPdf) {

                if (urlPdf.substr(0,11)=="../../temp/"){
                    swal("Mensaje!", "Has Generado correctamente los datos", "success");
                    $("#rutRevAleCortPdf").prop('data',urlPdf) ;
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
                swal("Error!", "error desconocido contacte a sistemas", "error");
            }
        });

    }


    $("#revAlePorc").keyup(
        function(){
            calcPorc();
        }
    )


    $("#revAleProIni").blur(
        function(){
           complementaInpProFin()
        }
    )
    $("#revAleProFin").blur(
        function(){
           complementaInpProFin2()

        }
    )



});


function complementaInpProFin()
{
    var faltante =11-$("#revAleProIni").val().length;
    $("#revAleProFin").val(($("#revAleProIni").val()+faltanteFunc('9',faltante)).substr(0,11));
    $("#revAleProIni").val( ($("#revAleProIni").val()+faltanteFunc('0',faltante)).substr(0,11));
}

function complementaInpProFin2()
{
    var faltante =11-$("#revAleProFin").val().length;
    $("#revAleProFin").val(($("#revAleProFin").val()+faltanteFunc('9',faltante)).substr(0,11));

}

function faltanteFunc(constante,numero)
{
    var res="";
    for(x=1;x<=numero;x++)
    {
        res +=""+constante ;
    }
    return res;
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

function calcPorc(){
    $("#revAleCan").val( Math.round(cantTot*$("#revAlePorc").val()/100) );
    }

//
//function RefZon(){
//
//    $( "#genOrdCorrInpZon" ).autocomplete({
//        source: function ( request,response) {
//            $.ajax({
//                type: "POST",
//                url:"../datos/datos.genera_orden_cor.php",
//                data: { tip : 'autComZon',proy:$("#genOrdCorrSelPro").val(),term:$("#genOrdCorrInpZon").val() },
//                success:response,
//                dataType: 'json'
//            });
//        }
//    }, {minLength: 1 });
//
//
//}

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
                $("#revAleSelPro").focus(false);
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
                            $("#revAleSelPro").focus();
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
        url : '../datos/datos.revAleatoriaCorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#revAleSelPro').empty();
            $('#revAleSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#revAleSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}
function  obCantTot()
{

    var datos =$("#revAleForm").serializeArray();
    datos.push({name: 'tip', value: 'cantReg'});

    $.ajax
    ({
        url : '../datos/datos.revAleatoriaCorte.php',
        type : 'POST',
        dataType : 'json',
        data : datos,
        success : function(json) {


            cantTot=json[0]["CANTIDAD"];
               $('#revAleCanTot').val(json[0]["CANTIDAD"]);
            $("#revAleCan").val( Math.round(cantTot*$("#revAlePorc").val()/100) );
        },
        error : function(xhr, status) {

        }
    });
}



function llenarSpinerUsu()
{

    $.ajax
    ({
        url : '../datos/datos.revAleatoriaCorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUsu',pro:$('#revAleSelPro').val() },
        success : function(json) {
            $('#revAleUsu').empty();
            $('#revAleUsu').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#revAleUsu').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
                swal("Loggin Exitoso!")
                compSession(llenarSpinerPro);
                compSession(llenarSpinerUsu);
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
                            $("#revAleSelPro").focus();
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

//
//function refZonDes() {
//    if($("#genOrdCorrInpZon").val().length>2){
//        $("#genOrdCorrInpZonDesc").val("ZONA "+$("#genOrdCorrInpZon").val());
//        obtieneMaxPer();
//    }else{
//        $("#genOrdCorrInpZonDesc").val("");
//        $("#genOrdCorrInpPer").val("");
//        $("#genOrdCorrInpPerDesc").val("");
//
//    }
//
//}
//
//function obtieneMaxPer(){
//    $.ajax
//    ({
//        url : '../datos/datos.genera_orden_cor.php',
//        type : 'POST',
//        dataType : 'json',
//        data : { tip : 'perMax',zon:$("#genOrdCorrInpZon").val() },
//        success : function(json) {
//            if(json){
//                $("#genOrdCorrInpPer").val(json[0]["MAXPER"]);
//                $("#genOrdCorrInpPerDesc").val(json[0]["MES"]);
//            }
//
//        },
//        error : function(xhr, status) {
//
//        }
//    });
//}
//
//function generaOrdenManCorr(){
//    $.ajax
//    ({
//        url : '../datos/datos.genera_orden_cor.php',
//        type : 'POST',
//        dataType : 'json',
//        data : { tip : 'genOrd',zon:$("#genOrdCorrInpZon").val(),per:$("#genOrdCorrInpPer").val() },
//        success : function(json) {
//
//            if (json["res"]=="true"){
//                $('#genOrdCorrForm')[0].reset();
//                swal({
//                        title: "Mensaje",
//                        text: "Se ha generado las ordenes correctamente",
//                        type: "success"},
//                    function(isConfirm){
//                        if (isConfirm) {
//                            $("#genOrdCorrSelPro").focus();
//                        }
//                    }
//                );
//            }else if(json["res"]=="false"){
//                swal("Mensaje!", json["error"], "error");
//            }
//        },
//        error : function(xhr, status) {
//            swal("Mensaje!", "error inesperado contacte a sistemas", "error");
//        }
//    });
//}
