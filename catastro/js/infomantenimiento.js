var proyecto;
var servicioAg;
var servicioAlc;
var inmueble;
var periodo;
var uso;

var usoNuev;
var actNuev;
var estNuev;
var estServNuev;

$(document).ready(function(){
    inmueble = getUrlVars()["cod"];
    periodo = getUrlVars()["per"];
    desContr();
    compSession();
    compSession(llenarDatInmGen);
    compSession(llenarDatInmGenMant);
    compSession(obtieneDtFotos);

    $("#selManCatUsoNuev").change(
        function(){
            usoNuev=$("#selManCatUsoNuev").val();
            compSession(llenarSelAct());
            compSession(llenarSelTarAgua());
            compSession(llenarSelTarAlca());
        }
    );


    $('#formGuarMan').submit(
        function(){
            compSession(validaCampos);
        }
    )



});

function ocultaControles() {

        $("#divAlcNue").remove();
        $("#divAlcAct").remove();

}

function validaCampos() {
    console.log('valida mensaje');
    if(($('#selManCatUsoNuev').val().trim()!=$('#inpManCatUsoAct').val().trim()) && $('#selManCatActNue').val().trim()=='' && $('#selManCatUsoNuev').val().trim()!='' ){
        swal ( "Por favor seleccione la actividad " )  ;
        return false;
    }

    if(($('#selManCatUsoNuev').val().trim()!=$('#inpManCatUsoAct').val().trim()) && $('#selManCatTarAguNue').val().trim()=='' && $('#selManCatUsoNuev').val().trim()!=''){
        swal ( "Por favor seleccione la Tarifa de agua " )  ;
        return false;
    }

    if ($("#inpManCatTarAlcAct").length > 0 ){
        if(($('#selManCatUsoNuev').val().trim()!=$('#inpManCatUsoAct').val().trim()) && $('#selManCatTarAlcNue').val().trim()=='' && $('#selManCatUsoNuev').val().trim()!=''){
            swal ( "Por favor seleccione la Tarifa de alcantarillado " )  ;
            return false;
        }
    }

    guardaMant();
}

function guardaMant(){
    var datos=$("#formGuarMan").serializeArray();
    datos.push({name: 'tip', value: 'giarMant'});
    datos.push({name: 'periodo', value: periodo});
    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data :  datos ,
        success : function(json) {
            if(json){
                if(json['res']=="true"){
                    swal({
                            title: "Mensaje",
                            text: "Has mantenimiento guardado correctamente",
                            type: "success"},
                        function(isConfirm){
                            if (isConfirm) {
                                setTimeout("window.opener.document.location.reload();self.close()",1000);
                            }
                        });
                }else{
                    swal({
                            title: "Mensaje",
                            text: "error "+json['error'],
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                                $("#inpManCatDirNuev").focus();
                            }
                        });
                    $("#inpManCatDirNuev").focus();

                }


            }
            else
            {

            }
        },
        error : function(xhr, status) {

        }
    });


}

function llenarDatInmGen()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'datInmGen',inm:inmueble},
        success : function(json) {


            $("#inpManCatCodSis").val(inmueble);
            $("#inpManCatPro").val(json[0]['ID_PROCESO']);
            $("#inpManCatCat").val(json[0]['CATASTRO']);
            $("#inpManCatDirAct").val(json[0]['DIRECCION']);
            $("#inpManCatUsoAct").val(json[0]['USO']);
            $("#inpManCatActAct").val(json[0]['ACTIVIDAD']);
            $("#inpManCatUniHAct").val(json[0]['UNIDADES_HAB']);
            $("#inpManCatUniTAct").val(json[0]['UNIDADES_TOT']);
            $("#inpManCatEstAct").val(json[0]['ID_ESTADO']);
            $("#inpManCatTarAguAct").val(json[0]['DESC_TARIFA']);
            $("#inpManCatTarAlcAct").val(json[0]['TAR_ALC']);
            $("#inpManCatTelAct").val(json[0]['TELEFONO']);
            $("#inpManCupBasAct").val(json[0]['CUPO_BASICO']);
            $("#inpManEstServAct").val(json[0]['ESTADO_SERVICIO']);
            proyecto=json[0]['ID_PROYECTO'];
            servicioAg=json[0]['SERVAGUA'];
            servicioAlc=json[0]['SERVALCANT'];
            llenarSelUso();
            if (json[0]['TAR_ALC']==null){
                ocultaControles();
            }






        },
        error : function(xhr, status) {

        }
    });
}


function llenarDatInmGenMant()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'datInmGenMant',inm:inmueble,per:periodo},
        success : function(json) {


            $("#inpManCatDirNuev").val(json[0]['DIRECCION']);
            $("#inpManCatUniHNue").val(json[0]['UNIDADES_HAB']);
            $("#inpManCatUniTNue").val(json[0]['UNIDADES_TOT']);
            $("#inpManCatTelNue").val(json[0]['TELEFONO_CLI']);
            $("#texAreManCatObs").val(json[0]['OBSERVACIONES']);
            usoNuev=json[0]['ID_USO'];
            actNuev=json[0]['ID_ACTIVIDAD'];
            estNuev=json[0]['ID_ESTADO'];
            estServNuev=json[0]['CONDICION_SERV'];
            llenarSelUso();
            llenarSelAct();
            llenarSelTarAgua();
            llenarSelTarAlca();
            llenarSelEst();
            llenarSelEstSer();



        },
        error : function(xhr, status) {

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


function llenarSelUso()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUso' },
        success : function(json) {
            $('#selManCatUsoNuev').empty();
            $('#selManCatUsoNuev').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                if(usoNuev==json[x]["CODIGO"]){
                    $('#selManCatUsoNuev').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
                    llenarSelAct();
                    llenarSelTarAgua();
                    llenarSelTarAlca();
                }else{
                    $('#selManCatUsoNuev').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }

        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelTarAgua()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selTarAgu',uso:$('#selManCatUsoNuev').val(),ser:servicioAg,pro:proyecto },
        success : function(json) {
            $('#selManCatTarAguNue').empty();
            $('#selManCatTarAguNue').append(new Option('', '', true, true));
            if(json!=null){
            for(var x=0;x<json.length;x++)
            {
                if('R'==json[x]["CODIGO"]){
                    $('#selManCatTarAguNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));

                }else{
                    $('#selManCatTarAguNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }}
        },
        error : function(xhr, status) {

        }
    });
}
function llenarSelTarAlca()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selTarAgu',uso:$('#selManCatUsoNuev').val(),ser:servicioAlc,pro:proyecto },
        success : function(json) {
            $('#selManCatTarAlcNue').empty();
            $('#selManCatTarAlcNue').append(new Option('', '', true, true));
            if(json!=null){
            for(var x=0;x<json.length;x++)
            {
                if('R'==json[x]["CODIGO"]){
                    $('#selManCatTarAlcNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));

                }else{
                    $('#selManCatTarAlcNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }}
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelEst()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEst' },
        success : function(json) {
            $('#selManCatEstNue').empty();
            $('#selManCatEstNue').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                if(estNuev==json[x]["CODIGO"]){
                    $('#selManCatEstNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));

                }else{
                    $('#selManCatEstNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelEstSer()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEstSer' },
        success : function(json) {
            $('#selManCatEstSerNue').empty();
            $('#selManCatEstSerNue').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                if(estServNuev==json[x]["ID"]){
                    $('#selManCatEstSerNue').append(new Option(json[x]["DESCRIPCION"], json[x]["ID"], true, true));

                }else{
                    $('#selManCatEstSerNue').append(new Option(json[x]["DESCRIPCION"], json[x]["ID"], false, false));
                }

            }
        },
        error : function(xhr, status) {

        }
    });
}


function obtieneDtFotos()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'obtFotMan',inm:inmueble,per:periodo},
        success : function(json) {
            for(var x=0;x<json.length;x++)
            {
                pintaImagen(json[x]["URL_FOTO"],json[x]["CONSECUTIVO"])

            }
        },
        error : function(xhr, status) {

        }
    });
}

function pintaImagen(url,consec) {
    var img =$('<img/>', {
        'name':consec,
        'id':consec,
        'src':url,
        'height':300


    });
    var div1 =$('<div/>', {
        'class':'col-xs-6 col-sm-6 col-md-6 col-lg-6',

    });

    var div2 =$('<div/>', {
        'class':'form-group',

    });
    var div3 =$('<div/>', {
        'class':'col-xs-6 col-sm-6 col-md-6 col-lg-6',

    });

    div3.append(img);
    div2.append(div3);
    div1.append(div2);
    $("#divManCatFot").append(div1)
}


function llenarSelAct()
{

    $.ajax
    ({
        url : '../datos/datos.infomantenimiento.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selAct',uso:usoNuev},
        success : function(json) {
            $('#selManCatActNue').empty();
            $('#selManCatActNue').append(new Option('', '', false, false));

            for(var x=0;x<json.length;x++)
            {

                if(actNuev==json[x]["CODIGO_ACT"]){
                    $('#selManCatActNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO_ACT"], true, true));

                }else{
                    $('#selManCatActNue').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO_ACT"], false, false));
                }

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


function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
