$(document).ready(function(){
    desContr();
    compSession();
    compSession(llenarSpinerPro);
    $("#LotCorSelPro").change(
        function(){
            refProDes();
        }
    );
    $("#LotCorInpZon").keyup(
        function(){
            RefZon();
        }
    )

    $("#LotCorInpZon").blur(
        function(){
            refZonDes();
        }
    )

    $('#LotCorForm').submit(
        function(){
            compSession(generaOrdenManCorr);
        }
    )
});

function refProDes() {
    if($("#LotCorSelPro").val()==""){
        $('#LotCorForm')[0].reset();
    }else if($("#LotCorSelPro").val()=="BC"){
        $("#LotCorInpProDesc").val("Boca Chica");
    }else if($("#LotCorSelPro").val()=="SD"){
        $("#LotCorInpProDesc").val("Santo Domingo");
    }
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


function RefZon(){

    $( "#LotCorInpZon" ).autocomplete({
        source: function ( request,response) {
            $.ajax({
                type: "POST",
                url:"../datos/datos.loteCorte.php",
                data: { tip : 'autComZon',proy:$("#LotCorSelPro").val(),term:$("#LotCorInpZon").val() },
                success:response,
                dataType: 'json'
            });
        }
    }, {minLength: 1 });


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
        url : '../datos/datos.loteCorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#LotCorSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#LotCorSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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


function refZonDes() {
    if($("#LotCorInpZon").val().length>2){
        $("#LotCorInpZonDesc").val("ZONA "+$("#LotCorInpZon").val());
        obtieneMaxPer();
    }else{
        $("#LotCorInpZonDesc").val("");
        $("#LotCorInpPer").val("");
        $("#LotCorInpPerDesc").val("");

    }

}

function obtieneMaxPer(){
    $.ajax
    ({
        url : '../datos/datos.loteCorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'perMax',zon:$("#LotCorInpZon").val() },
        success : function(json) {
            if(json){
                $("#LotCorInpPer").val(json[0]["MAXPER"]);
                $("#LotCorInpPerDesc").val(json[0]["MES"]);
            }

        },
        error : function(xhr, status) {

        }
    });
}

function generaOrdenManCorr(){
    $.ajax
    ({
        url : '../datos/datos.loteCorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'genOrd',zon:$("#LotCorInpZon").val(),per:$("#LotCorInpPer").val() },
        success : function(json) {

            if (json["res"]=="true"){
                $('#LotCorForm')[0].reset();
                swal({
                        title: "Mensaje",
                        text: "Se ha generado las ordenes correctamente",
                        type: "success"},
                    function(isConfirm){
                        if (isConfirm) {
                            $("#LotCorSelPro").focus();
                        }
                    }
                );
            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
            }
        },
        error : function(xhr, status) {
            swal("Mensaje!", "error inesperado contacte a sistemas", "error");
        }
    });
}
