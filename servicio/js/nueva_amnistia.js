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
var deudaPos;
var penalidades;
var penalidadesPos;
var inicial;
var exonerar;
var descuento;
var descuentoAnt;
var descuentoPos;
var pagar;
var reconexion;
var asistente;
var docasiste;
var entidad;
var caja;
var actividad;
var tarifa;
var apagar;

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


    $("#ingNueConCuo").blur(
        function(){
            if($(this).val()=='' && $("#ingNueConCodSis").val()!= '' ){

            }else{
                cuotas = $("#ingNueConCuo").val();
                categoria = $("#ingNueConCat").val();
                acueducto = $("#ingNueConAcu").val();
                tarifa = $("#ingNueConTar").val();
                actividad = $("#ingNueConAct").val();
                estado = $("#ingNueConEst").val();
                /*deuda = $("#ingNueConDeu").val();

                */
                compSession(completaAmnistia(cuotas, categoria, acueducto, tarifa, actividad));
                compSession(verificaReconexion(estado));
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
        url : '../datos/datos.nueva_amnistia.php',
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
                $("#ingNueConAct").val(json[0]['SEC_ACTIVIDAD']);
                $("#ingNueConCat").val(json[0]['CATEGORIA']);
                $("#ingNueConTar").val(json[0]['CONSEC_TARIFA']);
                $("#ingNueConEst").val(json[0]['ID_ESTADO']);
                $("#ingNueConNom").val(json[0]['ALIAS']);
                $("#ingNueConDoc").val(json[0]['DOCUMENTO']);
                $("#ingNueConTipDoc").val(json[0]['TIPO_DOC']);
                $("#ingNueConTel").val(json[0]['TELEFONO']);
                $("#ingNueConEma").val(json[0]['EMAIL']);
                $("#ingNueConGer").val(json[0]['ID_GERENCIA']);
                $("#ingNueConDeu").val(json[0]['DEUDA_A_NOV']);
                $("#ingNueConDeuPos").val(json[0]['DEUDA_RESTA']);
                $("#ingNueConPen").val(json[0]['PENALIDAD_A_NOV']);
                $("#ingNueConPenPos").val(json[0]['PENALIDAD_RESTA']);

                $("#ingNueConPag").val($("#ingNueConDeu").val() - $("#ingNueConPen").val());
                $("#ingNueConPagPos").val($("#ingNueConDeuPos").val() - $("#ingNueConPenPos").val());
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

function completaAmnistia(cuotas, categoria, acueducto, tarifa, actividad){
    $.ajax
    ({
        url : '../datos/datos.nueva_amnistia.php',
        type : 'POST',
        data : { tip : 'obtAmn', cuo : cuotas, cat : categoria, acu : acueducto, tar : tarifa, act: actividad},
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConDes").val(json[0]['PORCENTAJE_INICIAL']);

                descuentoAnt = Math.round( ($("#ingNueConPag").val() * $("#ingNueConDes").val())/ 100);
                descuentoPos = Math.round( ($("#ingNueConPagPos").val() * $("#ingNueConDes").val())/ 100);
                descuento = descuentoAnt + descuentoPos;
                $("#ingNueConValDes").val(descuento);
                tapagar = Math.round($("#ingNueConPag").val() - descuentoAnt) + Math.round($("#ingNueConPagPos").val() - descuentoPos) + Math.round($("#ingNueConPen").val()) + Math.round($("#ingNueConPenPos").val());
                $("#ingNueConValPag").val(tapagar);

            }
            else{
                swal({
                        title: "Mensaje",
                        text: "El numero maximo de cuotas deben ser 6.",
                        type: "info",
                        html: true,
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $("#ingNueConCuo").val('');
                            $("#ingNueConCuo").focus();
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
        url : '../datos/datos.nueva_amnistia.php',
        type : 'POST',
        data : { tip : 'obtRec', inm : $("#ingNueConCodSis").val(), est : estado},
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingNueConValRec").val(json[0]['VALOR_TARIFA']);
            }
            else{
                $("#ingNueConValRec").val(0);
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
        url : '../datos/datos.nueva_amnistia.php',
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
    xmlhttp.open("POST", "../datos/datos.nueva_amnistia.php", true);   // async
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
    xmlhttp.open("POST", "../datos/datos.nueva_amnistia.php", true);   // async
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
    deudaPos=$("#ingNueConDeuPos").val();
    mora=$("#ingNueConMor").val();
    inicial=$("#ingNueConPorCuoIni").val();
    exonerar=$("#ingNueConPorExoMor").val();
    descuentoPor=$("#ingNueConDes").val();
    pagar=$("#ingNueConPag").val();
    reconexion=$("#ingNueConValRec").val();
    oficina=$("#ingNueConOfi").val();
    asistente=$("#ingNueConAsi").val();
    docasiste=$("#ingNueConCed").val();
    entidad=$("#ingNueConEnt").val();
    caja=$("#ingNueConCaj").val();
    gerencia=$("#ingNueConGer").val();
    actividad=$("#ingNueConAct").val();
    tarifa=$("#ingNueConTar").val();
    penalidades=$("#ingNueConPen").val();
    penalidadesPos=$("#ingNueConPenPos").val();
    apagar=$("#ingNueConValPag").val();

    generaAmnistia();
}

function generaAmnistia()
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
                            //creaDiferidoAmnistia();
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
    xmlhttp.open("POST", "../datos/datos.nueva_amnistia.php", true);   // async
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
                imprimeAmnistiaNuevo();


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
    xmlhttp.open("POST", "../datos/datos.nueva_amnistia.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingPqr&inm="+inmueble+"&ali="+alias+"&doc="+documento+"&dir="+direccion+"&tel="+telefono+"&ema="+email+
        "&deu="+deuda+"&ini="+inicial+"&rec="+reconexion+"&cuo="+cuotas+"&pag="+pagar+"&ent="+entidad+"&pun="+oficina+"&caj="+caja+
        "&ger="+gerencia+"&apa="+apagar+"&dpo="+deudaPos);
}

function creaDiferidoAmnistia()
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
    xmlhttp.open("POST", "../datos/datos.nueva_amnistia.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingDif&inm="+inmueble+"&deu="+deuda+"&ini="+inicial+"&rec="+reconexion+"&cuo="+cuotas+"&apa="+apagar);
}

function imprimeAmnistiaNuevo()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if (datos.substr(0,11)=="../../temp/"){
                swal("Mensaje!", "Se ha generado correctamente el descuento por amnistía", "success");
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
    const d = new Date();
    xmlhttp.open("POST", "../reportes/reporte.NuevaAmnistia.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("inm="+inmueble+"&acu="+acueducto+"&est="+estado+"&dir="+direccion+"&urb="+urbanizacion+"&uso="+uso+"&cat="+categoria+
        "&cuo="+cuotas+"&doc="+documento+"&tdo="+TipoDoc+"&ali="+alias+"&cli="+cliente+"&tel="+telefono+"&ema="+email+"&ofi="+oficina+
        "&cal="+calidad+"&deu="+deuda+"&mor="+mora+"&ini="+inicial+"&exo="+exonerar+"&des="+descuentoPor+"&pag="+apagar+"&rec="+reconexion+
        "&asi="+asistente+"&das="+docasiste+"&pen="+penalidades+"&dpo="+deudaPos);
    inmueble=undefined; deuda=undefined; mora=undefined; inicial=undefined; exonerar=undefined; descuento=undefined; apagar=undefined;
    reconexion=undefined; penalidades=undefined;
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