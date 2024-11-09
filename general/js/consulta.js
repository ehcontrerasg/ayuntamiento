var codCol;
var facCol;
var difCol;
var pagCol;
var otrRecCol;
var salCol;
var valMod;
var codigoContrato;
$(document).ready(function(){
    desContr();
    compSession();


    valMod=getParameterByName('mod');
    if(valMod=='1'){
        $("#headGen").addClass( "fondCat" );
        $("#accordion").addClass( "accordionCat" );
        $("#botConsGen").addClass( "botFormCat" );
        $("#fieldInfo").addClass( "colCat" );
        $("#butActDat").hide();
    }else if(valMod=='2'){
        $("#headGen").addClass( "fondFac" );
        $("#accordion").addClass( "accordionFac" );
        $("#botConsGen").addClass( "botFormFac" );
        $("#fieldInfo").addClass( "colFac" );
        $("#butActDat").hide();
    }else if(valMod=='3') {
        $("#headGen").addClass("fondCorte");
        $("#accordion").addClass("accordionCorte");
        $("#botConsGen").addClass("botFormCorte");
        $("#fieldInfo").addClass("colCorte");
        $("#butActDat").hide();
    }else if(valMod=='6'){
        $("#headGen").addClass( "fondGer" );
        $("#accordion").addClass( "accordionGer" );
        $("#botConsGen").addClass( "botFormGer" );
        $("#fieldInfo").addClass( "colGer" );
        $("#butActDat").hide();
    }else if(valMod=='7'){
        $("#headGen").addClass( "fondCassd" );
        $("#accordion").addClass( "accordionCaasd" );
        $("#botConsGen").addClass( "botFormCassd" );
        $("#fieldInfo").addClass( "colCassd" );
        $("#butActDat").hide();
    }else if(valMod=='9'){
        $("#headGen").addClass( "fondSerCli" );
        $("#accordion").addClass( "accordionSerCli" );
        $("#botConsGen").addClass( "botFormSerCli" );
        $("#fieldInfo").addClass( "colSerCli" );
        $("#butActDat").show();
    }else if(valMod=='10'){
        $("#headGen").addClass( "fondMed" );
        $("#accordion").addClass( "accordionMed" );
        $("#botConsGen").addClass( "botFormMed" );
        $("#fieldInfo").addClass( "colMed" );
        $("#butActDat").hide();
    }else if(valMod=='11'){
        $("#headGen").addClass( "fondRec" );
        $("#accordion").addClass( "accordionRec" );
        $("#botConsGen").addClass( "botFormRec" );
        $("#fieldInfo").addClass( "colRec" );
        $("#butActDat").hide();
    }else if(valMod=='13'){
        $("#headGen").addClass( "fondGraCli" );
        $("#accordion").addClass( "accordionGraCli" );
        $("#botConsGen").addClass( "botFormGraCli" );
        $("#fieldInfo").addClass( "colGraCli" );
        $("#butActDat").hide();
    }else if(valMod=='15'){
        $("#headGen").addClass( "fondArc" );
        $("#accordion").addClass( "accordionArc" );
        $("#botConsGen").addClass( "botFormArc" );
        $("#fieldInfo").addClass( "colArc" );
        $("#butActDat").hide();
    }else if(valMod=='17'){
        $("#headGen").addClass( "fondAseo" );
        $("#accordion").addClass( "accordionAseo" );
        $("#botConsGen").addClass( "botFormAseo" );
        $("#fieldInfo").addClass( "colAseo" );
        $("#butActDat").hide();
    }

//// FUNCIONES POPUP
    $("#detConDatInm").hide();
    compSession(flexyListInm);

///// FUNCIONES PAGINA PRINCIPAL

    compSession(llenarSelPro);
    compSession(llenarSelGrupoCli);
    compSession(llenarSelTipoCli);
    compSession(llenarSelMed);
    compSession(llenarSelEmpl);
    compSession(llenarSelSum);
    compSession(llenarSelUso);

    $("#ConsGenForm").attr('action', "vista.detConsGeneral.php");
    $('#mod').val(valMod);

    $("#ConsGenForm").submit(
        function(){
            // popup("vista.detConsGeneral.php",638,400,'yes',JSON.stringify($("#ConsGenForm").serializeArray()));
            //window.open("vista.detConsGeneral.php?mod="+valMod,	JSON.stringify($("#ConsGenForm").serializeArray()),'width=1350, height=660, replace=true');

            /* $.get("vista.detConsGeneral.php?mod="+valMod, JSON.stringify($("#ConsGenForm").serializeArray()), function(data) {
                 $('#modal-consulta-body').html(data);
             });*/

            //$('#modal-consulta-body').attr('src', "vista.detConsGeneral.php?mod="+valMod, JSON.stringify($("#ConsGenForm").serializeArray()));

            /*   $.ajax({
                   url: "../vistas/vista.detConsGeneral.php?mod="+valMod,
                   type : 'POST',
                   dataType: 'html',
                   data: $("#ConsGenForm").serializeArray(),
               })
               .done(function(data) {
                    $('#modal-consulta-body').html(data);
               })*/

            //compSession(flexyListInm);

        }

    );

    $("#busGenSelPro").change(
        function(){
            compSession(llenarSelTipVia);
        }
    );

    $("#busGenSelEstInm").change(
        function(){
            compSession(llenarSelEstInm);
        }
    );
    $("#busGenSelUso").change(
        function(){
            compSession(llenarSelActividad);
            compSession(llenarSelTarifa);
        }
    );


    $("#busGenInpZonIni").keyup(
        function(){
            RefZonIni();
        }
    );

    $("#busGenInpZonFin").keyup(
        function(){
            RefZonFin();
        }
    );


    $("#busGenInpNomVia").keyup(
        function(){
            RefNomVia();
        }
    );

    $("#busGenInpUrb").keyup(
        function(){
            RefUrbanizacion();
        }
    );

    $("#busGenInpUrb").blur(
        function(){
            descUrb();
        }
    );


    $('#busGenInpProIni').blur(
        function(){
            complementaInpProFin();
        }
    );
    $('#busGenInpProFin').blur(
        function(){
            complementaInpProFin2();
        }
    )

    $('#busGenInpCatastroIni').blur(
        function(){
            complementaInpCatFin();
        }
    );
    $('#busGenInpCatastroFin').blur(
        function(){
            complementaInpCatFin2();
        }
    )

});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function complementaInpProFin(){
    var faltante =11-$('#busGenInpProIni').val().length;
    $('#busGenInpProFin').val(($('#busGenInpProIni').val()+faltanteFunc('9',faltante)).substr(0,11));
    $('#busGenInpProIni').val(($('#busGenInpProIni').val()+faltanteFunc('0',faltante)).substr(0,11));
}


function complementaInpCatFin(){
    var faltante =15-$('#busGenInpCatastroIni').val().length;
    $('#busGenInpCatastroFin').val(($('#busGenInpCatastroIni').val()+faltanteFunc('9',faltante)).substr(0,15));
    $('#busGenInpCatastroIni').val(($('#busGenInpCatastroIni').val()+faltanteFunc('0',faltante)).substr(0,15));
}


function complementaInpProFin2(){
    var faltante =11-$('#busGenInpProFin').val().length;
    $('#busGenInpProFin').val(($('#busGenInpProFin').val()+faltanteFunc('9',faltante)).substr(0,11));

}
function complementaInpCatFin2(){
    var faltante =15-$('#busGenInpCatastroFin').val().length;
    $('#busGenInpCatastroFin').val(($('#busGenInpCatastroFin').val()+faltanteFunc('9',faltante)).substr(0,15));

}


function faltanteFunc(constante,numero){
    var res="";
    for(x=1;x<=numero;x++)
    {
        res +=""+constante ;
    }
    return res;
}


function RefZonIni(){

    $( "#busGenInpZonIni" ).autocomplete({
        source: function ( request,response) {
            $.ajax({
                type: "POST",
                url:"../datos/datos.consulta.php",
                data: { tip : 'autComZon',proy:$("#busGenSelPro").val(),term:$("#busGenInpZonIni").val() },
                success:response,
                dataType: 'json'
            });
        }
    }, {minLength: 0 });
}

function descUrb(){

    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'text',
        data : { tip : 'inpUrbDesc',urb:$("#busGenInpUrb").val(),proy:$("#busGenSelPro").val() },
        success : function(json) {


            $("#busGenInpUrbDesc").val(json);

        },
        error : function(xhr, status) {

        }
    });
}


function RefUrbanizacion(){

    $( "#busGenInpUrb" ).autocomplete({
        source: function ( request,response) {
            $.ajax({
                type: "POST",
                url:"../datos/datos.consulta.php",
                data: { tip : 'autComUrb',proy:$("#busGenSelPro").val(),term:$("#busGenInpUrb").val()},
                success:response,
                dataType: 'json'
            });
        }
    }, {minLength: 0 });
}


function RefNomVia(){

    $( "#busGenInpNomVia" ).autocomplete({
        source: function ( request,response) {
            $.ajax({
                type: "POST",
                url:"../datos/datos.consulta.php",
                data: { tip : 'autComNomVia',proy:$("#busGenSelPro").val(),term:$("#busGenInpNomVia").val(),tipVia:$("#busGenSelTipVia").val() },
                success:response,
                dataType: 'json'
            });
        }
    }, {minLength: 0 });


}

function RefZonFin(){
    $( "#busGenInpZonFin" ).autocomplete({
        source: function ( request,response) {
            $.ajax({
                type: "POST",
                url:"../datos/datos.consulta.php",
                data: { tip : 'autComZon',proy:$("#busGenSelPro").val(),term:$("#busGenInpZonFin").val() },
                success:response,
                dataType: 'json'
            });
        }
    }, {minLength: 0 });


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



function llenarSelMed()
{
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selMed' },
        success : function(json) {
            $('#busGenSelMarMed').append(new Option('Marca:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelMarMed').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelSum()
{
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selSum' },
        success : function(json) {
            $('#busGenSelSuministro').append(new Option('Suministro:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelSuministro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelEmpl()
{
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEmp' },
        success : function(json) {
            $('#busGenSelEmpla').append(new Option('Emplazamiento:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelEmpla').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelPro()
{
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#busGenSelPro').append(new Option('Acueducto:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelGrupoCli()
{
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selGrupoCli' },
        success : function(json) {
            $('#busGenSelGrupoCli').append(new Option('Grupo:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelGrupoCli').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelTipoCli()
{
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selTipoCli' },
        success : function(json) {
            $('#busGenSelTipocli').append(new Option('Tipo Cliente:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelTipocli').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelTipVia()
{
    $('#busGenSelTipVia').empty();
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selTipVia',pro:$('#busGenSelPro').val() },
        success : function(json) {

            $('#busGenSelTipVia').append(new Option('Tipo Vía:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelTipVia').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelEstInm()
{
    $('#busGenSelEstInmCod').empty();
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEstInm',est:$('#busGenSelEstInm').val() },
        success : function(json) {

            $('#busGenSelEstInmCod').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelEstInmCod').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelUso()
{
    $('#busGenSelUso').empty();
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUso' },
        success : function(json) {

            $('#busGenSelUso').append(new Option('Seleccione Uso:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelUso').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelActividad()
{
    $('#busGenSelActividad').empty();
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selAct',uso:$("#busGenSelUso").val() },
        success : function(json) {


            $('#busGenSelActividad').append(new Option('Seleccione Actividad:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#busGenSelActividad').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelTarifa()
{
    $('#rendCorSelTarifa').empty();
    $.ajax
    ({
        url : '../datos/datos.consulta.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selTarifas',uso:$("#busGenSelUso").val(),proy:$('#busGenSelPro').val()},
        success : function(json) {

            $('#rendCorSelTarifa').append(new Option('Seleccione Tarifa:', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#rendCorSelTarifa').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
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
                $("#busGenSelPro").empty();
                llenarSelPro();
                //compSession(flexyListInm);
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
            alert("error");
            return false;
        }
    });

}


///// FUNCIONES POP UP
function flexyListInm(){
    var parametros = top.frames.jobFrame.$('#ConsGenForm').serializeArray();
    parametros.push({name: 'tip', value: 'flexyInm'});
    //console.log(parametros)

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexGenListInm").flexigrid
    (
        {
            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            type:  'post',

            colModel : [
                {display: 'No', name: 'rnum', width:40,  align: 'center'},
                {display: 'Acueducto', name: 'ID_PROYECTO', width: 50, sortable: true, align: 'center'},
                {display: 'Tipo Cliente', name: 'TIPO_CLIENTE', width: 50, sortable: true, align: 'center'},
                {display: 'Cod Sistema', name: 'codigo_inm', width: 60, sortable: true, align: 'center'},
                {display: 'Zona', name: 'ID_ZONA', width: 30, sortable: true, align: 'center'},
                {display: 'Urbanizaci\u00F3n', name: 'DESC_URBANIZACION', width: 82, sortable: true, align: 'center'},
                {display: 'Direcci\u00F3n', name: 'DIRECCION', width: 127, sortable: true, align: 'center'},
                {display: 'Estado', name: 'ID_ESTADO', width: 32, sortable: true, align: 'center'},
                {display: 'Catastro', name: 'CATASTRO', width: 120, sortable: true, align: 'center'},
                {display: 'Proceso', name: 'ID_PROCESO', width: 65, sortable: true, align: 'center'},
                {display: 'Uso', name: 'ID_USO', width: 50, sortable: true, align: 'center'},
                {display: 'Categoria', name: 'CATEGORIA', width: 50, sortable: true, align: 'center'},
                {display: 'Cliente', name: 'CODIGO_CLI', width: 40, sortable: true, align: 'center'},
                {display: 'Nombre', name: 'ALIAS', width: 260, sortable: true, align: 'center'},
                {display: 'C\u00E9dula', name: 'DOCUMENTO', width: 80, sortable: true, align: 'center'},
                {display: 'Medidor', name: 'SERIAL', width: 60, sortable: true, align: 'center'},
                {display: 'Calibre', name: 'DESC_CALIBRE', width: 60, sortable: true, align: 'center'},
                {display: 'Fecha Inst.', name: 'FECHA_INSTALACION', width: 60, sortable: true, align: 'center'},
                {display: 'Fecha Alta', name: 'FEC_ALTA', width: 60, sortable: true, align: 'center'},
                {display: 'Suministro', name: 'METODO_SUMINISTRO', width: 50, sortable: true, align: 'center'}
            ],
            usepager: true,
            title: 'Busqueda general de inmuebles',
            useRp: false,
            page: 1,
            rp: 500,
            sortname: 'I.CODIGO_INM',
            sortorder: "DESC",
            onSuccess: function(){asigevenConsGen()},
            showTableToggleBtn: false,
            width: 1200,
            height: 245,
            params: parametros
        }
    );
    $("#flexGenListInm").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexGenListInm").flexOptions({params: parametros});
    $("#flexGenListInm").flexReload();

}



function flexyMed(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyMed'});
    parametros.push({name: 'inm', value: codCol});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexMed").flexigrid
    (
        {

            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            type:  'post',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:15,  align: 'center'},
                {display: 'Serial', name: 'SERIAL', width:80,  align: 'center'},
                {display: 'Marca', name: 'DESC_MED', width: 80, sortable: true, align: 'center'},
                {display: 'Calibre', name: 'DESC_CALIBRE', width: 50, sortable: true, align: 'center'},
                {display: 'Emplazamiento', name: 'DESC_EMPLAZAMIENTO', width: 90, sortable: true, align: 'center'},
                {display: 'Fecha Instalaci\u00F3n', name: 'FECHA', width: 130, sortable: true, align: 'center'},
                {display: 'Fecha Baja', name: 'FECBAJA', width: 130, sortable: true, align: 'center'},
                {display: 'Estado Medidor', name: 'DESCRIPCION', width: 140, sortable: true, align: 'center'},
                {display: 'M\u00E9todo Suministro', name: 'DESC_SUMINISTRO', width: 120, sortable: true, align: 'center'},
                {display: 'Lectura Instalaci\u00F3n', name: 'LECTURA_INSTALACION', width: 100, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_INSTALACION",
            sortorder: "DESC",
            usepager: false,
            //title: 'Datos Medidor',
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            //width: 1000,
            height: 180,
            pagestat: 'Mostrando del {from} al {to} de {total} registros',
            procmsg: 'Procesando, un momento por favor ...',
            nomsg: 'No existen registros para su consulta',
            params: parametros
        }
    );
    $("#flexMed").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexMed").flexOptions({params: parametros});
    $("#flexMed").flexReload();

}


function flexyMedHij(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyMedHij'});
    parametros.push({name: 'inm', value: codCol});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexMedHij").flexigrid
    (
        {

            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            type:  'post',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:15,  align: 'center'},
                {display: 'Cod Sistema', name: 'rnum', width:80,  align: 'center'},
                {display: 'Serial', name: 'SERIAL', width:80,  align: 'center'},
                {display: 'Marca', name: 'DESC_MED', width: 80, sortable: true, align: 'center'},
                {display: 'Calibre', name: 'DESC_CALIBRE', width: 50, sortable: true, align: 'center'},
                {display: 'Emplazamiento', name: 'DESC_EMPLAZAMIENTO', width: 90, sortable: true, align: 'center'},
                {display: 'Fecha Instalaci\u00F3n', name: 'FECHA', width: 130, sortable: true, align: 'center'},
                {display: 'Estado Medidor', name: 'DESCRIPCION', width: 140, sortable: true, align: 'center'},
                {display: 'M\u00E9todo Suministro', name: 'DESC_SUMINISTRO', width: 120, sortable: true, align: 'center'},
                {display: 'Lectura Instalaci\u00F3n', name: 'LECTURA_INSTALACION', width: 100, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_INSTALACION",
            sortorder: "DESC",
            usepager: false,
            //title: 'Datos Medidor',
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            //width: 1000,
            height: 180,
            pagestat: 'Mostrando del {from} al {to} de {total} registros',
            procmsg: 'Procesando, un momento por favor ...',
            nomsg: 'No existen registros para su consulta',
            params: parametros
        }
    );
    $("#flexMedHij").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexMedHij").flexOptions({params: parametros});
    $("#flexMedHij").flexReload();

}

function flexyLec(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyLec'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyLec").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',
            type:  'post',
            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:15,  align: 'center'},
                {display: 'Periodo', name: 'PERIODO', width:60,  align: 'center'},
                {display: 'Lectura', name: 'LECTURA_ACTUAL', width: 62, sortable: true, align: 'center'},
                {display: 'Consumo', name: 'CONSUMO', width: 62, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECLEC', width: 80, sortable: true, align: 'center'},
                {display: 'Observaci\u00F3n', name: 'OBSERVACION', width: 70, sortable: true, align: 'center'},
                {display: 'Lector', name: 'COD_LECTOR', width: 200, sortable: true, align: 'center'}
            ],

            sortname: "PERIODO",
            sortorder: "DESC",
            usepager: false,
            //title: 'Datos Medidor',
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            //width: 1000,
            height: 180,
            width: 1200,
            pagestat: 'Mostrando del {from} al {to} de {total} registros',
            procmsg: 'Procesando, un momento por favor ...',
            nomsg: 'No existen registros para su consulta',
            params: parametros
        }
    );
    $("#flexyLec").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyLec").flexOptions({params: parametros});
    $("#flexyLec").flexReload();

}

function flexyServ(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyServ'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyServ").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',
            type:  'post',
            dataType: 'json',
            colModel : [
                {display: 'C\u00F3digo;', name: 'COD_SERVICIO', width:30, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'DESC_SERVICIO', width:100,  align: 'center'},
                {display: 'Tarifa', name: 'CONSEC_TARIFA', width: 240, sortable: true, align: 'center'},
                {display: 'Unidades', name: 'UNIDADES_TOT', width: 62, sortable: true, align: 'center'},
                {display: 'Habitadas', name: 'UNIDADES_HAB', width: 80, sortable: true, align: 'center'},
                {display: 'Deshabitadas', name: 'UNIDADES_DESH', width: 70, sortable: true, align: 'center'},
                {display: 'Cupo B\u00E1sico', name: 'CUPO_BASICO', width: 100, sortable: true, align: 'center'},
                {display: 'Promedio', name: 'PROMEDIO', width: 100, sortable: true, align: 'center'},
                {display: 'Consumo M\u00EDnimo', name: 'CONSUMO_MINIMO', width: 100, sortable: true, align: 'center'},
                {display: 'Calculo', name: 'DESC_CALCULO', width: 100, sortable: true, align: 'center'},
                {display: 'Diametro', name: 'DIAMETRO', width: 100, sortable: true, align: 'center'}
            ],

            sortname: "COD_SERVICIO",
            sortorder: "ASC",
            usepager: false,
            //title: 'Datos Medidor',
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            //width: 1000,
            height: 180,
            pagestat: 'Mostrando del {from} al {to} de {total} registros',
            procmsg: 'Procesando, un momento por favor ...',
            nomsg: 'No existen registros para su consulta',
            params:parametros
        }
    );
    $("#flexyServ").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyServ").flexOptions({params: parametros});
    $("#flexyServ").flexReload();

}


function flexyfacturas(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyFact'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyFact").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',
            type:  'post',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:20,  align: 'center'},
                {display: 'Consec<br/>Factura', name: 'CONSEC_FACTURA', width: 55, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'Periodo', width: 44, sortable: true, align: 'center'},
                {display: 'Fecha<br/>Lectura', name: 'FEC_LECT', width: 60, sortable: true, align: 'center'},
                {display: 'Consumo<br/>Fact', name: 'LECTURA', width: 54, sortable: true, align: 'center'},
                {display: 'Fecha<br/>Expedicion', name: 'FEC_EXPEDICION', width: 60, sortable: true, align: 'center'},
                {display: 'NCF', name: 'NCF', width: 120, sortable: true, align: 'center'},
                {display: 'Valor', name: 'TOTAL', width: 35, sortable: true, align: 'center'},
                {display: 'Pagado', name: 'TOTAL_PAGADO', width: 35, sortable: true, align: 'center'},
                {display: 'Fecha <br/> pago', name: 'FECHA_PAGO', width: 60, sortable: true, align: 'center'},
                {display: 'Dias', name: 'Dias', width: 30, sortable: true, align: 'center'},
                {display: 'Reliquida', name: 'ANTERIORES', width: 40, sortable: true, align: 'center'},
                {display: 'Nota Crédito', name: 'NOTACREDITO', width: 40, sortable: true, align: 'center'},
                {display: 'Envio Correo Electronico', name: 'EMAIL', with: 150, sortable: true, aling: 'center'}
            ],

            sortname: "PERIODO",
            sortorder: "desc",
            usepager: false,
            //title: 'facturas',
            onSuccess: function(){asigevenFactura()},
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 600,
            height: 235,
            params:parametros
        }
    );
    $("#flexyFact").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyFact").flexOptions({params: parametros});
    $("#flexyFact").flexReload();
}


function asigevenConsGen() {
    tabflexPag = document.getElementById("flexGenListInm");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",cargainfoInm);
        }

    }
}


function cargainfoInm(){
    codCol=this.getAttribute("id").replace("row","");
    $("#detConDatInm").show();
    compSession(llenarDatInmGen);
    compSession(llenarDatFav);
    compSession(llenarDatTot);
    compSession(llenarDatDif);
    compSession(llenarDatRec);
    compSession(flexyMed);
    compSession(flexyMedHij);
    compSession(flexyLec);
    compSession(flexyServ);
    compSession(flexyfacturas);
    compSession(flexyDiferidos);
    compSession(flexyPdc);
    compSession(flecyDiferidosGen);
    compSession(flexyEstcuenta);
    compSession(popUpEstCuenta);
    compSession(flexyPagos);
    compSession(flexyOtrosRec);
    compSession(flexyCor);
    compSession(flexyRec);
    compSession(flexyObs);
    compSession(flexyDeudaCero);
    compSession(flexySaldFav);
    compSession(flexiReclamos);
    compSession(muestraFotos);
    compSession(flexiDocumentos);


}

function muestraFotos()
{

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'fotosInm',inm:codCol},
        success : function(json) {

            if(json){
                $("ul li:eq(13)").show();
                for(var x=0;x<json.length;x++)
                {
                    var img= $('<img/>');
                    var nombre=(json[x]["NOMBRE_FOTO"]);
                    if (json[x]["URL_FOTO"].indexOf('mantenimiento') == -1) {
                        img.attr('src','../../../webservice/webservice/'+(json[x]["URL_FOTO"]));
                    } else {
                        var foto = json[x]["URL_FOTO"].substring(2);
                        foto = '../fotos_sgc'+foto;
                        img.attr('src','../../../webservice/webservice/'+foto);
                    }
                    img.attr('width','640PX');
                    img.attr('heigth','480px');
                    //  alert('../../webservice/'+json[x]["URL_FOTO"]);
                    $('#fotosConsGen').append('<p><strong>'+nombre+'</strong></p>');
                    $('#fotosConsGen').append(img);

                }


            }else{
                $("ul li:eq(13)").hide();
            }

        },
        error : function(xhr, status) {

        }
    });
}





function popUpEstCuenta(){
    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();

    var formattedTime = hours +""+ minutes +""+ seconds;


    $("#linkImpEstCuenta").attr("href", "../../facturacion/datos/datos.RepEstCuen.php?tip=rep&temp="+formattedTime+"&inmueble="+codCol);
    $("#linkImpEstConcepto").attr("href", "../../facturacion/datos/datos.RepEstCon.php?tip=rep&temp="+formattedTime+"&inmueble="+codCol);
}


function actdatos(idContrato){

    window.open("../../catastro/vistas/vista.cambiousuario.php?id_contrato="+idContrato, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400');
}


function llenarDatInmGen()
{

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'fielSetInm',inm:codCol},
        success : function(json) {

            if(json[0]['SERIAL']!=null){


                $("ul li:eq(0)").show();
                $("ul li:eq(2)").show();


            }else{


                $("ul li:eq(0)").hide();
                $("ul li:eq(2)").hide();
            }

            $("#LegDetConDatInmCod").text('Inmueble N°: '+codCol);
            $("#inpDetConDatInmZon").val(json[0]['ID_ZONA']);
            $("#inpDetConDatInmDir").val(json[0]['DIRECCION']);
            $("#inpDetConDatInmUrb").val(json[0]['DESC_URBANIZACION']);
            $("#inpDetConDatInmFecAlt").val(json[0]['FEC_ALTA']);
            $("#inpDetConDatInmCat").val(json[0]['CATASTRO']);
            $("#inpDetConDatInmPro").val(json[0]['ID_PROCESO']);
            $("#inpDetConDatInmEst").val(json[0]['ID_ESTADO']);

            if(json[0]['ID_ESTADO']=="PC" || json[0]['ID_ESTADO']=="SS"){
                $("#divReco").show();
            }else{
                $("#divReco").hide();
            }

            $("#inpDetConDatInmCli").val(json[0]['CODIGO_CLI']);
            if($("#inpDetConDatInmCli").val()!="9999999"){
                $("#divClie").show();
            }else{
                $("#divClie").hide();
            }
            $("#inpDetConDatInmNom").val(json[0]['ALIAS']);
            codigoContrato = json[0]['ID_CONTRATO'];
            $("#inpDetConDatInmCon").val(codigoContrato);
            if($("#inpDetConDatInmCon").val()!=""){
                $("#divContrato").show();
            }else{
                $("#divContrato").hide();
            }
            $("#inpDetConDatEstCre").val(json[0]['ESTADO_CREDITO']);
            if($("#inpDetConDatEstCre").val()=="GESTION LEGAL"){
                $("#divEstCre").show();
                $("#inpDetConDatEstCreLegend").val('EN GESTIÓN LEGAL - REFERIR A OFICINA DE ABOGADOS');
                $("#inpDetConDatEstCreLegend").css("color", "red");
                $("#inpDetConDatEstCreLegend").show();
                $("#inpDetConDatEstCre").css("color", "red");
            }
            else if ($("#inpDetConDatEstCre").val()=="DATA CREDITO"){
                $("#inpDetConDatEstCreLegend").hide();
                $("#divEstCre").show();
                //$("#inpDetConDatEstCreLegend").val('EN GESTIÓN LEGAL - REFERIR A OFICINA DE ABOGADOS');
                //$("#inpDetConDatEstCreLegend").css("color", "red");
                //$("#inpDetConDatEstCreLegend").show();
                $("#inpDetConDatEstCre").css("color", "red");
            }
            else{
                $("#inpDetConDatEstCreLegend").hide();
                $("#divEstCre").hide();
                //$("#inpDetConDatEstCreLegend").val('');
            }
            $("#inpDetConDatInmDoc").val(json[0]['DOCUMENTO']);
            if($("#inpDetConDatInmDoc").val()!="9999999"){
                $("#divDoc").show();
            }else{
                $("#divDoc").hide();
            }

            $("#inpDetConDatInmTel").val(json[0]['TELEFONO']);
            if($("#inpDetConDatInmTel").val()!=""){
                $("#divTel").show();
            }else{
                $("#divTel").hide();
            }
            $("#inpDetConDatInmEmail").val(json[0]['EMAIL']);
            if($("#inpDetConDatInmEmail").val()!=""){
                $("#divEmail").show();
            }else{
                $("#divEmail").hide();
            }

            $("#inpDetConDatInmFav").val(json[0]['DESC_EMPLAZAMIENTO']);
            $("#inpDetConDatInmFac").val(json[0]['FACTURAS']);
            $("#inpDetConDatInmFac").css("color", "red");
            $("#inpDetConDatInmDeu").val(json[0]['DEUDA']);
            $("#inpDetConDatInmDeu").formatCurrency({ region: 'es-DO' });
            $("#inpDetConDatInmDeu").css("color", "red");



            $('#btnActualizarDatos').click(
                function(){
                    actdatos(codigoContrato);
                }
            );


        },
        error : function(xhr, status) {

        }
    });
}

function llenarDatFav(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'saldFav',inm:codCol},
        success : function(json) {
            $("#inpDetConDatInmFav").val(json[0]['SALDO']);
            $("#inpDetConDatInmFav").formatCurrency({ region: 'es-DO' });
            $("#inpDetConDatInmFav").css("color", "green");

        },
        error : function(xhr, status) {

        }
    });
}


function llenarDatTot(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'text',
        data : { tip : 'tipTot',inm:codCol},
        success : function(text) {
            $("#inpDetConDatTota").val(text);
            if(text=="Padre"){
                $("ul li:eq(1)").show();
            }else{
                $("ul li:eq(1)").hide();
            }


        },
        error : function(xhr, status) {

        }
    });
}


function llenarDatDif(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'difPend',inm:codCol},
        success : function(json) {
            $("#inpDetConDatInmDif").val(json[0]['DIFERIDO']);
            $("#inpDetConDatInmDif").formatCurrency({ region: 'es-DO' });
            $("#inpDetConDatInmDif").css("color", "red");

        },
        error : function(xhr, status) {

        }
    });
}
function llenarDatRec(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'recValPend',inm:codCol},
        success : function(json) {
            $("#inpDetConDatInmRec").val(json[0]['VALOR_TARIFA']);

            $("#inpDetConDatInmRec").formatCurrency({ region: 'es-DO' });
            $("#inpDetConDatInmRec").css("color", "red");


        },
        error : function(xhr, status) {

        }
    });
}


function asigevenFactura() {
    tabflexFac = document.getElementById("flexyFact");
    filflexFac = tabflexFac.getElementsByTagName("tr");
    for (i = 0; i < filflexFac.length; i++) {
        if(filflexFac[i]){
            var currentRow = filflexFac[i];
            currentRow.addEventListener("click",eventoFactura);
        }

    }
}

function getRadioButtonSelectedValue(ctrl)
{
    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) return ctrl[i].value;
}

function eventoFactura(){
    var valorrdbutton=getRadioButtonSelectedValue($("#rbfactipofac"));

    facCol=this.getAttribute("id").replace("row","");
    if(valorrdbutton=='M'){
        document.getElementById("ifpdf").data="../../facturacion/clases/classFacturaPdf.php?factura="+facCol;
    }else{
        document.getElementById("ifpdf").data="../../facturacion/clases/classFacturaPdf2.php?factura="+facCol;
    }

    flexydetfactura();
    flexydetlectura();
    flexyDetPagApl();

}

function flexydetfactura(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyDetFac'});
    parametros.push({name: 'fac', value: facCol});
    parametros.push({name: 'inm', value: codCol});
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatdetfactura").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            type:  'post',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:20,  align: 'center'},
                {display: 'Concepto', name: 'CONCEPTO', width: 120, sortable: true, align: 'center'},
                {display: 'Rango', name: 'RANGO', width: 40, sortable: true, align: 'center'},
                {display: 'Unidades', name: 'UNIDADES', width: 55, sortable: true, align: 'center'},
                {display: 'Precio', name: 'PRECIO', width: 50, sortable: true, align: 'center'},
                {display: 'Valor', name: 'VALOR', width: 90, sortable: true, align: 'center'}
            ],

            sortname: "CONCEPTO, RANGO",
            sortorder: "ASC",
            usepager: false,
            title: 'Detalle Factura',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 380,
            height: 170,
            params:parametros
        }
    );
    $("#flexdatdetfactura").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexdatdetfactura").flexOptions({params: parametros});
    $("#flexdatdetfactura").flexReload();
}


function flexydetlectura(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyFacLecEnt'});
    parametros.push({name: 'fac', value: facCol});;
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatdetlectura").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            colModel : [
                {display: 'Lectura', name: 'LECTURA_ORIGINAL', width:60,  align: 'center'},
                {display: 'Observaci&oacute;n', name: 'OBSERVACION_ACTUAL', width: 90, sortable: true, align: 'center'},
                {display: 'Lector', name: 'LOGIN', width: 120, sortable: true, align: 'center'},
                {display: 'Consumo', name: 'CONSUMO', width: 60, sortable: true, align: 'center'}
            ],

            sortname: "LOGIN",
            sortorder: "ASC",
            usepager: false,
            title: 'Detalle Lectura y Entrega de Facturas',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 350,
            height: 170,
            params:parametros
        }
    );
    $("#flexdatdetlectura").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexdatdetlectura").flexOptions({params: parametros});
    $("#flexdatdetlectura").flexReload();
}


function flexyDetPagApl(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexDatPagAplicFct'});
    parametros.push({name: 'fac', value: facCol});;
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexDatPagAplicFct").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            colModel : [
                {display: 'Cod Pago', width:60,  align: 'center'},
                {display: 'Fecha', width: 90, sortable: true, align: 'center'},
                {display: 'Importe', width: 120, sortable: true, align: 'center'},
                {display: 'Total Pago', width: 60, sortable: true, align: 'center'}
            ],

            sortname: "PA.FECHA_PAGO",
            sortorder: "DESC",
            usepager: false,
            title: 'Detalle Pago aplicado',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 350,
            height: 170,
            params:parametros
        }
    );
    $("#flexDatPagAplicFct").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexDatPagAplicFct").flexOptions({params: parametros});
    $("#flexDatPagAplicFct").flexReload();
}



function flexyDiferidos(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexDatDif'});
    parametros.push({name: 'inm', value: codCol});
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexDatDif").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:30,  align: 'center'},
                {display: 'Codigo Diferido', name: 'VALOR_DIFERIDO', width: 90, sortable: true, align: 'center'},
                {display: 'Descripcion',  width: 90, sortable: true, align: 'center'},
                {display: 'Valor Cuotas', name: 'RANGO', width: 100, sortable: true, align: 'center'},
                {display: 'Numero Cuotas', name: 'CUTAS_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Diferido', name: 'VALOR_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Cuotas pagadas', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'},
                {display: 'Valor pagado',  width: 100, sortable: true, align: 'center'},
                {display: 'pendiente',  width: 100, sortable: true, align: 'center'}
            ],

            sortname: "CODIGO",
            sortorder: "DESC",
            usepager: false,
            title: 'Diferidos Inmueble',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 1000,
            height: 80,
            params:parametros
        }
    );
    $("#flexDatDif").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexDatDif").flexOptions({params: parametros});
    $("#flexDatDif").flexReload();
}


function flexyPdc(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexDatPdc'});
    parametros.push({name: 'inm', value: codCol});
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexDatPdc").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:30,  align: 'center'},
                {display: 'Valor PDC', name: 'VALOR_DIFERIDO', width: 90, sortable: true, align: 'center'},
                {display: 'Total Cuotas', name: 'RANGO', width: 100, sortable: true, align: 'center'},
                {display: 'Cuotas Pagadas', name: 'CUTAS_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Pagado', name: 'VALOR_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Pendiente', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'}
            ],

            sortname: "ID_DEUDA_CERO",
            sortorder: "DESC",
            usepager: false,
            title: 'Plan Deuda Cero Inmueble',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 460,
            height: 150,
            params:parametros
        }
    );
    $("#flexDatPdc").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexDatPdc").flexOptions({params: parametros});
    $("#flexDatPdc").flexReload();
}
function flexyEstcuenta(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexEstCuenta'});
    parametros.push({name: 'inm', value: codCol});
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexDatFacEstCuen").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'Fecha', name: 'CONSEC_FACTURA', width: 60, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'Periodo', width: 433, sortable: true, align: 'center'},
                {display: 'Debe', name: 'FEC_LECT', width: 36, sortable: true, align: 'center'},
                {display: 'Haber', name: 'LECTURA', width: 39, sortable: true, align: 'center'},
                {display: 'Saldo', name: 'FEC_EXPEDICION', width: 44, sortable: true, align: 'center'}
            ],

            //sortname: "PERIODO",
            //sortorder: "desc",
            //usepager: false,
            title: 'Estado de cuenta Inmueble',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 600,
            height: 150,
            params:parametros
        }
    );
    $("#flexDatFacEstCuen").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexDatFacEstCuen").flexOptions({params: parametros});
    $("#flexDatFacEstCuen").flexReload();
}

function recargaifr(){

    var valorrdbutton=getRadioButtonSelectedValue($("input[value='rbfactipofac']"));
    if(valorrdbutton=='M'){
        document.getElementById("ifpdf").data="../../facturacion/clases/classFacturaPdf.php?factura="+facCol;
    }else{
        document.getElementById("ifpdf").data="../../facturacion/clases/classFacturaPdf2.php?factura="+facCol;
    }

}

function flecyDiferidosGen(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyDiferidos'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyDiferidos").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:15,  align: 'center'},
                {display: 'C\xF3digo<br>Diferido', name: 'DIFERIDO', width: 40, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'CONCEPTO', width: 50, sortable: true, align: 'center'},
                {display: 'Fecha<br>Apertura', name: 'FEC_APERTURA', width: 60, sortable: true, align: 'center'},
                {display: 'Valor<br>Diferido', name: 'VAL_DIF', width: 50, sortable: true, align: 'center'},
                {display: 'Activo', name: 'ACTIVO', width: 30, sortable: true, align: 'center'},
                {display: 'Cuotas<br>Pagadas', name: 'CUOTAS_PAGADAS', width: 45, sortable: true, align: 'center'},
                {display: 'Valor<br>Pagado ', name: 'VAL_PAGO', width: 50, sortable: true, align: 'center'},
                {display: 'Numero<br>Cuotas ', name: 'NUMERO_CUOTAS', width: 40, sortable: true, align: 'center'},
                {display: 'Periodo<br>Inicio', name: 'PERIODO_INI', width: 50, sortable: true, align: 'center'}

            ],
            sortname: "PER_INI",
            sortorder: "desc",
            usepager: false,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 800,
            height: 171,
            onSuccess: function(){asigevenDifGen()},
            params:parametros
        }
    );
    $("#flexyDiferidos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyDiferidos").flexOptions({params: parametros});
    $("#flexyDiferidos").flexReload();
}

function flecyCuotasDiferidosGen(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyCuotaDiferidos'});
    parametros.push({name: 'dif', value: difCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyCuotaDiferidos").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:10,  align: 'center'},
                {display: 'Numero<br/>cuota', name: 'CUOTA', width: 37, sortable: true, align: 'center'},
                {display: 'Valor<br/>cuota', name: 'VAL_CUOTA', width: 70, sortable: true, align: 'center'},
                {display: 'Valor<br/>pagado', name: 'VAL_PAGADO', width: 60, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'PERIODO', width: 35, sortable: true, align: 'center'},
                {display: 'Fecha<br/>pago', name: 'FECHA_PAGO', width: 55, sortable: true, align: 'center'},


            ],
            sortname: "DD.PERIODO",
            sortorder: "desc",
            usepager: false,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 400,
            height: 171,
            params:parametros
        }
    );
    $("#flexyCuotaDiferidos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyCuotaDiferidos").flexOptions({params: parametros});
    $("#flexyCuotaDiferidos").flexReload();
}

function asigevenDifGen() {
    tabflexPag = document.getElementById("flexyDiferidos");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",cargainfoDif);
        }

    }
}

function cargainfoDif(){
    difCol=this.getAttribute("id").replace("row","");

    compSession(flecyCuotasDiferidosGen);



}

function flexyOtrosRec(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyOtrosRec'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexOtroRec").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:20,  align: 'center'},
                {display: 'Codigo', name: 'CODIGO', width: 50, sortable: true, align: 'center'},
                {display: 'Fecha Dig.', name: 'FECHADIG', width: 85, sortable: true, align: 'center'},
                {display: 'Fecha Pago.', name: 'FECHAPAG', width: 85, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'DESC_SERVICIO', width: 200, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                {display: 'Usuario', name: 'LOGIN', width: 130, sortable: true, align: 'center'},
                {display: 'Fecha anula.', name: 'FECHA_REV', width: 85, sortable: true, align: 'center'},
                {display: 'Usuario anula.', name: 'USRREV', width: 130, sortable: true, align: 'center'}
            ],

            sortname: "ORE.FECHA",
            sortorder: "desc",
            usepager: false,
            title: 'Otros Recaudos',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            onSuccess: function(){asigevenOtrosRec()},
            width: 900,
            height: 180,
            params:parametros
        }
    );
    $("#flexOtroRec").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexOtroRec").flexOptions({params: parametros});
    $("#flexOtroRec").flexReload();
}


function flexFacApliOtrRec(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyAplOreFac'});
    parametros.push({name: 'ore', value: otrRecCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexFacApliOtrRec").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'Periodo', name: 'rnum', width:80,  align: 'center'},
                {display: 'Factura', name: 'CODIGO', width: 80, sortable: true, align: 'center'},
                {display: 'valor', name: 'FECHADIG', width: 85, sortable: true, align: 'center'}

            ],

            sortname: "F.PERIODO",
            sortorder: "desc",
            usepager: false,
            title: 'Facturas aplicadas',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            onSuccess: function(){asigevenOtrosRec()},
            width: 300,
            height: 180,
            params:parametros
        }
    );
    $("#flexFacApliOtrRec").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexFacApliOtrRec").flexOptions({params: parametros});
    $("#flexFacApliOtrRec").flexReload();
}


function asigevenOtrosRec() {
    tabflexPag = document.getElementById("flexOtroRec");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",eventoOtrosRec);
        }

    }
}

function eventoOtrosRec(){

    otrRecCol=this.getAttribute("id").replace("row","");
    compSession(flexFacApliOtrRec);
    compSession(formpagoOtrosRec);
    compSession(entpagoOtrosRec);
}


function formpagoOtrosRec(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'tipForRec',ore:otrRecCol },
        success : function(json) {


            for(var x=0;x<json.length;x++)
            {
                if(x==0){
                    $("#DatOtrReclbfrmpago").text(json[x]["DESCRIPCION"]);
                }else{
                    $("#DatOtrReclbfrmpago").text($("#DatOtrReclbfrmpago").text()+','+json[x]["DESCRIPCION"]);
                }
            }

        },
        error : function(xhr, status) {

        }
    });


}

function entpagoOtrosRec(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'ubiRec',ore:otrRecCol },
        success : function(json) {

            $("#DatOtrReclbentidad").text("");
            $("#DatOtrReclbpunto").text("");
            $("#DatOtrReclbcaja").text("");

            for(var x=0;x<json.length;x++)
            {


                if(x==0){

                    $("#DatOtrReclbentidad").text(json[x]["DESC_ENTIDAD"]);
                    $("#DatOtrReclbpunto").text(json[x]["DESC_PUNTO"]);
                    $("#DatOtrReclbcaja").text(json[x]["DESC_CAJA"]);


                }else{
                    $("#DatOtrReclbentidad").text($("#DatOtrReclbentidad").text()+','+json[x]["DESC_ENTIDAD"]);
                    $("#DatOtrReclbpunto").text($("#DatOtrReclbpunto").text()+','+json[x]["DESC_PUNTO"]);
                    $("#DatOtrReclbcaja").text($("#DatOtrReclbcaja").text()+','+json[x]["DESC_CAJA"]);



                }
            }

        },
        error : function(xhr, status) {

        }
    });

}

function flexyPagos(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyPagos'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyPagos").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:20,  align: 'center'},
                {display: 'Cod. pago', name: 'ID_PAGO', width: 80, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA_PAGO', width: 85, sortable: true, align: 'center'},
                {display: 'Referencia', name: 'REFERENCIA', width: 371, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                {display: 'Motivo Anula', name: 'MOTIVO_REV', width: 280, sortable: true, align: 'center'},
                {display: 'Fecha Anula', name: 'FECHA_REV', width: 80, sortable: true, align: 'center'},
                {display: 'Usuario Anula', name: 'USR_REV', width: 80, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_PAGO",
            sortorder: "desc",
            usepager: false,
            title: 'Pagos',
            useRp: false,
            onSuccess: function(){asigevenPagos()},
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 600,
            height: 180,
            params:parametros
        }
    );
    $("#flexyPagos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyPagos").flexOptions({params: parametros});
    $("#flexyPagos").flexReload();
}


function flexyfactaplPago(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyFactPag'});
    parametros.push({name: 'pag', value: pagCol});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyFactPag").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Periodo', name: 'ID_PAGO', width: 52, sortable: true, align: 'center'},
                {display: 'Factura', name: 'FECHA_PAGO', width: 48, sortable: true, align: 'center'},
                {display: 'Total Factura', name: 'REFERENCIA', width: 86, sortable: true, align: 'center'},
                {display: 'Importe Aplicado', name: 'IMPORTE', width: 32, sortable: true, align: 'center'}
            ],

            sortname: "PERIODO",
            sortorder: "desc",
            usepager: false,
            title: 'Pagos aplicados',
            useRp: false,
            rp: 1000,
            page: 1,
            width: 300,
            showTableToggleBtn: false,
            height: 180,
            params:parametros
        }
    );
    $("#flexyFactPag").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyFactPag").flexOptions({params: parametros});
    $("#flexyFactPag").flexReload();
}



function flexyDifapPago(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyDifPag'});
    parametros.push({name: 'pag', value: pagCol});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexyAcuerPag").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Cdf', name: 'ID_PAGO', width: 52, sortable: true, align: 'center'},
                {display: 'Diferido', name: 'FECHA_PAGO', width: 48, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'REFERENCIA', width: 86, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 32, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_PAGO",
            sortorder: "desc",
            usepager: false,
            title: 'Acuerdos aplicados',
            useRp: false,
            rp: 1000,
            page: 1,
            width: 300,
            showTableToggleBtn: false,
            //width: 686,
            height: 180,
            params:parametros
        }
    );
    $("#flexyAcuerPag").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexyAcuerPag").flexOptions({params: parametros});
    $("#flexyAcuerPag").flexReload();
}



function asigevenPagos() {
    tabflexPag = document.getElementById("flexyPagos");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",eventoPagos);
        }

    }
}

function eventoPagos(){
    pagCol=this.getAttribute("id").replace("row","");
    compSession(formpagoPagos);
    compSession(entpagoPagos);
    compSession(flexyfactaplPago);
    compSession(flexyDifapPago);

}

function formpagoPagos(){

    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'tipPago',pag:pagCol },
        success : function(json) {


            for(var x=0;x<json.length;x++)
            {
                if(x==0){
                    $("#lbfrmpagoPag").text(json[x]["DESCRIPCION"]);
                }else{
                    $("#lbfrmpagoPag").text($("#lbfrmpagoPag").text()+','+json[x]["DESCRIPCION"]);
                }
            }

        },
        error : function(xhr, status) {

        }
    });


}


function entpagoPagos(){


    $.ajax
    ({
        url : '../datos/datos.detConsGeneral.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'ubPago',pag:pagCol },
        success : function(json) {

            $("#lbentidadPag").text("");
            $("#lbpuntoPag").text("");
            $("#lbcajaPag").text("");

            for(var x=0;x<json.length;x++)
            {


                if(x==0){

                    $("#lbentidadPag").text(json[x]["DESC_ENTIDAD"]);
                    $("#lbpuntoPag").text(json[x]["DESC_PUNTO"]);
                    $("#lbcajaPag").text(json[x]["DESC_CAJA"]);


                }else{
                    $("#lbentidadPag").text($("#lbentidadPag").text()+','+json[x]["DESC_ENTIDAD"]);
                    $("#lbpuntoPag").text($("#lbpuntoPag").text()+','+json[x]["DESC_PUNTO"]);
                    $("#lbcajaPag").text($("#lbcajaPag").text()+','+json[x]["DESC_CAJA"]);



                }
            }

        },
        error : function(xhr, status) {

        }
    });


}

function flexyCor(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyCor'});
    parametros.push({name: 'inm', value: codCol});
    console.log('entro a la funcion corte');


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexCorte").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Codigo', name: 'ORDEN', width: 50, sortable: true, align: 'center'},
                {display: 'Fecha Planificacion', name: 'FECHA_PAGO', width: 92, sortable: true, align: 'center'},
                {display: 'Fecha Realizacion', name: 'REFERENCIA', width: 87, sortable: true, align: 'center'},
                {display: 'Descripcion', name: 'IMPORTE', width: 250, sortable: true, align: 'center'},
                {display: 'Tipo', name: 'IMPORTE', width: 31, sortable: true, align: 'center'},
                {display: 'Operario', name: 'OPERARIO', width: 90, sortable: true, align: 'center'},
                {display: 'Impo Corte', name: 'impo_corte', width: 90, sortable: true, align: 'center'},
                {display: 'Usu Reversion', name: 'usu_rev', width: 80, sortable: true, align: 'center'},
                {display: 'Fecha Reversion', name: 'FECHA_REVERSION', width: 80, sortable: true, align: 'center'},
                {display: 'Obs Reversion', name: 'observacion', width: 700, sortable: true, align: 'center'}
            ],

            sortname: "ORDEN",
            sortorder: "desc",
            usepager: false,
            title: 'Corte',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            height: 180,
            width: 600,
            params:parametros
        }
    );
    $("#flexCorte").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexCorte").flexOptions({params: parametros});
    $("#flexCorte").flexReload();
}


function flexyRec(){


    var parametros=[];
    parametros.push({name: 'tip', value: 'flexRec'});
    parametros.push({name: 'inm', value: codCol});
    console.log('entro a la funcion reconexion');

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexReconexion").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Fecha Planificacion', name: 'ID_PAGO', width: 96, sortable: true, align: 'center'},
                {display: 'Fecha Realizacion', name: 'FECHA_PAGO', width: 87, sortable: true, align: 'center'},
                {display: 'Tipo', name: 'REFERENCIA', width: 31, sortable: true, align: 'center'},
                {display: 'Observacion', name: 'IMPORTE', width: 69, sortable: true, align: 'center'},
                {display: 'Fecha Acuerdo', name: 'IMPORTE', width: 79, sortable: true, align: 'center'},
                {display: 'Usu Eje', name: 'IMPORTE', width: 50, sortable: true, align: 'center'}

            ],

            sortname: "FECHA_EJE",
            sortorder: "desc",
            usepager: false,
            title: 'Reconexion',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            height: 180,
            width: 600,
            params:parametros
        }
    );
    $("#flexReconexion").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexReconexion").flexOptions({params: parametros});
    $("#flexReconexion").flexReload();
}

function flexyObs(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexObs'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexObs").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Codigo', name: 'ID_PAGO', width: 35, sortable: true, align: 'center'},
                {display: 'Asunto', name: 'FECHA_PAGO', width: 272, sortable: true, align: 'center'},
                {display: 'Descripción', name: 'REFERENCIA', width: 678, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'IMPORTE', width: 61, sortable: true, align: 'center'},
                {display: 'Usuario', name: 'IMPORTE', width: 80, sortable: true, align: 'center'}
            ],

            sortname: "OI.FECHA",
            sortorder: "desc",
            usepager: false,
            title: 'Observaciones',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            //onSuccess: function(){asigevenOtrosRec()},
            width: 1200,
            height: 180,
            params:parametros
        }
    );
    $("#flexObs").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexObs").flexOptions({params: parametros});
    $("#flexObs").flexReload();
}


function flexyDeudaCero(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexDeudCero'});
    parametros.push({name: 'inm', value: codCol});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexDeudaCero").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:15,  align: 'center'},
                {display: 'C\xF3digo <br/> Deuda Cero', name: 'CODIGO', width: 60, sortable: true, align: 'center'},
                {display: 'Per\xEDodo <br/> Inicial', name: 'PER_INI', width: 60, sortable: true, align: 'center'},
                {display: 'N\xFAmero  <br/> Cuotas', name: 'NUM_CUOTAS', width: 40, sortable: true, align: 'center'},
                {display: 'Fecha <br/> \xDAltimo Pago', name: 'FECHA_ULTPAGO', width: 76, sortable: true, align: 'center'},
                {display: 'Activo', name: 'ACTIVO', width: 30, sortable: true, align: 'center'},
                {display: 'Cuotas <br/> Pagadas', name: 'CUOTAS_PAGADAS', width: 45, sortable: true, align: 'center'},
                {display: 'Total <br/> Diferido ', name: 'TOT_DIF', width: 48, sortable: true, align: 'center'},
                {display: 'Total <br/> Mora ', name: 'TOT_MORA', width: 48, sortable: true, align: 'center'},
                {display: 'Cliente <br/> Acuerdo', name: 'CLIENTE', width: 200, sortable: true, align: 'center'},
                {display: 'Per\xEDodo <br/> Reversi\xF3n', name: 'PERREV', width: 60, sortable: true, align: 'center'}
            ],
            sortname: "PERIODO_INI",
            sortorder: "desc",
            usepager: false,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 1200,
            height: 171,
            params:parametros
        }
    );
    $("#flexDeudaCero").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexDeudaCero").flexOptions({params: parametros});
    $("#flexDeudaCero").flexReload();
}



function flexySaldFav(){

    var parametros=[];
    parametros.push({name: 'tip', value: 'flexiSaldos'});
    parametros.push({name: 'inm', value: codCol});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexiSaldos").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'RNUM', width:25,  sortable: true, align: 'center'},
                {display: 'C\xF3digo', name: 'CODIGO', width: 70, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA', width: 120, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 75, sortable: true, align: 'center'},
                {display: 'Aplicado', name: 'VALOR_APLICADO', width: 75, sortable: true, align: 'center'},
                {display: 'Por Aplicar', name: 'PENDIENTE', width: 75, sortable: true, align: 'center'},
                {display: 'Motivo', name: 'MOTIVO', width: 400, sortable: true, align: 'center'}
            ],
            sortname: "CODIGO",
            sortorder: "DESC",
            usepager: false,
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            width: 900,
            height: 171,
            params:parametros,
            onSuccess: function(){asigevenSaldos()}
        }
    );
    $("#flexiSaldos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexiSaldos").flexOptions({params: parametros});
    $("#flexiSaldos").flexReload();
}

function asigevenSaldos() {
    tabflexPag = document.getElementById("flexiSaldos");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",eventoSaldos);
        }

    }
}

function eventoSaldos(){
    salCol=this.getAttribute("id").replace("row","");

    compSession(flexiAplSaldos);

}

function flexiAplSaldos(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyAplSaldos'});
    parametros.push({name: 'sal', value: salCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexiAplSaldos").flexigrid	(
        {
            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'Periodo', name: 'rnum', width:80,  align: 'center'},
                {display: 'Factura', name: 'CODIGO', width: 80, sortable: true, align: 'center'},
                {display: 'valor', name: 'FECHADIG', width: 85, sortable: true, align: 'center'}

            ],

            sortname: "F.PERIODO",
            sortorder: "desc",
            usepager: false,
            title: 'Facturas aplicadas',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            onSuccess: function(){asigevenOtrosRec()},
            width: 300,
            height: 180,
            params:parametros
        }
    );
    $("#flexiAplSaldos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexiAplSaldos").flexOptions({params: parametros});
    $("#flexiAplSaldos").flexReload();
}
function flexiReclamos(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexyReclamos'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexiReclamos").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:25,  align: 'center'},
                {display: 'Código PQR', name: 'CODIGO_PQR', width: 70, sortable: true, align: 'center'},
                {display: 'Fecha PQR', name: 'FECHA_PQR', width: 120, sortable: true, align: 'center'},
                {display: 'Tipo PQR', name: 'DESC_TIPO_RECLAMO', width: 100, sortable: true, align: 'center'},
                {display: 'Motivo PQR', name: 'DESC_MOTIVO_REC', width: 400, sortable: true, align: 'center'},
                {display: 'Estado', name: 'CERRADO', width: 60, sortable: true, align: 'center'},
                {display: 'Diagnostico', name: 'DIAGNOSTICO', width: 80, sortable: true, align: 'center'},
                {display: 'PDF', name: 'foto', width: 40, sortable: true, align: 'center'}
            ],


            sortname: "CODIGO_PQR",
            sortorder: "DESC",

        title: 'Historico de PQRS',

            rp: 1000,
            page: 1,

            width: 1200,
            height: 300,
            params:parametros
        }
    );
    $("#flexiReclamos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexiReclamos").flexOptions({params: parametros});
    $("#flexiReclamos").flexReload();
}


function flexiDocumentos(){
    var parametros=[];
    parametros.push({name: 'tip', value: 'flexiDocumentos'});
    parametros.push({name: 'inm', value: codCol});

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexiDocumentos").flexigrid	(
        {

            url: './../datos/datos.detConsGeneral.php',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:25,  align: 'center'},
                {display: 'Registro', name: 'ID_REGISTRO', width: 70, sortable: true, align: 'center'},
                {display: 'Codigo Archivo', name: 'CODIGO_ARCH', width: 120, sortable: true, align: 'center'},
                {display: 'Area', name: 'AREA', width: 130, sortable: true, align: 'center'},
                {display: 'Tipo Doc', name: 'TIPDOC', width: 200, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA', width: 70, sortable: true, align: 'center'},
                {display: 'Observación', name: 'OBSERVACION', width: 200, sortable: true, align: 'center'},
                {display: 'PDF', name: 'RUTA', width: 40, sortable: true, align: 'center'}
            ],


            sortname: "ID_REGISTRO",
            sortorder: "DESC",

            title: 'Historico de Documentos',

            rp: 1000,
            page: 1,

            width: 1200,
            height: 300,
            params:parametros
        }
    );
    $("#flexiDocumentos").flexOptions({url: './../datos/datos.detConsGeneral.php'});
    $("#flexiDocumentos").flexOptions({params: parametros});
    $("#flexiDocumentos").flexReload();
}



function reclamoPdf(id) { // Traer la fila seleccionada
    popup("../../facturacion/vistas/vista.documento_pqr.php?codigo_pqr="+id,1100,800,'yes','pop2');
}

/*function documentoPdf(id) { // Traer la fila seleccionada
    popup("../../archivo",1100,800,'yes','pop2');
}*/

function NotaCreditoPDF(factura) {
    popup("../../facturacion/reportes/reporte.nota_credito.php?factura="+factura,1100,800,'yes','pop2');
}

function EnvioEmail(factura, inmueble) {
    popup("../../facturacion/clases/enviafac.php?factura="+factura+"&inmueble="+inmueble, 700,450, 'yes','pop2');
}


