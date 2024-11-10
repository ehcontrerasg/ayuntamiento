/**
 * Created by PC on 7/7/2016.
 */


$(document).ready(function(){
    desContr();
    compSession();


    // var f = new Date();
    // $("#genOrdInpDes").val('Proceso masivo del día '+f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());

    compSession(genMigInmSelPro);
    compSession(genMigInmSelCic);

    $("#migInmSelPro").change(
        function(){
            compSession(genMigInmSelSec);
        }
    );

    $("#migInmSelSec").change(
        function(){
            compSession(genMigInmSelRut);
        }
    );
    $("#migInmSelNueSec").change(
        function(){
            compSession(genMigInmSelNueRut);
        }
    );

    $('#busMigInmForm').submit(
        function(){
            compSession(getInmMigracion);
        }
    );

    $('#actMigInmForm').submit(
        function(){
            compSession(ingresaMigracion);
        }
    );

});

function genMigInmSelPro(){
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#migInmSelPro').empty();
            $('#migInmSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#migInmSelPro').append(new Option(json[x]["DESC_PROYECTO"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {
        }
    });
}

function genMigInmSelSec(){
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selSec', proyecto: $("#migInmSelPro").val() },
        success : function(json) {
            $('#migInmSelSec').empty();
            $('#migInmSelNueSec').empty();
            $("#migInmSelSec").append(new Option('', '', true, true));
            $("#migInmSelNueSec").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#migInmSelSec").append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                $("#migInmSelNueSec").append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function genMigInmSelRut(){
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selRut', sector: $("#migInmSelSec").val() },
        success : function(json) {
            $('#migInmSelRut').empty();
            $("#migInmSelRut").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#migInmSelRut").append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function genMigInmSelNueRut(){
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selRut', sector: $("#migInmSelNueSec").val() },
        success : function(json) {
            $('#migInmSelNueRut').empty();
            $("#migInmSelNueRut").append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $("#migInmSelNueRut").append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function genMigInmSelCic(){
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCic' },
        success : function(json) {
            $('#migInmSelCic').empty();
            $('#migInmSelCic').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#migInmSelCic').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function getInmMigracion()
{
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selInmMig', pro:$('#migInmSelPro').val(), sec:$('#migInmSelSec').val(), rut:$('#migInmSelRut').val(), man:$('#migInmMan').val(), inm:$('#migInmCod').val(), sec2:$('#migInmSelNueSec').val(), rut2:$('#migInmSelNueRut').val(), man2:$('#migInmNueMan').val(), cic:$('#migInmSelCic').val()},
        success : function(json) {

            $('#actMigInmForm').empty();


            var spanExt = $('<table/>', {
                'class' : 'datoForm col1'
            });

            var spanInt = $('<tr/>', {
                'class' : 'datoForm col1',
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:30px',
                'text'  : 'N°'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:70px',
                'text'  : 'Código Inmueble'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:100px',
                'text'  :'Urbanización'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:150px',
                'text'  : 'Dirección'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:50px',
                'text'  : 'Estado'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:50px',
                'text'  : 'Zona Actual'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:150px',
                'text'  : 'Proceso Actual'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:150px',
                'text'  : 'Catastro Actual'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:50px',
                'text'  : 'Nueva Zona'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:150px',
                'text'  : 'Nuevo Proceso'
            });
            spanExt.append(spanInt);

            var spanInt = $('<th/>', {
                'class' : 'titDato',
                'style' : 'width:150px',
                'text'  : 'Nuevo Catastro'
            });
            spanExt.append(spanInt);

            $('#actMigInmForm').append(spanExt);

            for(var x=0;x<json.length ;x++)
            {
                var spanExt = $('<tr/>', {
                    'class' : 'datoForm col1'
                });

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:30px',
                    'text'  : x + 1
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:70px',
                    'text'  : json[x]["CODIGO_INM"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:100px',
                    'text'  : json[x]["DESC_URBANIZACION"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:150px',
                    'text'  : json[x]["DIRECCION"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:50px',
                    'text'  : json[x]["ID_ESTADO"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:50px',
                    'text'  : json[x]["ID_ZONA"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:150px',
                    'text'  : json[x]["ID_PROCESO"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'titDato numCont1',
                    'style' : 'width:150px',
                    'text'  : json[x]["CATASTRO"]
                });
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'inpDatp numCont1',
                    'style' : 'width:50px'
                });
                var inpNueZon = $('<input/>', {
                    'value' : json[x]["NUEVA_ZONA"],
                    'id'    : 'nueZon' + x,
                    'name'  : x,
                    'size'  :  '10',
                    'maxlength' : '3'
                });
                spanExt.append(spanInt);
                spanInt.append(inpNueZon);

                var spanInt = $('<td/>', {
                    'class' : 'inpDatp numCont1',
                    'style' : 'width:150px'
                });
                var inpNuePro = $('<input/>', {
                    'value' : json[x]["NUEVO_PROCESO"],
                    'id'    : 'nuePro' + x,
                    'name'  : x,
                    'minlength' : '11',
                    'maxlength' : '11'
                });
                funcionInpPro(inpNuePro);
                funcionValProInp(inpNuePro);
                spanInt.append(inpNuePro);
                spanExt.append(spanInt);

                var spanInt = $('<td/>', {
                    'class' : 'inpDatp numCont1',
                    'style' : 'width:150px'
                });
                var inpNueCat = $('<input/>', {
                    'value' : json[x]["NUEVO_CATASTRO"],
                    'id'    : 'nueCat' + x,
                    'name'  : x
                });
                funcionInpCat(inpNueCat);
                funcionValCatInp(inpNueCat);
                spanExt.append(spanInt);
                spanInt.append(inpNueCat);

                var spanInt = $('<td/>', {
                    'class' : 'inpDatp numCont1',
                    'style' : 'display:none'
                });
                var inpInmueble = $('<input/>', {
                    'value' : json[x]["CODIGO_INM"],
                    'id'    : 'codInm' + x,
                    'name'  : x
                });
                spanExt.append(spanInt);
                spanInt.append(inpInmueble);

                var spanInt = $('<td/>', {
                    'class' : 'inpDatp numCont1',
                    'style' : 'display:none'
                });
                var inpProceso = $('<input/>', {
                    'value' : json[x]["ID_PROCESO"],
                    'id'    : 'codPro' + x,
                    'name'  : x
                });
                spanExt.append(spanInt);
                spanInt.append(inpProceso);

                var spanMensaje = $('<span/>',{
                    'id' : 'spanMensaje' + x,
                    'tabindex' : -1
                });
                spanExt.append(spanMensaje);

                $('#actMigInmForm').append(spanExt);
            }
            xid = x;
            var spanExt = $('<span/>', {
                'class' : 'datoForm col1'
            });

            var inp=$('<input/>', {
                'id'    : 'envPreBut',
                'value' : 'Migrar Inmuebles',
                'class' : 'botonFormulario botFormAseo',
                'type'  : 'submit',
                'tabindex' : -1

            });
            spanExt.append(inp);
            $('#actMigInmForm').append(spanExt);


        },
        error : function(xhr, status) {

        }
    });
}

//Funciones para pintar todos los procesos del listado Generado

function funcionInpPro(inp)
{
    setTimeout(function() {
        $("#valProBut").trigger("click");
        pintaProceso(inp.attr('name'));
    }, 10);
}

function pintaProceso(i)
{
    verificaProcesoNuevo($("#codInm" + i).val(), $("#nuePro" + i).val(),function(cantidad) {
        if(cantidad == true) {
            $("#nuePro" + i).css("backgroundColor", "#CC0000");
        }
        else{
            $("#nuePro" + i).css("backgroundColor", "#5C9B25");
        }
    });
}

function verificaProcesoNuevo(inmueble, proceso, my_callback)
{
    var cantidad = false;
    var variable;
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selVerPro', inm : inmueble, pro : proceso},
        success : function(json) {
            for(var x=0;x<json.length ; x++) {
                variable = $.trim(json[x]["CANTIDAD"]);
                if(variable == "1")
                {
                    cantidad = true;
                }
                else{
                    cantidad = false;
                }
            }
            my_callback(cantidad);
        },
        error : function(xhr, status) {
        }
    });
    return cantidad;
}

// Funciones Para validar y pintar los procesos ingresados manualmente
function funcionValProInp(inp)
{
    inp.blur(
        function () {
            pintaValidaProceso(inp.attr('name'));
        }
    );
}

function pintaValidaProceso(i)
{
    validaProcesoNuevo($("#codInm" + i).val(), $("#nuePro" + i).val(),function(cantidad) {
        if(cantidad == true) {
            $("#nuePro" + i).css("backgroundColor", "#CC0000");
        }
        else{
            $("#nuePro" + i).css("backgroundColor", "#5c9b25");
        }
    });
}

function validaProcesoNuevo(inmueble, proceso, my_callback)
{
    var cantidad = false;
    var variable;
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selValPro', inm : inmueble, pro : proceso},
        success : function(json) {
            for(var x=0;x<json.length ; x++) {
                variable = $.trim(json[x]["CANTIDAD"]);
                if(variable == "1")
                {
                    cantidad = true;
                }
                else{
                    cantidad = false;
                }
            }
            my_callback(cantidad);
        },
        error : function(xhr, status) {
        }
    });
    return cantidad;
}

//Funciones para pintar el catastro del listado de inmuebles generado
function funcionInpCat(inp)
{
    setTimeout(function() {
        $("#valCatBut").trigger("click");
        pintaCatastro(inp.attr('name'));
    }, 10);
}

function pintaCatastro(i)
{
    verificaCatastroNuevo($("#codInm" + i).val(), $("#nueCat" + i).val(),function(cantidad) {
        if(cantidad == true) {
            $("#nueCat" + i).css("backgroundColor", "#CC0000");
        }
        else{
            $("#nueCat" + i).css("backgroundColor", "#5C9B25");
        }
    });
}

function verificaCatastroNuevo(inmueble, catastro, my_callback)
{
    var cantidad = false;
    var variable;
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selVerCat', inm : inmueble, cat : catastro},
        success : function(json) {
            for(var x=0;x<json.length ; x++) {
                variable = $.trim(json[x]["CANTIDAD"]);
                if(variable == "1")
                {
                    cantidad = true;
                }
                else{
                    cantidad = false;
                }
            }
            my_callback(cantidad);
        },
        error : function(xhr, status) {
        }
    });
    return cantidad;
}

// Funciones Para validar y pintar los catastro ingresados manualmente
function funcionValCatInp(inp)
{
    inp.blur(
        function () {
            pintaValidaCatastro(inp.attr('name'));
        }
    );
}

function pintaValidaCatastro(i)
{
    validaCatastroNuevo($("#codInm" + i).val(), $("#nueCat" + i).val(),function(cantidad) {
        if(cantidad == true) {
            $("#nueCat" + i).css("backgroundColor", "#CC0000");
        }
        else{
            $("#nueCat" + i).css("backgroundColor", "#5c9b25");
        }
    });
}

function validaCatastroNuevo(inmueble, catastro, my_callback)
{
    var cantidad = false;
    var variable;
    $.ajax
    ({
        url : '../datos/datos.migra_inmuebles.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selValCat', inm : inmueble, cat : catastro},
        success : function(json) {
            for(var x=0;x<json.length ; x++) {
                variable = $.trim(json[x]["CANTIDAD"]);
                if(variable == "1")
                {
                    cantidad = true;
                }
                else{
                    cantidad = false;
                }
            }
            my_callback(cantidad);
        },
        error : function(xhr, status) {
        }
    });
    return cantidad;
}

async function ingresaMigracion()
{
    for (i = 0; i < xid; i++) {
        await guardarMigracion($('#codInm' + i).val(), $('#nueZon' + i).val(), $('#nuePro' + i).val(), $('#nueCat' + i).val());
    }
    getInmMigracion();
}

function guardarMigracion(inmueble, zona, proceso, catastro) {
    return new Promise((resolve, reject) => {
        $.ajax
        ({
            url: '../datos/datos.migra_inmuebles.php',
            type: 'POST',
            dataType: 'text',
            data: {
                tip: 'guardMig',
                inm: inmueble,
                zon: zona,
                pro: proceso,
                cat: catastro
            },
            success: function (res) {
                resolve()
            },
            error: function (xhr, status) {

            }
        });
    });

}

function iniSes()
{
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