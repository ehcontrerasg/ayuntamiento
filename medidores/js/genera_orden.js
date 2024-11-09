/**
 * Created by PC on 7/7/2016.
 */

$(document).ready(function(){
    desContr();
    compSession();

    var f = new Date();
    $("#genOrdInpDes").val('Proceso masivo del día '+f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());

    compSession(genOrdLleSelPro);
    compSession(genOrdLleSelMotCamb);
    compSession(genOrdLleSelCon);

    $('#genOrdInpProIni').blur(
        function(){
            complementaInpProFin();
        }
    )
    $('#genOrdInpProFin').blur(
        function(){
            complementaInpProFin2();
        }
    )
    $("#genOrdInpManIni").blur(
        function(){
            completaManzFin();
        }
    )
    $("#genOrdSelCon").change(
        function(){
            compSession(genOrdLleSelOpe);
        }
    )

    $('#genOrdFor').submit(
        function(){
           // compSession(generaOrdCamb);
            compSession(flexyGenOrd);
        }
    )

});


function complementaInpProFin(){
    var faltante =11-$('#genOrdInpProIni').val().length;
    $('#genOrdInpProFin').val(($('#genOrdInpProIni').val()+faltanteFunc('9',faltante)).substr(0,11));
    $('#genOrdInpProIni').val(($('#genOrdInpProIni').val()+faltanteFunc('0',faltante)).substr(0,11));
}

function complementaInpProFin2(){
    var faltante =11-$('#genOrdInpProFin').val().length;
    $('#genOrdInpProFin').val(($('#genOrdInpProFin').val()+faltanteFunc('9',faltante)).substr(0,11));

}

function completaManzFin(){
    $('#genOrdInpManFin').val($("#genOrdInpManIni").val());
}

function faltanteFunc(constante,numero){
    var res="";
    for(x=1;x<=numero;x++)
    {
        res +=""+constante ;
    }
    return res;
}

function flexyGenOrd(){

    var parametros =
        [
            {name:"proyecto", value:$('#genOrdSelPro').val()},
            {name:"proini"  , value:  $('#genOrdInpProIni').val()},
            {name:"profin"  , value:  $('#genOrdInpProFin').val()},
            {name:"codsis"  , value:  $('#genOrdInpCodSis').val()},
            {name:"manini"  , value:  $("#genOrdInpManIni").val()},
            {name:"manfin"  , value:  $('#genOrdInpManFin').val()},
            {name:"medido"  , value:  $("#genOrdSelMed").val()},
            {name:"estado"  , value:  $("#genOrdSelEstInm").val()},
            {name:"tip"     , value:  "flexy"}
        ]



    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexGeneraOrd").flexigrid
    (
        {
            url: './../datos/datos.genera_orden.php',
            dataType: 'json',
            type:  'post',

            colModel : [
                {display: 'No',  width:10,  align: 'center'},
                {display: 'Zona',  width:30,  align: 'center'},
                {display: 'Estado', width: 60,  align: 'center'},
                {display: 'Cod. Sistma',  width: 50,  align: 'center'},
                {display: 'Direccion', width: 100,  align: 'center'},
                {display: 'Medidor',  width: 70,  align: 'center'},
                {display: 'Serial',  width: 100,  align: 'center'},
                {display: 'Calibre', width: 100, align: 'center'},
                {display: 'Fecha Alta', width: 100,  align: 'center'}
            ],
            usepager: true,
            title: 'Inmuebles generacion de cambio medidor',
            useRp: false,
            page: 1,
            onSuccess: function(){generaOrdCamb()},
            showTableToggleBtn: false,
            width: 750,
            height: 245,
            params: parametros
        }
    );
    $("#flexGeneraOrd").flexOptions({url: './../datos/datos.genera_orden.php'});
    $("#flexGeneraOrd").flexOptions({params: parametros});
    $("#flexGeneraOrd").flexReload();
}


function generaOrdCamb(){
    var params=$('#genOrdFor').serializeArray();
    params.push({name: 'tip', value: 'genOrd'});
    $.ajax
    ({
        url : '../datos/datos.genera_orden.php',
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


function genOrdLleSelPro(){
    $.ajax
    ({
        url : '../datos/datos.genera_orden.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#genOrdSelPro').empty();
            $('#genOrdSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genOrdSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function validaCampos(){

    if($('#genOrdInpCodSis').val().trim()=="" && ($('#genOrdInpProIni').val().trim()=="" || $('#genOrdInpProFin').val().trim()==""))
    {
        swal("Error!", "Debe seleccionar un codigo de sistema o un rango de procesos", "error");
        return false;
    }

    if($("#genOrdInpManIni").val()!="" && $("#genOrdInpManIni").val().length<3 )
    {
        swal("Error!", "Manzana inicial incorrecta", "error");
        return false;
    }

    if($('#genOrdInpManFin').val()!="" && $('#genOrdInpManFin').val().length<3 )
    {

        swal("Error!", "Manzana final incorrecta", "error");
        return false;
    }


    return true;
}

function genOrdLleSelMotCamb(){
    $.ajax
    ({
        url : '../datos/datos.genera_orden.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selMot' },
        success : function(json) {
            $('#genOrdSelMot').empty();
            $('#genOrdSelMot').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genOrdSelMot').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function genOrdLleSelCon(){
    $.ajax
    ({
        url : '../datos/datos.genera_orden.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCon' },
        success : function(json) {
            $('#genOrdSelCon').empty();
            $("#genOrdSelCon").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#genOrdSelCon").append(new Option(json[x]["DESCRIPCION"], json[x]["ID_CONTRATISTA"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function genOrdLleSelOpe(){
    $.ajax
    ({
        url : '../datos/datos.genera_orden.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selOpe',cont: $("#genOrdSelCon").val() },
        success : function(json) {
            $('#genOrdSelOpe').empty();
            $("#genOrdSelOpe").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#genOrdSelOpe").append(new Option(json[x]["LOGIN"], json[x]["ID_USUARIO"], false, false));
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


