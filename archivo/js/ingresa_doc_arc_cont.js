$(document).ready(function(){

    desContr();
    compSession(llenarSelClase);
    compSession(llenarSelBanco);
    compSession(llenarSelEmpresa);


    $("#cladocumento").change(
        function() {
            compSession(llenarSelDoc());
            if($("#cladocumento").val() == ''){
                $("#divFecEmi").hide();
                $("#divBeneficiario").hide();
                $("#divBanco").hide();
                $("#divEmpresa").hide();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").hide();
                $("#divCodFac").hide();
                $("#divCuenta").hide();
                $("#divAproba").hide();
            }
        }
    );

    $("#tipdocumento").change(
        function() {
            if ($("#tipdocumento").val() == 13)
            {
                $("#divFecEmi").show();
                $("#divBeneficiario").show();
                $("#divBanco").show();
                $("#divEmpresa").show();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").hide();
                $("#divCodFac").hide();
                $("#divCuenta").hide();
                $("#divAproba").hide();
            }
            if ($("#tipdocumento").val() == 14 || $("#tipdocumento").val() == 22)
            {
                $("#divFecEmi").show();
                $("#divBeneficiario").show();
                $("#divBanco").hide();
                $("#divEmpresa").show();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").hide();
                $("#divCodFac").hide();
                $("#divCuenta").hide();
                $("#divAproba").hide();
            }
            if ($("#tipdocumento").val() == 15  || $("#tipdocumento").val() == 23)
            {
                $("#divFecEmi").show();
                $("#divBeneficiario").show();
                $("#divBanco").hide();
                $("#divEmpresa").show();
                $("#divFecRec").show();
                $("#divAsunto").show();
                $("#divPeriodo").hide();
                $("#divCodFac").hide();
                $("#divCuenta").hide();
                $("#divAproba").hide();
            }
            if ($("#tipdocumento").val() == 16)
            {
                $("#divFecEmi").hide();
                $("#divBeneficiario").hide();
                $("#divBanco").hide();
                $("#divEmpresa").hide();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").show();
                $("#divCodFac").hide();
                $("#divCuenta").hide();
                $("#divAproba").hide();

            }
            if ($("#tipdocumento").val() == 18  || $("#tipdocumento").val() == 19)
            {
                $("#divFecEmi").show();
                $("#divBeneficiario").hide();
                $("#divBanco").hide();
                $("#divEmpresa").show();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").hide();
                $("#divCodFac").show();
                $("#divCuenta").hide();
                $("#divAproba").hide();
            }
            if ($("#tipdocumento").val() == 20  || $("#tipdocumento").val() == 21)
            {
                $("#divFecEmi").show();
                $("#divBeneficiario").show();
                $("#divBanco").show();
                $("#divEmpresa").show();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").hide();
                $("#divCodFac").hide();
                $("#divCuenta").show();
                $("#divAproba").show();
            }
            if ($("#tipdocumento").val() == '')
            {
                $("#divFecEmi").hide();
                $("#divBeneficiario").hide();
                $("#divBanco").hide();
                $("#divEmpresa").hide();
                $("#divFecRec").hide();
                $("#divAsunto").hide();
                $("#divPeriodo").hide();
                $("#divCodFac").hide();
                $("#divCuenta").hide();
                $("#divAproba").hide();
            }
        }
    );


    $("#ingDocArcForm").submit(function(e){
        e.preventDefault();
        IngDocArc();

    });
    /*$('#ingCodDoc').focusout(function(){
        var codigo_inm = $(this).val();
        $.post("../datos/datos.info_observacion.php", {tip: 'existReg', codigo_inm: codigo_inm}, function(result){
            //console.log(result);
            if (result.toString().trim() == 'true') {
                //console.log('si mostrar');
                $(":submit").attr("disabled", true);
                $('#msgExist').fadeIn();
            }else{
                //console.log('no mostrar');
                $(":submit").removeAttr("disabled");
                $('#msgExist').fadeOut();
            }
        });
    });*/
});

function llenarSelClase()
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc_cont.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCla' },
        success : function(json) {
            $('#cladocumento').empty();
            $('#cladocumento').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++){
                $('#cladocumento').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {
        }
    });
}

function llenarSelBanco()
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc_cont.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selBan' },
        success : function(json) {
            $('#banco').empty();
            $('#banco').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++){
                $('#banco').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {
        }
    });
}

function llenarSelEmpresa()
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc_cont.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEmp' },

        success : function(json) {
            $('#empresa').empty();
            $('#empresa').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++){
                $('#empresa').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {
        }
    });
}


function llenarSelDoc()
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc_cont.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selDoc', clase: $("#cladocumento").val() },
        success : function(json) {
            $('#tipdocumento').empty();
            $('#tipdocumento').append(new Option('', '', true, true));
            for (var x=0; x < json.length; x++) {
                $('#tipdocumento').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {
        }
    });
}

function IngDocArc() {
    oData = new FormData(document.forms.namedItem("ingDocArcForm"));
    oData.append("tip", "IngDocArc");
    if ($("#tipdocumento").val() == 13 || $("#tipdocumento").val() == 14 || $("#tipdocumento").val() == 15 || $("#tipdocumento").val() == 18 || $("#tipdocumento").val() == 19 || $("#tipdocumento").val() == 20 || $("#tipdocumento").val() == 21 || $("#tipdocumento").val() == 22 || $("#tipdocumento").val() == 23) {
        if ($("#fechaDoc").val() == "") {
            $("#fechaDoc").focus();
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese la fecha de emisión",
                type: "error"
            });
            return false;
        }
    }
    if ($("#tipdocumento").val() == 13 || $("#tipdocumento").val() == 14 || $("#tipdocumento").val() == 15 || $("#tipdocumento").val() == 20 || $("#tipdocumento").val() == 21 || $("#tipdocumento").val() == 22 || $("#tipdocumento").val() == 23){
        if ($("#beneficiario").val() == "") {
            $("#beneficiario").focus();
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el beneficiario",
                type: "error"
            });
            return false;
        }
    }
    if ($("#tipdocumento").val() == 13 || $("#tipdocumento").val() == 20 || $("#tipdocumento").val() == 21){
        if ($("#banco").val() == "") {
            $("#banco").focus();
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el banco",
                type: "error"
            });
            return false;
        }
    }
    if ($("#tipdocumento").val() == 13 || $("#tipdocumento").val() == 14 || $("#tipdocumento").val() == 15 || $("#tipdocumento").val() == 18 || $("#tipdocumento").val() == 19 || $("#tipdocumento").val() == 20 || $("#tipdocumento").val() == 21 || $("#tipdocumento").val() == 22 || $("#tipdocumento").val() == 23){
        if ($("#empresa").val() == "") {
            $("#empresa").focus();
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese la empresa",
                type: "error"
            });
            return false;
        }
    }
    if ($("#tipdocumento").val() == 15 || $("#tipdocumento").val() == 23) {
        if ($("#fechaRep").val() == "") {
            $("#fechaRep").focus();
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese la fecha de recepción",
                type: "error"
            });
            return false;
        }
        if ($("#asunto").val() == "") {
            $("#asunto").focus();
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el asunto",
                type: "error"
            });
            return false;
        }
    }

    if ($("#tipdocumento").val() == 16) {
        if ($("#periodo").val() == "") {
            $("#periodo").focus()
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el periodo",
                type: "error"
            });
            return false;
        }
    }
    if ($("#tipdocumento").val() == 18 || $("#tipdocumento").val() == 19) {
        if ($("#codFac").val() == "") {
            $("#codFac").focus()
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el numero de factura",
                type: "error"
            });
            return false;
        }
    }
    if ($("#tipdocumento").val() == 20 || $("#tipdocumento").val() == 21) {
        if ($("#cuenta").val() == "") {
            $("#cuenta").focus()
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el numero de cuenta",
                type: "error"
            });
            return false;
        }
        if ($("#aprobacion").val() == "") {
            $("#aprobacion").focus()
            swal({
                title: "Validación de Campos",
                text: "Por favor ingrese el numero de aprobación",
                type: "error"
            });
            return false;
        }
    }
    swal({
        title: "Insertar Archivo",
        text: "Esta seguro de que desea insertar este archivo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, insertar",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
    },function(isConfirm){
        if (isConfirm) {
            console.log('si');
            $.ajax({
                url: '../datos/datos.ingresa_doc_arc_cont.php', //URL destino
                data: oData,
                processData: false, //Evitamos que JQuery procese los datos, daría error
                contentType: false, //No especificamos ningún tipo de dato
                cache: false,
                type: 'POST',
                success : function(resp) {
                    // document.write(json);
                    // cargarArchivo();

                    json = resp.toString().trim();
                    console.log(resp);
                    if (json=="true"){
                        $('#ingDocArcForm')[0].reset();
                        $('#ingDocArcForm input, #ingDocArcForm select, #ingDocArcForm textarea').val('');
                        swal({
                                title: "Mensaje",
                                text: "Has cargado el documento correctamente",
                                type: "success"
                            }, function(isConfirm) {
                                if (isConfirm) {
                                    //$("#ingCodDoc").focus();
                                    //$(this).focus();
                                }
                            }
                        );
                    }else{
                        swal("Mensaje!", json["error"], "error");
                        $(this).focus();
                    }
                }
            });
        }else{
            console.log('si');
            swal("Cancelled", "El archivo no fue guardado :)", "error");
            $(this).focus();
        }
    })
    /*$.ajax({
        url: '../datos/datos.ingresa_doc_arc.php', //URL destino
        data: oData,
        processData: false, //Evitamos que JQuery procese los datos, daría error
        contentType: false, //No especificamos ningún tipo de dato
        cache: false,
        type: 'POST',
        beforeSend: function(){

        },
        success : function(resp) {
           // document.write(json);
           // cargarArchivo();
           json = resp.toString().trim();
           console.log(resp);
            if (json=="true"){
                $('#ingDocArcForm')[0].reset();

                swal({
                        title: "Mensaje",
                        text: "Has ingresado la orden correctamente",
                        type: "success"},
                    function(isConfirm){
                        if (isConfirm) {
                            $("#ingCodDoc").focus();
                        }
                    }
                );
            }else{
                swal("Mensaje!", json["error"], "error");
                $(this).focus();
            }
        }
    });    */
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
                $("#ingCodDoc").focus(false);
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
                            $("#ingCodDoc").focus();
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

//FUNCION PARA ABRIR UN POPUP
var popped = null;
function popup(uri, awid, ahei, scrollbar) {
    var params;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        }
        else {
            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
            popped = window.open(uri, "popup", params);
        }
    }
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
                        inputPlaceholder: "Write something",
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
                            $("#ingCodDoc").focus();
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

function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;
        }
    }

    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;
        }
    }
    $(document).bind("contextmenu",function(e){return false;});
}