var idcontrato;
var inmueble;
var acueducto;
var estado;
var proceso;
var catastro;
var sector;
var ruta;
var direccion;
var urbanizacion;
var uso;
var unidades;
var agua;
var alcantarillado;
var tipo;
var tarifa;
var derecho;
var fianza;
var total;
var documento;
var cliente;
var alias;
var telefono;
var email;
var TipoDoc;
var cupo;
var oficina;
var cuotas;
var conTar;
var medidor;

$(document).ready(function(){
    desContr();
    compSession();
    compSession(Inicio);
    $("#ingNueConCodSis").blur(
        function(){

            if($(this).val()=='' ){
                $('#regNueConForm')[0].reset();
                $(this).focus();
            }else{
                compSession(completaDatos);
            }
        }
    );



    $("#ingNueConDoc").blur(
        function(){
            if($(this).val()=='' && $("#ingNueConCodSis").val()!= '' ){
                //$('#regNueConForm')[0].reset();
                //$(this).focus();
            }else{
                urbanizacion = $("#ingNueConUrb").val();
                medidor = $("#ingNueCanMed").val();
                acueducto = $("#ingNueConAcu").val();
                compSession(completaCliente);
                compSession(completaTarifas(urbanizacion, medidor, acueducto));
            }
        }
    );

    $("#regNueConForm").submit(
        function(){
            compSession(generaImp);
        }
    );
});


function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu", function(e){return false;});

}

function completaDatos(){
    $.ajax
    ({
        url : '../datos/datos.nuevo_contrato.php',
        type : 'POST',
        data : { tip : 'obtDat', inm : $("#ingNueConCodSis").val() },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConAcu").val(json[0]['ID_PROYECTO']);
                $("#ingNueConSec").val(json[0]['ID_SECTOR']);
                $("#ingNueConRut").val(json[0]['ID_RUTA']);
                $("#ingNueConPro").val(json[0]['ID_PROCESO']);
                $("#ingNueConCat").val(json[0]['CATASTRO']);
                $("#ingNueConDir").val(json[0]['DIRECCION']);
                $("#ingNueConUrb").val(json[0]['DESC_URBANIZACION']);
                $("#ingNueConUso").val(json[0]['ID_USO']);
                $("#ingNueConSum").val(json[0]['TIPO']);
                $("#ingNueConUni").val(json[0]['UNIDADES_HAB']);
                $("#ingNueConEst").val(json[0]['ID_ESTADO']);
                $("#ingNueConDig").val(json[0]['DIGITO_PROCESO']);
                $("#ingNueCanCon").val(json[0]['CANT_CONTRATO']);
                $("#ingNueConSeq").val(json[0]['SECUENCIA']);
                $("#ingNueCanMed").val(json[0]['CANT_MEDIDOR']);

                if($("#ingNueConUso").val() != 'R' /*&& $("#ingNueConUso").val() != 'C'*/ ){
                    swal({
                            title: "Mensaje",
                            text: "El Inmueble no es de uso residencial.<br> No se puede crear el contrato",
                            type: "warning",
                            html: true,
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $("#regNueConForm")[0].reset();
                            }
                        });
                }
                if($("#ingNueConDig").val() != '00' && ($("#ingNueConEst").val() != 'CC' && $("#ingNueConEst").val() != 'CK' && $("#ingNueConEst").val() != 'CT')){
                    swal({
                            title: "Error",
                            text: "El Inmueble tiene un contrato activo. <br> Antes de continuar debe cancelar el contrato actual. <br> Contacte a catastro.",
                            type: "error",
                            html: true,
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $("#regNueConForm")[0].reset();
                            }
                        });
                }
            }
            else{
                swal({
                        title: "Mensaje",
                        text: "No se encuentra registrado el Inmueble. <br> Por favor verifique los datos o consulte con el area de catastro.",
                        type: "info",
                        html: true,
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $("#ingNueConCodSis").val('');
                            $("#ingNueConCodSis").focus();
                        }
                    });
            }
        },
        error : function(xhr, status) {

            return false;
        }
    });
}


function completaTarifas(urbanizacion, medidor, acueducto){
    $.ajax
    ({
        url : '../datos/datos.nuevo_contrato.php',
        type : 'POST',
        data : { tip : 'obtTar', inm : $("#ingNueConCodSis").val(), urb : urbanizacion, med : medidor, acu : acueducto},
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConDerInc").val(json[0]['DI']);
                $("#ingNueConFia").val(json[0]['FIANZA']);
                $("#ingNueConTar").val(json[0]['TARIFA']);
                $("#ingNueConAgu").val(json[0]['TARIFA_AGUA']);
                $("#ingNueConCup").val(json[0]['CUPO']);
                $("#ingNueConTotCon").val(json[0]['TOTAL']);
                $("#ingNueConTarCon").val(json[0]['CONSEC_TARIFA']);

            }
            else{
                swal({
                        title: "Mensaje",
                        text: "No se encuentra la tarifa para el inmueble. <br> Por favor contacte a catastro",
                        type: "info",
                        html: true,
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $("#ingNueConCodSis").val('');
                            $("#ingNueConCodSis").focus();
                        }
                    });

            }
        },
        error : function(xhr, status) {
            return false;
        }
    });
}

function completaCliente(){
    $.ajax
    ({
        url : '../datos/datos.nuevo_contrato.php',
        type : 'POST',
        data : { tip : 'obtCli', cli : $("#ingNueConDoc").val() },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConDoc").val(json[0]['DOCUMENTO']);
                $("#ingNueConNom").val(json[0]['NOMBRE_CLI']);
                $("#ingNueConTel").val(json[0]['TELEFONO']);
                $("#ingNueConCli").val(json[0]['CODIGO_CLI']);
                $("#ingNueConEma").val(json[0]['EMAIL']);
                $("#ingNueConTipDoc").val(json[0]['TIPO_DOC']);
            }
            else{
                swal({
                        title: "Mensaje",
                        text: "No se encuentra registrado el cliente. <br> Por favor ingrese los datos en las casillas",
                        type: "info",
                        html: true,
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $("#ingNueConNom").val('');
                            $("#ingNueConTel").val('');
                            $("#ingNueConCli").val('');
                            $("#ingNueConNom").removeAttr("readonly");
                            $("#ingNueConTel").removeAttr("readonly");
                            $("#ingNueConEma").removeAttr("readonly");
                            $("#ingNueConTipDoc").removeAttr("readonly");
                        }
                    });

            }
        },
        error : function(xhr, status) {
            return false;
        }
    });
}

function completaOficina(){
    $.ajax
    ({
        url : '../datos/datos.nuevo_contrato.php',
        type : 'POST',
        data : { tip : 'obtOfi' },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConOfi").val(json[0]['ID_PUNTO_PAGO']);
            }
            else{
                swal({
                        title: "Mensaje",
                        text: "No se encuentra la oficina para el usuario. <br> Por favor contacte a sistemas",
                        type: "info",
                        html: true,
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $("#ingNueConCodSis").val('');
                            $("#ingNueConCodSis").focus();
                        }
                    });

            }
        },
        error : function(xhr, status) {
            return false;
        }
    });
}


function Inicio()
{
    TipoDoc = document.getElementById("ingNueConTipDoc");
    genNueConLleSelDoc();
    completaOficina();
}

function genNueConLleSelDoc()
{
    var select= TipoDoc;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "Error: "+errm+", al llenar la lista de Tipo de Documentos", "error");
            }
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.nuevo_contrato.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selDoc");
}

function generaImp(){
    idcontrato = $("#ingNueConSeq").val();
    inmueble=$("#ingNueConCodSis").val();
    acueducto=$("#ingNueConAcu").val();
    estado=$("#ingNueConEst").val();
    proceso=$("#ingNueConPro").val();
    catastro=$("#ingNueConCat").val();
    sector=$("#ingNueConSec").val();
    ruta=$("#ingNueConRut").val();
    direccion=$("#ingNueConDir").val();
    urbanizacion=$("#ingNueConUrb").val();
    uso=$("#ingNueConUso").val();
    unidades=$("#ingNueConUni").val();
    agua=$("#ingNueConAgu").val();
    alcantarillado=$("#ingNueConAlc").val();
    tipo=$("#ingNueConSum").val();
    tarifa=$("#ingNueConTar").val();
    derecho=$("#ingNueConDerInc").val();
    fianza=$("#ingNueConFia").val();
    total=$("#ingNueConTotCon").val();
    documento=$("#ingNueConDoc").val();
    cliente=$("#ingNueConCli").val();
    alias=$("#ingNueConNom").val();
    telefono=$("#ingNueConTel").val();
    email=$("#ingNueConEma").val();
    TipoDoc=$("#ingNueConTipDoc").val();
    oficina=$("#ingNueConOfi").val();
    cuotas=$("#ingNueConCuo").val();
    conTar=$("#ingNueConTarCon").val();
    cupo=$("#ingNueConCup").val();
    medidor=$("#ingNueCanMed").val();

    generaCon();
}

function generaCon()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if(datos=="true")
            {
                swal
                (
                    {
                        title: "Advertencia",
                        text: "Va a crear un nuevo contrato con los parametros seleccionados <br> esto puede tardar algunos segundos",
                        type: "warning",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            guardaContratoNuevo();
                        }
                    }
                );
            }else{
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
        }
    }
    xmlhttp.open("POST", "../datos/datos.nuevo_contrato.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");

}


function imprimeContratoNuevo()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if (datos.substr(0,11)=="../../temp/"){
                swal("Mensaje!", "Se ha generado correctamente el contrato", "success");
            }else if(datos["res"]=="false"){
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
            $("#genNueConObj").prop('data',datos);

        }
    }
    xmlhttp.open("POST", "../reportes/reporte.NuevoContrato.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("idc="+idcontrato+"&inm="+inmueble+"&acu="+acueducto+"&est="+estado+
        "&pro="+proceso+"&cat="+catastro+"&sec="+sector+"&rut="+ruta+"&dir="+direccion+"&urb="+urbanizacion+"&uso="+uso+
        "&uni="+unidades+"&agu="+agua+"&alc="+alcantarillado+"&tpo="+tipo+"&tar="+tarifa+"&der="+derecho+"&fia="+fianza+
        "&tot="+total+"&doc="+documento+"&cli="+cliente+"&ali="+alias+"&tel="+telefono+"&ema="+email+"&ofi="+oficina+"&cuo="+cuotas);
}

function guardaContratoNuevo()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+" "+xmlhttp.responseText+", al intentar generar el contrato contacte a sistemas", "error");
            }

            if (datos["res"]=="true")
            {
                swal("Mensaje!", "Los datos del contrato fueron guardados correctamente", "success");
                imprimeContratoNuevo();

            }else if(datos["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: datos["cod"],
                        type: "error",
                        html:true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Aceptar!",
                        closeOnConfirm: true

                    }
                );
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.nuevo_contrato.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingCon&idc="+idcontrato+"&inm="+inmueble+"&acu="+acueducto+"&est="+estado+"&pro="+proceso
        +"&cat="+catastro+"&sec="+sector+"&rut="+ruta+"&dir="+direccion+"&urb="+urbanizacion+"&uso="+uso+"&uni="+unidades
        +"&agu="+agua+"&alc="+alcantarillado+"&tpo="+tipo+"&tar="+tarifa+"&der="+derecho+"&fia="+fianza+"&tot="+total+"&doc="
        +documento+"&cli="+cliente+"&ali="+alias+"&tel="+telefono+"&ema="+email+"&tdo="+TipoDoc+"&cuo="+cuotas+"&cta="+conTar+
        "&cup="+cupo);
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
                        }else{
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