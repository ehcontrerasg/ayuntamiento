//DECLARACIONES
var cardnumber                = document.getElementById("txtNumeroTarjeta");
var cvv                       = document.getElementById("txtCVV");
var txtCodigoInmueble         = document.getElementById("txtCodigoInmueble");
var fecha_expiracion          = document.getElementById("txtFechaExpiracion");
var txtCorreoElectronico      = document.getElementById("txtCorreoElectronico");
var txtTelefono               = document.getElementById("txtTelefono");
var txtCelular                = document.getElementById("txtCelular");
var txtNombreCliente          = document.getElementById("txtNombreCliente");
var txtCedula                 = document.getElementById("txtCedula");
var txtCodigoInmuebleBuscar   = document.getElementById("txtCodigoInmuebleBuscar");
var form                      = $("#frmRegistroTarjeta");
var dataTable                 = $("#dataTable");
var btnGuardar                = $("#btnGuardar");
var btnLimpiar                = $("#btnLimpiar");
var frmAnularPago             = $("#frmAnularPago");
var btnBuscarDatosTarjeta     = $("#btnBuscarDatosTarjeta");
var idCargoUsuario            = 0;

//INICIACION DE LAS LIBRERIAS
new Cleave(fecha_expiracion, {
    date: true,
    datePattern: ['m', 'y']
});



var cardnumber_mask         = new IMask(cardnumber, {
    mask: [
        {
            mask: '0000 000000 00000',
            regex: '^3[47]\\d{0,13}',
            cardtype: 'american express'
        },
        {
            mask: '0000 0000 0000 0000',
            regex: '^(?:6011|65\\d{0,2}|64[4-9]\\d?)\\d{0,12}',
            cardtype: 'discover'
        },
        {
            mask: '0000 000000 0000',
            regex: '^3(?:0([0-5]|9)|[689]\\d?)\\d{0,11}',
            cardtype: 'diners'
        },
        {
            mask: '0000 0000 0000 0000',
            regex: '^(5[1-5]\\d{0,2}|22[2-9]\\d{0,1}|2[3-7]\\d{0,2})\\d{0,12}',
            cardtype: 'mastercard'
        },
        {
            mask: '0000 000000 00000',
            regex: '^(?:2131|1800)\\d{0,11}',
            cardtype: 'jcb15'
        },
        {
            mask: '0000 0000 0000 0000',
            regex: '^(?:35\\d{0,2})\\d{0,12}',
            cardtype: 'jcb'
        },
        {
            mask: '0000 0000 0000 0000',
            regex: '^(?:5[0678]\\d{0,2}|6304|67\\d{0,2})\\d{0,12}',
            cardtype: 'maestro'
        },
        {
            mask: '0000 0000 0000 0000',
            regex: '^4\\d{0,15}',
            cardtype: 'visa'
        },
        {
            mask: '0000 0000 0000 0000',
            regex: '^62\\d{0,14}',
            cardtype: 'unionpay'
        },
        {
            mask: '0000 0000 0000 0000',
            cardtype: 'desconocido'
        }
    ],
    dispatch: function (appended, dynamicMasked) {
        var number = (dynamicMasked.value + appended).replace(/\D/g, '');

        for (var i = 0; i < dynamicMasked.compiledMasks.length; i++) {
            var re = new RegExp(dynamicMasked.compiledMasks[i].regex);
            if (number.match(re) != null) {
                return dynamicMasked.compiledMasks[i];
            }
        }
    }
});

//ACCIONES
compSession(cargarBancoEmisor());
compSession(cargoUsuario());

new IMask(txtCodigoInmuebleBuscar, {mask: '0000000'});

txtCodigoInmueble.addEventListener("blur",function(campo){
    var codigo_inmueble = campo.target.value;
    getDatosCliente(codigo_inmueble)
});

btnGuardar.on("click",function(){
    var cv_cvv              = cvv.checkValidity();
    var cv_cardnumber       = cardnumber.checkValidity();
    var cv_inmueble         = txtCodigoInmueble.checkValidity();
    var cv_fecha_expiracion = fecha_expiracion.checkValidity();
    var cv_telefono         = txtTelefono.checkValidity();
    var cv_celular          = txtCelular.checkValidity();
    var cv_correo           = txtCorreoElectronico.checkValidity();

    if(cv_cvv === true && cv_cardnumber === true && cv_inmueble === true && cv_fecha_expiracion === true &&
       cv_telefono === true && cv_celular === true && cv_correo === true){

        var valor_fecha_expiracion = fecha_expiracion.value;
        var fecha_valida = verificacionFechaExpiracion(valor_fecha_expiracion);

        if(fecha_valida){
           compSession(insertarTarjeta());
        }else{
            swal("Error!", "Fecha de tarjeta inválida.", "error");
        }
    }
});

btnLimpiar.on("click",function(){
    limpiarFormulario(form);
})

dataTable.on("click",".btnVerFormulario",function(){
    var row         =  $(this).closest('tr');
    var data        =  $("#dataTable").DataTable().row(row).data();
    var inmueble    =  data[1];
    compSession(visualizaFormularioRegistroTarjeta(inmueble));

});

dataTable.on("click",".btnEliminar",function() {
     var row         =  $(this).closest('tr');
     var data        =  $("#dataTable").DataTable().row(row).data();
     var inmueble    = data[1];
     var id_registro = data[0];

     $("#idRegistro").val(id_registro);
     $("#codigoInmueble").val(inmueble);
     $("#txtCodigoInmuebleAnulacion").val(inmueble);
     $("#txtMotivoAnulacion").val('');
     $("#modalAnular").modal('show');
});

frmAnularPago.on('submit',function(){

    var idRegistro       = $("#idRegistro").val();
    var motivo_anulacion = $("#txtMotivoAnulacion").val();
    var codigo_inmueble  = $("#codigoInmueble").val();
    compSession(eliminarTarjeta(idRegistro,motivo_anulacion,codigo_inmueble));
});

btnBuscarDatosTarjeta.on("click",function(){

    var elementosOcultos = columnasYBotonesOcultos(idCargoUsuario);
    var codigoInmuebleBuscar = txtCodigoInmuebleBuscar.value;
    cargarDatatable(elementosOcultos,codigoInmuebleBuscar);
});

//FUNCIONES
function insertarTarjeta(){

    var datos        = form.serializeArray();
    var tipo_tarjeta = determinarTipoTarjeta(cardnumber_mask);

    datos.push({name:"tip",value:"insertarTarjeta"});
    datos.push({name:"tipo_tarjeta",value:tipo_tarjeta});

    if( tipo_tarjeta == "mastercard" || tipo_tarjeta == "visa"){
        $.ajax({
            type:"POST",
            url:"../../recaudo/datos/datos.domiciliacion.php",
            data: datos,
            success:function(res){
                var res = JSON.parse(res);
                if(res.coderror == 0){
                    swal("Éxito!", res.msgerror, "success");
                    getTarjetasRegistradas();
                    limpiarFormulario(form);
                    var codigo_inmueble = datos[0].value;
                    generarFormulario(codigo_inmueble);
                }else{
                    swal("Error!", res.msgerror, "error");
                }
            },
            error:function(xhrjs, error){
                alert(xhrjs+" "+error)
            }
        });
    }else{
        swal("Error!", "Número de tarjeta no válida. Verifique que la tarjeta sea VISA o MASTERCARD.", "error");
    }

}
//Obtiene el cargo de usuario logueado.
function cargoUsuario(){
    $.ajax({
        type:"POST",
        url: "../../recaudo/datos/datos.domiciliacion.php",
        data:{tip:"verificarCargo"},
        success:function(res){
            idCargoUsuario = JSON.parse(res);
        },
        error:function(xhrjs,error){
            alert(error)
        }

    });
}

function getTarjetasRegistradas(){

    $.ajax({
        type:"POST",
        url: "../../recaudo/datos/datos.domiciliacion.php",
        data:{tip:"verificarCargo"},
        success:function(res){
            var cargo = JSON.parse(res);
            var elementosOcultos = columnasYBotonesOcultos(cargo);
            cargarDatatable(elementosOcultos);
        },
        error:function(xhrjs,error){
            alert(error)
        }

    });

}

/*function getTarjetasRegistradas(){

    var cargos_permitidos = [
                                {id_cargo:9},
                                {id_cargo:110},
                                {id_cargo:111},
                                {id_cargo:201},
                               // {id_cargo:301}
                            ];
    $.ajax({
                type:"POST",
                url: "../../recaudo/datos/datos.domiciliacion.php",
                data:{tip:"verificarCargo"},
                success:function(res){

                    cargos_permitidos.forEach(function(elemento){
                        var cargo = JSON.parse(res);
                        if(elemento.id_cargo == cargo){
                            cargarDatatable();
                        }

                    })
                },
                error:function(xhrjs,error){
                    alert(error)
                }

    });

}*/

function cargarDatatable(elementosOcultos,codigoInmuebleBuscar){
    $.ajax({
        type:"POST",
        url: "../../recaudo/datos/datos.domiciliacion.php",
        data: {tip:"getTarjetas",codigo_inmueble:codigoInmuebleBuscar},
        async:true,
        success:function(res){
            if ( $.fn.dataTable.isDataTable('#dataTable') ) {
                $('#dataTable').DataTable().destroy();
            }
            $('#dataTable').DataTable( {
                data: JSON.parse(res),
                columns: [
                    { title: "ID"},
                    { title: "INMUEBLE"},
                    { title: "NOMBRE DEL CLIENTE"},
                    { title: "NUMERO DE TARJETA"},
                    { title: "FECHA DE EXPIRACION"},
                    { title: "TIPO DE TARJETA"},
                    { title: "BANCO EMISOR"},
                    { title: "ACCIONES"}
                ],
                columnDefs: [
                    {
                        "targets": elementosOcultos,
                        "visible": false,
                        "searchable": false
                    },
                    { "width": "1px", "targets": 4 }
                ],
                "info":     false,
                "order": [[ 2, "desc" ]],
                "paging" : false,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'},
                "scrollY":        "700px",
                "scrollCollapse": true,
                "searching": false

            });

            $('#dataTable').show();
        }
    });

}

function limpiarFormulario(form){

    for(var indice = 0; indice<form[0].length;indice++){
        var control = $("#"+form[0][indice].id);
        if(control.prop("type") == "text" || control.prop("type") == "email"){
            control.val("");
        }
    }
}
function determinarTipoTarjeta(mascara){
        return mascara.masked.currentMask.cardtype;
}
function eliminarTarjeta(id_registro,motivo, codigo_inmueble){

    var datos = {tip:"eliminarTarjeta", id_registro:id_registro,motivo:motivo,codigo_inmueble:codigo_inmueble};
    $.ajax({
        type:"POST",
        url:"../../recaudo/datos/datos.domiciliacion.php",
        data: datos,
        success:function(res){
            var res = JSON.parse(res);
            if(res.coderror == 0){
                $("#idRegistro").val('');
                $('#modalAnular').modal('toggle');
                swal("Éxito!", res.msgerror, "success");
                getTarjetasRegistradas();
                //generarFormularioAnulacionServicio(codigo_inmueble);
            }else{
                swal("Error!", res.msgerror, "error");
            }
        },
        error:function(xhrjs, error){
            alert(xhrjs+" "+error)
        }
    });
}
function getDatosTarjeta(id_registro){

    $.ajax(
            {
                type:"POST",
                url: "../datos/datos.domiciliacion.php",
                data: {tip:"getDatosTarjeta",id_registro:id_registro},
                success:function(res){
                   var data = JSON.parse(res);
                   llenarCampos(data);
                },
                error:function (xhrjs,error) {
                    alert(xhrjs+" "+error);
                }
            }
          );
}
function cargarBancoEmisor(){
   var select_banco_emisor =  $("#txtBancoEmisor");
    $.ajax(
            {
                type:"POST",
                url:"../../recaudo/datos/datos.domiciliacion.php",
                data:{tip:"getBancoEmisor"},
                success: function(res){
                    res = JSON.parse(res);
                    for(var indice = 0; indice < res.length; indice++){
                        var option = new Option(res[indice][1],res[indice][0]);
                        select_banco_emisor.append(option);
                    }
                },
                error:function(jqxhr, error){
                    alert(jqxhr+" "+error);
                }
            }
          );
}
function generarFormulario(codigo_inmueble){

    //var datos = ;
    $.ajax({
            type:"POST",
            url: "../../recaudo/datos/datos.formularioRegistroTarjetas.php",
            data:{codigo_inmueble:codigo_inmueble},
            success:function(res){

                $("#formularioPDF").empty();
                $("#formularioPDF").append(res);

              //  imprimirPDF("formularioPDF");

            },
            error:function(xhrjs, error){
                alert(xhrjs+" "+error);
            }
    });
}

function generarFormularioAnulacionServicio(codigo_inmueble){

    //var datos = ;
    $.ajax({
        type:"POST",
        url: "../../recaudo/datos/datos.formularioAnulacionPagoRecurrente.php",
        data:{codigo_inmueble:codigo_inmueble},
        success:function(res){

            //$("#formularioPDF").empty();
            $("#formularioPDF").append(res);

            //  imprimirPDF("formularioPDF");

        },
        error:function(xhrjs, error){
            alert(xhrjs+" "+error);
        }
        //data: {};
    });
}

function getDatosCliente(codigo_inmueble){
    $.ajax({
            type:"POST",
            url: "../../recaudo/datos/datos.domiciliacion.php",
            data: {tip:'getDatosCliente',codigo_inmueble:codigo_inmueble},
            success:function(res){

                if(res != "false"){
                    txtCodigoInmueble.removeAttribute("style");

                    var datos_cliente = JSON.parse(res);
                    txtCorreoElectronico.value = datos_cliente.EMAIL;
                    txtNombreCliente.value     = datos_cliente.NOMBRE_CLIENTE;
                    txtTelefono.value          = datos_cliente.TELEFONO;
                    txtCelular.value           = datos_cliente.CELULAR;

                    compSession(aplicarMascaras());

                }else if(res == "false" && txtCodigoInmueble.value !=''){
                    swal("Error!", "Este inmueble no tiene contrato con nosotros.", "error");
                    txtCodigoInmueble.setAttribute("style",["border: 2px solid #e64242"]);
                    txtCorreoElectronico.value = "";
                    txtTelefono.value          = "";
                    txtCelular.value           = "";
                    compSession(aplicarMascaras());
                }else{
                    txtCodigoInmueble.setAttribute("style",["border: 2px solid #e64242"]);
                    txtCorreoElectronico.value = "";
                    txtTelefono.value          = "";
                    txtCelular.value           = "";
                    compSession(aplicarMascaras());
                }
            },
            error:function(xhrjs, error){
                alert(xhrjs+" "+error);
            }
    });
}
function visualizaFormularioRegistroTarjeta(codigo_inmueble){

    var ruta = '../../archivo/pdf/SD/'+codigo_inmueble+'/';

    $.ajax({
                type:"POST",
                url:"../../recaudo/datos/datos.domiciliacion.php",
                data:{tip:"verificarDirectorio",ruta:ruta},
                success:function(res){

                    var respuesta = JSON.parse(res);
                    
                    if(respuesta != ""){
                        ruta += respuesta;
                        window.open(ruta,"_blank");
                    }else{
                        generarFormulario(codigo_inmueble);
                        //swal("Error!", "Este inmueble no tiene formulario digitalizado.", "error");
                    }

                },
                error:function(xhrjq, error){
                    alert(error);
                }
            });

}
function compSession(callback) {
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
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();
                        }
                    });
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
function verificacionFechaExpiracion(expire) {

    var d = new Date();
    var currentYear = d.getFullYear();
    var currentMonth = d.getMonth() + 1;
    // get parts of the expiration date
    var parts = expire.split('/');
    var year = parseInt(parts[1], 10) + 2000;
    var month = parseInt(parts[0], 10);
    // compare the dates
    if (year < currentYear || (year == currentYear && month <= currentMonth || (year-currentYear)>=10)) {
        return  false;
    }
    return true
}

function aplicarMascaras(){

    var mascaraCvv      = new IMask(cvv, {mask: '000'});

    var mascaraInmueble = new IMask(txtCodigoInmueble, {mask: '0000000'});
    //var mascaraTelefono = new IMask(txtTelefono, {mask: '000-000-0000'});
    //var mascaraCelular  = new IMask(txtCelular, {mask: '000-000-0000'});
}

function columnasYBotonesOcultos(idCargo){

    var arregloColumnasOcultas = [0,1,2,3,4,5,6]; //La columna '0' siempre estará oculta.
    var cargosPermitidos = [
        {idCargo:   9, columnasOcultas:[0]},
        {idCargo: 110, columnasOcultas:[0]},
        {idCargo: 111, columnasOcultas:[0]},
        {idCargo: 201, columnasOcultas:[0]},
        {idCargo: 200, columnasOcultas:[0]},
        {idCargo: 300, columnasOcultas:[0,3,4,5,6]},
        {idCargo: 301, columnasOcultas:[0,3,4,5,6]}
    ];



    cargosPermitidos.forEach(function(cargo,indice){

        if(cargo.idCargo == idCargo){
            arregloColumnasOcultas = cargosPermitidos[indice].columnasOcultas;
        }
    });

    return arregloColumnasOcultas;

}