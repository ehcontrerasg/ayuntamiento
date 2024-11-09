/*************************************
 *
 *	@Author : Jesus Gutierrez
 *	@Fecha  : 01/11/2019
 *
 *************************************/

$(document).ready(function(){
    desContr();
    compSession();
    table = $('#dataTable').DataTable({
        "processing": "true",
        "ajax" : {
            "method" : "POST",
            "url" : "../datos/datos.consulta_doc_arc_contable.php?20",
        },
        "columns":[
            {"data": "id"},
            {"data": "DESC_TIPO_INGRESO"},
            {"data": "DESC_DOCUMENTO"},
            {"data": "CODIGO_DOCUMENTO"},
            {"data": "COD_SEGMENTO"},
            {"data": "FECHA_CARGUE"},
            {"data": "USUARIO_CARGUE"},
            {"data": "BOTONES"},
        ]
    });

   // obtener_data_editar("#dataTable tbody", table);
    listarPDF("#dataTable tbody", table);
    info("#dataTable tbody", table);

    $("#modDocArcForm").submit(function(e){
        $('#myModal').modal('hide');
        e.preventDefault();
        //ModDocArc();
    });
});

/*function ModDocArc(){
    oData = new FormData(document.forms.namedItem("modDocArcForm"));
    oData.append("tip", "ModDocArc");
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
                url: '../datos/datos.modifica_doc_arc.php', //URL destino
                data: oData,
                processData: false, //Evitamos que JQuery procese los datos, daría error
                contentType: false, //No especificamos ningún tipo de dato
                cache: false,
                type: 'POST',
                success : function(json) {
                    //document.write(json);
                    //console.log(json);
                    //cargarArchivo();
                    if (json=="true"){
                        // $('#ingDocArcForm')[0].reset();
                        swal({
                                title: "Mensaje",
                                text: "Has ingresado el archivo correctamente.",
                                type: "success"},
                            function(isConfirm){
                                if (isConfirm) {
                                    $("#ingCodDoc").focus();
                                }
                            }
                        );
                    }else{
                        swal("Mensaje!", json, "error");
                        $(this).focus();
                    }
                    table.ajax.reload();
                }
            });
        }else{
            console.log('si');
            swal("Cancelled", "El archivo no fue guardado :(", "error");
            $(this).focus();
        }
    });

}
*/

/*
var obtener_data_editar = function(tbody, table) {
    $(tbody).on("click","button.editar", function(){
        var data = table.row($(this).parents("tr") ).data();
        //console.log(data.FECHA_DOCUMENTO);
        var IdRegistro = $("#IdRegistro").val(data.ID_REGISTRO);
        var	ngCodDoc = $("#ingCodDoc").val(data.CODIGO_INM);
        var	codArc = $("#codArc").val(data.CODIGO_ARCH);
        var	departamento = $("#departamento").append('<option selected>'+data.DESC_AREA+'</option>');
        //var	documento = $("#documento").append("<option selected>"+data.DESC_DOCUMENTO.toString()+'</option>');
        //var	proyecto = $("#proyecto").val(data.ID_PROYECTO);
        //fechaDoc = $("#fechaDoc").append(Date.parse(data.FECHA_DOCUMENTO));
        //var	observacion = $("#observacion").val(data.OBSERVACION);
        //archivo_fls = $("#archivo_fls").val(data.RUTA_ARCHIVO);

    });
}
*/

function verPDF (ruta){
    //if (caso==1) {
        window.open('../'+ruta, "Vista de registro");
    //}
}


var info = function(tbody, table){
    $(tbody).on("click","button.info", function(){
        var datos = table.row($(this).parents("tr") ).data();
        var IdRegistro = (datos.CODIGO_DOCUMENTO);
        //console.log(IdRegistro);
        buscar(IdRegistro);
        buscarComunicacion(IdRegistro);
        buscarEntradas(IdRegistro);
        buscarFacturas(IdRegistro);
        buscarNotas(IdRegistro);
        buscarPagos(IdRegistro);
    });
}


function buscar(id){
    var datos = 'IdRegistro='+id+'&tip=IdRegistroCont';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            //document.write(resp);
            //console.log('hola mundo');
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_info_cheque ('#dataTableInfoCheque tbody', jresp);
        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}

function buscarComunicacion(id){
    var datos = 'IdRegistro='+id+'&tip=IdComunicacion';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_info_comunicacion('#dataTableInfoComunicaciones tbody', jresp);
        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}

function buscarEntradas(id){
    var datos = 'IdRegistro='+id+'&tip=IdEntradas';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_info_entradas('#dataTableInfoEntradas tbody', jresp);
        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}

function buscarFacturas(id){
    var datos = 'IdRegistro='+id+'&tip=IdFacturas';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_info_facturas('#dataTableInfoFacturas tbody', jresp);

        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}

function buscarNotas(id){
    var datos = 'IdRegistro='+id+'&tip=IdNotas';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_info_notas('#dataTableInfoNotas tbody', jresp);

        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}


function buscarPagos(id){
    var datos = 'IdRegistro='+id+'&tip=IdPagos';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_info_pagos('#dataTableInfoPagos tbody', jresp);

        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}

var listarPDF = function(tbody, table){
    $(tbody).on("click","button.ver", function(){
        var data = table.row($(this).parents("tr") ).data();
        var codigo_doc = (data.CODIGO_DOCUMENTO);
        buscarPDFCont(codigo_doc);
    });
}

function buscarPDFCont(id)
{
    var datos = 'codigo_doc='+id.trim()+'&tip=codigo_doc';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            dibujar_tbl_listaPDF('#dataTablePDF tbody', jresp);
        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}


function dibujar_tbl_listaPDF(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';

    for (var i = 0; i < row; i++) {
        var ruta_fls = json['RUTA_DOCUMENTO'][i];
        var ruta = '/'+ruta_fls;

        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['DESC_TIPO_INGRESO'][i]+'</td>'+
            '<td>'+json['DESC_DOCUMENTO'][i]+'</td>'+
            '<td><button class="btn btn-default" onclick="verPDF('+" '"+ruta+"'"+')"  ><span class="glyphicon glyphicon-eye-close"></span></button>'+
            '</button></td>'+
            '</tr>';
    }
    $(id).html(html);
}

function dibujar_tbl_info_cheque(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['FECHA_EMISION'][i]+'</td>'+
            '<td>'+json['BENEFICIARIO'][i]+'</td>'+
            '<td>'+json['BANCO'][i]+'</td>'+
            '<td>'+json['EMPRESA'][i]+'</td>'+
            '</tr>';
    }
    $(id).html(html);
}

function dibujar_tbl_info_comunicacion(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['FECHA_EMISION'][i]+'</td>'+
            '<td>'+json['BENEFICIARIO'][i]+'</td>'+
            '<td>'+json['EMPRESA'][i]+'</td>'+
            '<td>'+json['FECHA_RECEPCION'][i]+'</td>'+
            '<td>'+json['ASUNTO'][i]+'</td>'+
            '</tr>';
    }
    $(id).html(html);
}

function dibujar_tbl_info_entradas(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['PERIODO'][i]+'</td>'+
            '</tr>';

    }
    $(id).html(html);
}

function dibujar_tbl_info_facturas(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['FECHA_EMISION'][i]+'</td>'+
            '<td>'+json['BENEFICIARIO'][i]+'</td>'+
            '<td>'+json['EMPRESA'][i]+'</td>'+
            '</tr>';

    }
    $(id).html(html);
}

function dibujar_tbl_info_notas(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['FECHA_EMISION'][i]+'</td>'+
            '<td>'+json['EMPRESA'][i]+'</td>'+
            '<td>'+json['CODIGO_FACTURA'][i]+'</td>'+
            '</tr>';

    }
    $(id).html(html);
}

function dibujar_tbl_info_pagos(id, json) {
    var row = json['CODIGO_DOCUMENTO'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
            '<td>'+json['CODIGO_DOCUMENTO'][i]+'</td>'+
            '<td>'+json['FECHA_EMISION'][i]+'</td>'+
            '<td>'+json['BENEFICIARIO'][i]+'</td>'+
            '<td>'+json['BANCO'][i]+'</td>'+
            '<td>'+json['EMPRESA'][i]+'</td>'+
            '<td>'+json['CUENTA'][i]+'</td>'+
            '<td>'+json['NUM_APROBACION'][i]+'</td>'+
            '</tr>';

    }
    $(id).html(html);
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