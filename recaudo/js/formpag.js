/**
 * Created by PC on 6/23/2016.
 */

var ifdetfacpend;
var selMedio;
var tdMonto;
var tdImporte1;
var tdVuelta;
var tdFavor;
var tdPendiente;
var tdBanco;
var tdImporte2;
var tdTipo;
var tdImporte3;
var tdImporte4;
var tdAproba;
var botonPago;
var hidperf;
var inpEntPago;
var inpPuntoPag;
var inpCaja;
var inpDescEnti;
var inpBotGen;
var tdObservacion;
var tdProyecto;
var tdArregloFact;
var tdTipoCliente;

function inicioFormPago(){
    ifdetfacpend=document.getElementById('ifdetfactpend');
    inpDescEnti=document.getElementById("des_ent");
    inpDescPunt=document.getElementById("des_punto");
    selMedio=document.getElementById("medio");
    tdMonto=document.getElementById("tdmonto");
    tdImporte1=document.getElementById("tdimporte1");
    tdVuelta=document.getElementById("tdvuelta");
    tdFavor=document.getElementById("tdfavor");
    tdPendiente=document.getElementById("tdpendiente");
    tdBanco=document.getElementById("tdbanco");
    tdImporte2=document.getElementById("tdimporte2");
    tdTipo=document.getElementById("tdtipo");
    tdImporte3=document.getElementById("tdimporte3");
    tdAproba=document.getElementById("tdaproba");
	tdObservacion=document.getElementById("tdobservacion");

    tdImporte4=document.getElementById("tdimporte4");

	tdProyecto=document.getElementById("tdproyecto");
    tdArregloFact=document.getElementById("tdarreglofact");
    tdTipoCliente=document.getElementById("tdtipocliente")

    botonPago=document.getElementById("botonpago");
    hidperf=document.getElementById("per_usu");
    inpEntPago=document.getElementById("cod_ent");
    inpPuntoPag=document.getElementById("id_punto");
    inpCaja=document.getElementById("num_caja");
	inpBotGen=document.getElementById("procesar");
    inpEntPago.addEventListener("blur",descInpEntidad);
    inpPuntoPag.addEventListener("blur",descPun);
	inpBotGen.addEventListener("click",habilita);
    actIfDetfact();
    campPerfiles();
}

function habilita(){
	inpBotGen.style.display = 'none';
}

function anular(e) {
	tecla = (document.all) ? e.keyPress : e.which;
	if(tecla == 13){
		return false;
	}
}


function descInpEntidad(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            inpDescEnti.value=datos;
        }
    }
    xmlhttp.open("POST", "../datos/datos.datPagos.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=entPago&ent="+inpEntPago.value);
}


function descPun(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            inpDescPunt.value=datos;
        }
    }
    xmlhttp.open("POST", "../datos/datos.datPagos.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=punPago&ent="+inpEntPago.value+"&pun="+inpPuntoPag.value);
}




function campPerfiles(){
    var valPerf;
    valPerf=hidperf.value;
    if(valPerf=="AD"){
        inpEntPago.readOnly=false;
        inpPuntoPag.readOnly=false;
        inpCaja.readOnly=false;
    }else{
        inpEntPago.readOnly=true;
        inpPuntoPag.readOnly=true;
        inpCaja.readOnly=true;
    }
}

function actIfDetfact(){
    ifdetfacpend.style.display = 'table-cell';
    ifdetfacpend.contentDocument.location.reload(true);
    ifdetfacpend.src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value');

}


function oculta(){
    if(selMedio.value == 0){
        tdMonto.style.display = 'none';
        tdImporte1.style.display = 'none';
        tdVuelta.style.display = 'none';
        tdFavor.style.display = 'none';
        tdPendiente.style.display = 'none';
        tdBanco.style.display = 'none';
        tdImporte2.style.display = 'none';
        tdTipo.style.display = 'none';
        tdImporte3.style.display = 'none';
        tdImporte4.style.display = 'none';
        tdAproba.style.display = 'none';
		tdObservacion.style.display = 'none';
        botonPago.style.display = 'none';
    }
    if(document.getElementById("medio").value == 1){
        document.getElementById("tdmonto").style.display = 'table-cell';
        document.getElementById("tdimporte1").style.display = 'table-cell';
        document.getElementById("tdvuelta").style.display = 'table-cell';
        document.getElementById("tdfavor").style.display = 'table-cell';
        document.getElementById("tdpendiente").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
		document.getElementById("tdobservacion").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
        document.getElementById("tdimporte4").style.display = 'none';
		document.getElementById('monto').focus();
    }
    if(document.getElementById("medio").value == 2){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdfavor").style.display = 'table-cell';
        document.getElementById("tdpendiente").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'table-cell';
        document.getElementById("tdimporte2").style.display = 'table-cell';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
		document.getElementById("tdobservacion").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
        document.getElementById("tdimporte4").style.display = 'none';
		document.getElementById('banco').focus();
    }
    if(document.getElementById("medio").value == 3){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdfavor").style.display = 'table-cell';
        document.getElementById("tdpendiente").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'table-cell';
        document.getElementById("tdimporte3").style.display = 'table-cell';
        document.getElementById("tdaproba").style.display = 'table-cell';
		document.getElementById("tdobservacion").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
        document.getElementById("tdimporte4").style.display = 'none';
		document.getElementById('tarjeta').focus();
    }
    if(document.getElementById("medio").value == 4){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdfavor").style.display = 'table-cell';
        document.getElementById("tdpendiente").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
		document.getElementById("tdobservacion").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
        document.getElementById("tdimporte4").style.display = 'table-cell';
        document.getElementById('importe4').focus();
		
    }
    if(document.getElementById("medio").value == 5){
        document.getElementById("tdmonto").style.display = 'table-cell';
        document.getElementById("tdimporte1").style.display = 'table-cell';
        document.getElementById("tdvuelta").style.display = 'table-cell';
        document.getElementById("tdfavor").style.display = 'table-cell';
        document.getElementById("tdpendiente").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'table-cell';
        document.getElementById("tdimporte3").style.display = 'table-cell';
        document.getElementById("tdaproba").style.display = 'table-cell';
		document.getElementById("tdobservacion").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
        document.getElementById("tdimporte4").style.display = 'none';
		document.getElementById('monto').focus();
    }
    if(document.getElementById("medio").value == 6){
        document.getElementById("tdmonto").style.display = 'table-cell';
        document.getElementById("tdimporte1").style.display = 'table-cell';
        document.getElementById("tdvuelta").style.display = 'table-cell';
        document.getElementById("tdfavor").style.display = 'table-cell';
        document.getElementById("tdpendiente").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'table-cell';
        document.getElementById("tdimporte2").style.display = 'table-cell';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
		document.getElementById("tdobservacion").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
        document.getElementById("tdimporte4").style.display = 'none';
		document.getElementById('monto').focus();
    }

}


function calculavuelto(){
    var proyecto, monto,importe1,vuelta,favor, deuda, pendiente, importe2, importe3, arrayFact, tipClient, importe4;
    monto = document.getElementById('monto').value;
    importe1 = document.getElementById('importe1').value;
    importe2 = document.getElementById('importe2').value;
    importe3 = document.getElementById('importe3').value;
    importe4 = document.getElementById('importe4').value;
    deuda = document.getElementById('deuda').value;
    proyecto = document.getElementById('proyecto').value;
    arrayFact = document.getElementById('arreglofact').value;
    tipClient = document.getElementById('tipocliente').value;

    if(proyecto == 'BC') {
        if (importe1 == '') importe1 = 0;
        if (importe2 == '') importe2 = 0;
        if (importe3 == '') importe3 = 0;
        if (importe4 == '') importe4 = 0;
        vuelta = parseFloat(monto) - parseFloat(importe1);
        favor = (parseFloat(importe1) + parseFloat(importe2) + parseFloat(importe3) + parseFloat(importe4) - parseFloat(deuda));
        pendiente = parseFloat(deuda) - parseFloat(importe1) - parseFloat(importe2) - parseFloat(importe3) - parseFloat(importe4);
        //importe1 = parseFloat(deuda);
        document.getElementById('vuelta').value = Math.round(vuelta);
        document.getElementById('pendiente').value = Math.round(pendiente);
        // document.getElementById('importe1').value = Math.round(importe1);
        if (favor > 0) {
            document.getElementById('favor').value = Math.round(favor);
        }
        else {
            document.getElementById('favor').value = 0;
        }
    }

    if(proyecto == 'SD'){
        if(tipClient == 'GC') {
            if (importe1 == '') importe1 = 0;
            if (importe2 == '') importe2 = 0;
            if (importe3 == '') importe3 = 0;
            if (importe4 == '') importe4 = 0;
            vuelta = parseFloat(monto) - parseFloat(importe1);
            favor = (parseFloat(importe1) + parseFloat(importe2) + parseFloat(importe3) +parseFloat(importe4) - parseFloat(deuda));
            pendiente = parseFloat(deuda) - parseFloat(importe1) - parseFloat(importe2) - parseFloat(importe3) - parseFloat(importe4);
            document.getElementById('vuelta').value = Math.round(vuelta);
            document.getElementById('pendiente').value = Math.round(pendiente);
            // document.getElementById('importe1').value = Math.round(importe1);
            if (favor > 0) {
                document.getElementById('favor').value = Math.round(favor);
            }
            else {
                document.getElementById('favor').value = 0;
            }
        }
        if(tipClient == 'CN') {
            var importereal = 0, saldo;
            if (importe1 == '') importe1 = 0;
            if (importe2 == '') importe2 = 0;
            if (importe3 == '') importe3 = 0;
            if (importe4 == '') importe4 = 0;
            var importe = (parseFloat(importe1) + parseFloat(importe2) + parseFloat(importe3) + parseFloat(importe4));
            saldo =  parseFloat(importe);
            var myarr  = arrayFact.split(' ');
            if(parseFloat(importe) > parseFloat(monto)){
                document.getElementById('importe1').value = '';
                document.getElementById('vuelta').value = '';
                document.getElementById('favor').value = 0;
                swal({
                    title: "Error",
                    text: "El importe no puede ser mayor al monto.<br><br>Por favor verifique.",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK!",
                    cancelButtonText: "No!",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    html: true
                });
                document.pagos.monto.value = '';
                document.pagos.importe1.value = '';
                exit();
                //document.getElementById('ifdetfactpend').style.display = 'none';
            }
            for(var i =0; i<myarr.length; i++){
                if (importe >= myarr[i]){
                    importe = importe - myarr[i];
                    if(importe == 0){
                        importereal = parseFloat(saldo);
                    }
                    else{
                        importereal = parseFloat(saldo) - parseFloat(importe);
                    }

                }
                else{
                    importereal = parseFloat(saldo) - parseFloat(importe);
                    //alert('importe real: '+importereal);
                  // alert('a devolver: '+ (monto-importereal));
                    //exit();
                }
            }
            if(importe1 > 0) {
                document.getElementById('importe1').value = parseFloat(importereal);
            }
            if(importe2 > 0) {
                document.getElementById('importe2').value = parseFloat(importereal);
            }
            if(importe3 > 0) {
                document.getElementById('importe3').value = parseFloat(importereal);
            }
            if(importe4 > 0) {
                document.getElementById('importe4').value = parseFloat(importereal);
            }

            vuelta = parseFloat(monto) - parseFloat(importereal);
            favor = parseFloat(importereal)- parseFloat(deuda);
            pendiente = parseFloat(deuda) - parseFloat(importereal);

            document.getElementById('vuelta').value = Math.round(vuelta);
            document.getElementById('pendiente').value = Math.round(pendiente);
            // document.getElementById('importe1').value = Math.round(importe1);
            if (favor > 0) {
                document.getElementById('favor').value = Math.round(favor);
            }
            else {
                document.getElementById('favor').value = 0;
            }

        }
    }


}

function recarga(){
    document.getElementById('medio').value = 0;
    document.getElementById('monto').value = '';
    //document.getElementById('importe1').value = '';
    document.getElementById('banco').value = 0;
    document.getElementById('importe2').value = '';
    document.getElementById('tarjeta').value = 0;
    document.getElementById('numaproba').value = '';
    document.getElementById('importe3').value = '';
    document.getElementById('importe4').value = '';
    document.pagos.submit();
}

function habframe(){


    if (document.pagos.medio.value == 0){
        //document.getElementById('ifdetfactpend').style.display = 'none';
        document.getElementById('procesar').style.display = 'none';
        document.pagos.medio.value = 0;
        document.pagos.monto.value = '';
        document.pagos.importe1.value = '';
        document.pagos.vuelta.value = '';
        document.pagos.favor.value = '';
        document.pagos.pendiente.value = '';
        document.pagos.banco.value = 0;
        document.pagos.importe2.value = '';
        document.pagos.tarjeta.value = 0;
        document.pagos.importe3.value = '';
        document.pagos.numaproba.value = '';
        document.pagos.importe4.value = '';
    }
    else if (document.pagos.medio.value == 1){
        if (document.pagos.importe1.value == '' || document.pagos.monto.value == ''){
            //document.getElementById('ifdetfactpend').style.display = 'none';
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
            //document.getElementById('ifdetfactpend').style.display = 'table-cell';
            document.getElementById("ifdetfactpend").contentDocument.location.reload(true);
            document.getElementById('ifdetfactpend').src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value')+'&importe1='+$('#importe1').attr('value');
        }
        document.pagos.banco.value = 0;
        document.pagos.importe2.value = '';
        document.pagos.tarjeta.value = 0;
        document.pagos.importe3.value = '';
        document.pagos.numaproba.value = '';
        document.pagos.importe4.value = '';
    }
    else if (document.pagos.medio.value == 2 ) {
        if (document.pagos.banco.value == 0 || document.pagos.importe2.value == ''){
            //document.getElementById('ifdetfactpend').style.display = 'none';
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
            //document.getElementById('ifdetfactpend').style.display = 'table-cell';
            document.getElementById("ifdetfactpend").contentDocument.location.reload(true);
            document.getElementById('ifdetfactpend').src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value')+'&importe2='+$('#importe2').attr('value');
        }
        document.pagos.monto.value = '';
        document.pagos.importe1.value = '';
        document.pagos.vuelta.value = '';
        document.pagos.pendiente.value = '';
        document.pagos.tarjeta.value = 0;
        document.pagos.importe3.value = '';
        document.pagos.numaproba.value = '';
        document.pagos.importe4.value = '';
    }
    else if (document.pagos.medio.value == 3) {
        if (document.pagos.tarjeta.value == 0 || document.pagos.importe3.value == '' || document.pagos.numaproba.value == ''){
            //document.getElementById('ifdetfactpend').style.display = 'none';
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
            //document.getElementById('ifdetfactpend').style.display = 'table-cell';
            document.getElementById("ifdetfactpend").contentDocument.location.reload(true);
            document.getElementById('ifdetfactpend').src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value')+'&importe3='+$('#importe3').attr('value');
        }
        document.pagos.monto.value = '';
        document.pagos.importe1.value = '';
        document.pagos.vuelta.value = '';
        document.pagos.pendiente.value = '';
        document.pagos.banco.value = 0;
        document.pagos.importe2.value = '';
        document.pagos.importe4.value = '';
    }
    else if (document.pagos.medio.value == 4){
        if (document.pagos.importe4.value == ''){
            //document.getElementById('ifdetfactpend').style.display = 'none';
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
            //document.getElementById('ifdetfactpend').style.display = 'table-cell';
            document.getElementById("ifdetfactpend").contentDocument.location.reload(true);
            document.getElementById('ifdetfactpend').src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value')+'&importe4='+$('#importe4').attr('value');
        }
        document.pagos.banco.value = 0;
        document.pagos.importe1.value = '';
        document.pagos.importe2.value = '';
        document.pagos.tarjeta.value = 0;
        document.pagos.importe3.value = '';
        document.pagos.numaproba.value = '';
    }
    else if (document.pagos.medio.value == 5) {
        if (document.pagos.importe1.value == '' || document.pagos.monto.value == '' || document.pagos.tarjeta.value == 0 || document.pagos.importe3.value == '' || document.pagos.numaproba.value == ''){
            //document.getElementById('ifdetfactpend').style.display = 'none';
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
            //document.getElementById('ifdetfactpend').style.display = 'table-cell';
            document.getElementById("ifdetfactpend").contentDocument.location.reload(true);
            document.getElementById('ifdetfactpend').src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value')+'&importe1='+$('#importe1').attr('value')+'&importe3='+$('#importe3').attr('value');
        }
        document.pagos.banco.value = 0;
        document.pagos.importe2.value = '';
    }
    else if (document.pagos.medio.value == 6) {
        if (document.pagos.importe1.value == '' || document.pagos.monto.value == '' || document.pagos.banco.value == 0 || document.pagos.importe2.value == ''){
            //document.getElementById('ifdetfactpend').style.display = 'none';
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
            //document.getElementById('ifdetfactpend').style.display = 'table-cell';
            document.getElementById("ifdetfactpend").contentDocument.location.reload(true);
            document.getElementById('ifdetfactpend').src = 'vista.det_factpend.php?inmueble='+$('#cod_inmueble').attr('value')+'&importe1='+$('#importe1').attr('value')+'&importe2='+$('#importe2').attr('value');
        }
        document.pagos.tarjeta.value = 0;
        document.pagos.importe3.value = '';
        document.pagos.numaproba.value = '';
    }
}
