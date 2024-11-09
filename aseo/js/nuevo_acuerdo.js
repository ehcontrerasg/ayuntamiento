var idacuerdo;
var inmueble;
var acueducto;
var estado;
var direccion;
var urbanizacion;
var uso;
var categoria;
var documento;
var TipoDoc;
var cliente;
var alias;
var telefono;
var email;
var calidad;
var cuotas;
var deuda;
var mora;
var inicial;
var exonerar;
var descuento;
var pagar;
var reconexion;
var asistente;
var docasiste;
var entidad;
var caja;
var gerencia;
var saldoPend;
var valCuotaIni;
var valCuotaMes;
var valTotal;

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
    $("#ingNueConCodSis").focus();
    $("#ingNueConValIni").blur(
        function (){
            $("#ingNueConValTotal").val( (Math.round($("#ingNueConValIni").val())));
            $("#ingNueConSalPend").val($("#ingNueConPag").val()-$("#ingNueConValIni").val());
            $("#ingNueConValCuoMen").val(Math.round($("#ingNueConSalPend").val() / $("#ingNueConCuo").val()));
            if($("#ingNueConValIni").val() < valorInicial){
                swal({
                    title: "Mensaje",
                    text: "El valor de la cuota inicial no puede ser menor a " + valorInicial + " .",
                    type: "info",
                    html: true,
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                });
                $("#ingNueConValIni").focus();
            }
        }
    );


    $("#ingNueConCuo").blur(
        function(){
            if($(this).val()=='' && $("#ingNueConCodSis").val()!= '' ){

            }else{
                cuotas = $("#ingNueConCuo").val();
                categoria = $("#ingNueConCat").val();
                deuda = $("#ingNueConDeu").val();
                mora = $("#ingNueConMor").val();
                acueducto = $("#ingNueConAcu").val();
                estado = $("#ingNueConEst").val();
                compSession(completaAcuerdo(cuotas, categoria, deuda, mora, acueducto));
                compSession(verificaReconexion(estado))
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
        url : '../datos/datos.nuevo_acuerdo.php',
        type : 'POST',
        data : { tip : 'obtDat', inm : $("#ingNueConCodSis").val() },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConAcu").val(json[0]['ID_PROYECTO']);
                $("#ingNueConDir").val(json[0]['DIRECCION']);
                $("#ingNueConUrb").val(json[0]['DESC_URBANIZACION']);
                $("#ingNueConUso").val(json[0]['ID_USO']);
                $("#ingNueConCat").val(json[0]['CATEGORIA']);
                $("#ingNueConEst").val(json[0]['ID_ESTADO']);
                $("#ingNueConNom").val(json[0]['ALIAS']);
                $("#ingNueConDoc").val(json[0]['DOCUMENTO']);
                $("#ingNueConTipDoc").val(json[0]['TIPO_DOC']);
                $("#ingNueConTel").val(json[0]['TELEFONO']);
                $("#ingNueConEma").val(json[0]['EMAIL']);
                $("#ingNueConDeu").val(json[0]['DEUDA']);
                $("#ingNueConMor").val(json[0]['MORA']);
                $("#ingNueConGer").val(json[0]['ID_GERENCIA']);
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

function completaAcuerdo(cuotas, categoria, deuda, mora, acueducto){
    $.ajax
    ({
        url : '../datos/datos.nuevo_acuerdo.php',
        type : 'POST',
        data : { tip : 'obtAcu', cuo : cuotas, cat : categoria, acu : acueducto},
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConPorCuoIni").val(json[0]['PORCENTAJE_INICIAL']);
                $("#ingNueConPorExoMor").val(json[0]['PORCEN_MORA_EXONERA']);
                descuento = Math.round((mora * $("#ingNueConPorExoMor").val())/ 100);
                $("#ingNueConDes").val(descuento);
                pagar = Math.round(deuda - descuento);
                $("#ingNueConPag").val(pagar);
                valorInicial = Math.round((pagar * $("#ingNueConPorCuoIni").val())/100);
                $("#ingNueConValIni").val(valorInicial);
                saldoPendiente = Math.round($("#ingNueConPag").val() - valorInicial);
                $("#ingNueConSalPend").val(saldoPendiente);
                cuotaMensual = Math.round((saldoPendiente / $("#ingNueConCuo").val()));
                $("#ingNueConValCuoMen").val(cuotaMensual);
            }
            else{
                swal({
                        title: "Mensaje",
                        text: "No se encuentran datos para realizar el acuerdo. <br> Por favor contacte a sistemas",
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

function verificaReconexion(estado){
    $.ajax
    ({
        url : '../datos/datos.nuevo_acuerdo.php',
        type : 'POST',
        data : { tip : 'obtRec', inm : $("#ingNueConCodSis").val(), est : estado},
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConRec").val(json[0]['VALOR_TARIFA']);
            }
            else{
                $("#ingNueConRec").val(0);
            }
            totalPagar= (Math.round($("#ingNueConValIni").val()));
            $("#ingNueConValTotal").val(totalPagar);
        },
        error : function(xhr, status) {
            return false;
        }
    });
}

function completaOficina(){
    $.ajax
    ({
        url : '../datos/datos.nuevo_acuerdo.php',
        type : 'POST',
        data : { tip : 'obtOfi' },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConOfi").val(json[0]['ID_PUNTO_PAGO']);
                $("#ingNueConAsi").val(json[0]['NOMBRE']);
                $("#ingNueConCed").val(json[0]['ID_USUARIO']);
                $("#ingNueConEnt").val(json[0]['COD_ENTIDAD']);
                $("#ingNueConCaj").val(json[0]['NUM_CAJA']);
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
    calidad = document.getElementById("ingNueConCal");
    genNueConLleSelDoc();
    genNueConLleSelCal();
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
    xmlhttp.open("POST", "../datos/datos.nuevo_acuerdo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selDoc");
}

function genNueConLleSelCal()
{
    var select= calidad;
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
                swal("Error!", "Error: "+errm+", al llenar la lista de En Calidad De", "error");
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
    xmlhttp.open("POST", "../datos/datos.nuevo_acuerdo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selCal");
}

function generaImp(){
    //idcontrato = $("#ingNueConSeq").val();
    inmueble=$("#ingNueConCodSis").val();
    acueducto=$("#ingNueConAcu").val();
    estado=$("#ingNueConEst").val();
    direccion=$("#ingNueConDir").val();
    urbanizacion=$("#ingNueConUrb").val();
    uso=$("#ingNueConUso").val();
    categoria=$("#ingNueConCat").val();
    cuotas=$("#ingNueConCuo").val();
    documento=$("#ingNueConDoc").val();
    TipoDoc=$("#ingNueConTipDoc").val();
    cliente=$("#ingNueConCli").val();
    alias=$("#ingNueConNom").val();
    telefono=$("#ingNueConTel").val();
    email=$("#ingNueConEma").val();
    calidad=$("#ingNueConCal").val();
    deuda=$("#ingNueConDeu").val();
    mora=$("#ingNueConMor").val();
    exonerar=$("#ingNueConPorExoMor").val();
    descuento=$("#ingNueConDes").val();
    pagar=$("#ingNueConPag").val();
    inicial=$("#ingNueConPorCuoIni").val();
    valCuotaIni=$("#ingNueConValIni").val();
    valCuotaMes=$("#ingNueConValCuoMen").val();
    reconexion=$("#ingNueConRec").val();
    oficina=$("#ingNueConOfi").val();
    asistente=$("#ingNueConAsi").val();
    docasiste=$("#ingNueConCed").val();
    entidad=$("#ingNueConEnt").val();
    caja=$("#ingNueConCaj").val();
    gerencia=$("#ingNueConGer").val();
    saldoPend=$("#ingNueConSalPend").val();
    valTotal=$("#ingNueConValTotal").val();

    generaAcu();
}

function generaAcu()
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
                        text: "Va a crear un nuevo acuerdo con los parametros seleccionados <br> esto puede tardar algunos segundos",
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
                            guardaSolicitudPqr();
                            //guardaDatosAcuerdo();
                            //creaDiferidoAcuerdo();
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
    xmlhttp.open("POST", "../datos/datos.nuevo_acuerdo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");

}

function guardaSolicitudPqr()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+" "+xmlhttp.responseText+", al intentar generar la solicitud", "error");
            }

            if (datos["res"]=="true")
            {
                swal("Mensaje!", "Los datos de la solicitud fueron guardados correctamente", "success");
                imprimeAcuerdoNuevo();


            }else if(datos["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: datos["res"],
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
    xmlhttp.open("POST", "../datos/datos.nuevo_acuerdo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingPqr&inm="+inmueble+"&ali="+alias+"&doc="+documento+"&dir="+direccion+"&tel="+telefono+"&ema="+email+
        "&deu="+deuda+"&ini="+inicial+"&rec="+reconexion+"&cuo="+cuotas+"&pag="+pagar+"&ent="+entidad+"&pun="+oficina+"&caj="+caja+
        "&ger="+gerencia+"&val="+valCuotaIni+"&mes="+valCuotaMes+"&des="+descuento+"&sal="+saldoPend);
}

function creaDiferidoAcuerdo()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+" "+xmlhttp.responseText+", al intentar crear el diferido", "error");
            }

            if (datos["res"]=="true")
            {
                swal("Mensaje!", "Los datos del diferido fueron guardados correctamente", "success");
                //imprimeAcuerdoNuevo();
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
    xmlhttp.open("POST", "../datos/datos.nuevo_acuerdo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingDif&inm="+inmueble+"&deu="+deuda+"&ini="+inicial+"&rec="+reconexion+"&cuo="+cuotas+"&pag="+pagar+"&val="+valCuotaIni);
}

function imprimeAcuerdoNuevo()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if (datos.substr(0,11)=="../../temp/"){
                swal("Mensaje!", "Se ha generado correctamente el acuerdo", "success");
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
    xmlhttp.open("POST", "../vistas/vista.reporteNuevoAcuerdo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("inm="+inmueble+"&acu="+acueducto+"&est="+estado+"&dir="+direccion+"&urb="+urbanizacion+"&uso="+uso+"&cat="+categoria+
        "&cuo="+cuotas+"&doc="+documento+"&tdo="+TipoDoc+"&ali="+alias+"&cli="+cliente+"&tel="+telefono+"&ema="+email+"&ofi="+oficina+
        "&cal="+calidad+"&deu="+deuda+"&mor="+mora+"&ini="+inicial+"&exo="+exonerar+"&des="+descuento+"&pag="+pagar+"&rec="+reconexion+
        "&asi="+asistente+"&das="+docasiste+"&val="+valCuotaIni+"&sal="+saldoPend+"&mes="+valCuotaMes+"&tot="+valTotal);
}


/*function guardaDiferidoAcuerdo()
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
}*/

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