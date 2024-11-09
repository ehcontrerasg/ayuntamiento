/**
 * Created by PC on 7/7/2016.
 */

$(document).ready(function() {
    desContr();
    compSession();

    var f = new Date();
    $("#genOrdInpDes").val('Proceso masivo del día ' + f.getDate() + "/" + (f.getMonth() + 1) + "/" + f.getFullYear());

    compSession(genOrdLleSelPro);
    compSession(genSelOperario);

    $("#selProy").change(
        function () {
            compSession(genSelZona);
        }
    );

    $("#selZon").change(
        function () {
            compSession(genSelPer);
        }
    )

    $("#selPer").change(
        function(){
            compSession(genSelRuta);
        }
    );

    $("#formularioSup").submit(
        function(){
            compSession(generaListadoSup);
        }
    );


});

function genOrdLleSelPro(){
    $.ajax
    ({
        url : '../datos/datos.supervisionFac.php',
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
        url : '../datos/datos.supervisionFac.php',
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

function genSelPer(){
    $.ajax
    ({
        url : '../datos/datos.supervisionFac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPer',zona: $("#selZon").val() },
        success : function(json) {
            $('#selPer').empty();
            $("#selPer").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#selPer").append(new Option(json[x]["PERIODO"], json[x]["PERIODO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function genSelRuta(){
    $.ajax
    ({
        url : '../datos/datos.supervisionFac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selRuta',zona: $("#selZon").val(),per:$("#selPer").val() },
        success : function(json) {
            $('#selRuta').empty();
            $("#selRuta").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#selRuta").append(new Option(json[x]["RUTA"], json[x]["RUTA"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}




function generaListadoSup(){
    var params=$('#formularioSup').serializeArray();
    params.push({name: 'tip', value: 'genListado'});
    $.ajax
    ({
        url : '../datos/datos.supervisionFac.php',
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

/*


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
*/

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


