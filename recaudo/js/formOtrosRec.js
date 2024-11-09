var hidperf;
var inpEntPago;
var inpPuntoPag;
var inpCaja;
var inpDescEnti;
var inpDescPunt;
var inpBotGen;
var tdValfact;
var tdDes_uso;
var tdProyecto;
var butDiferir;

function inicioFormOtrosRec(){
    inpDescEnti=document.getElementById("des_ent");
    inpDescPunt=document.getElementById("des_punto");
    hidperf=document.getElementById("per_usu");
    inpEntPago=document.getElementById("cod_ent");
    inpPuntoPag=document.getElementById("id_punto");
    inpCaja=document.getElementById("num_caja");
	inpBotGen=document.getElementById("procesar");
    inpEntPago.addEventListener("blur",descInpEntidad);
    inpPuntoPag.addEventListener("blur",descPun);
	inpBotGen.addEventListener("click",habilita);

    tdValfact=document.getElementById("tdvalfact");
    tdDes_uso=document.getElementById("tddes_uso");
    tdProyecto=document.getElementById("tdproyecto");
    habilitadiv();
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

function oculta(){
    if(document.getElementById("medio").value == 0){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdconcepto").style.display = 'none';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdcheque").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdnumero").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
        document.getElementById("botonpago").style.display = 'none';
    }
    if(document.getElementById("medio").value == 1){
        document.getElementById("tdmonto").style.display = 'table-cell';
        document.getElementById("tdvuelta").style.display = 'table-cell';
        document.getElementById("tdimporte1").style.display = 'table-cell';
        document.getElementById("tdconcepto").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdcheque").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdnumero").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
        document.getElementById("botonpago").style.display = 'table-cell';
		document.getElementById('concepto').focus();
    }
    if(document.getElementById("medio").value == 2){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdconcepto").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'table-cell';
        document.getElementById("tdcheque").style.display = 'table-cell';
        document.getElementById("tdimporte2").style.display = 'table-cell';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdnumero").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
        document.getElementById("botonpago").style.display = 'table-cell';
		document.getElementById('concepto').focus();
    }
    if(document.getElementById("medio").value == 3){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdconcepto").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdcheque").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'table-cell';
        document.getElementById("tdnumero").style.display = 'table-cell';
        document.getElementById("tdimporte3").style.display = 'table-cell';
        document.getElementById("tdaproba").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
		document.getElementById('concepto').focus();
    }
    if(document.getElementById("medio").value == 4){
        document.getElementById("tdmonto").style.display = 'none';
        document.getElementById("tdvuelta").style.display = 'none';
        document.getElementById("tdimporte1").style.display = 'none';
        document.getElementById("tdconcepto").style.display = 'none';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdcheque").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdnumero").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
        document.getElementById("botonpago").style.display = 'none';
		document.getElementById('concepto').focus();
    }
    if(document.getElementById("medio").value == 5){
        document.getElementById("tdmonto").style.display = 'table-cell';
        document.getElementById("tdvuelta").style.display = 'table-cell';
        document.getElementById("tdimporte1").style.display = 'table-cell';
        document.getElementById("tdconcepto").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'none';
        document.getElementById("tdcheque").style.display = 'none';
        document.getElementById("tdimporte2").style.display = 'none';
        document.getElementById("tdtipo").style.display = 'table-cell';
        document.getElementById("tdnumero").style.display = 'table-cell';
        document.getElementById("tdimporte3").style.display = 'table-cell';
        document.getElementById("tdaproba").style.display = 'table-cell';
        document.getElementById("botonpago").style.display = 'table-cell';
		document.getElementById('concepto').focus();
    }
    if(document.getElementById("medio").value == 6){
        document.getElementById("tdmonto").style.display = 'table-cell';
        document.getElementById("tdvuelta").style.display = 'table-cell';
        document.getElementById("tdimporte1").style.display = 'table-cell';
        document.getElementById("tdconcepto").style.display = 'table-cell';
        document.getElementById("tdbanco").style.display = 'table-cell';
        document.getElementById("tdcheque").style.display = 'table-cell';
        document.getElementById("tdimporte2").style.display = 'table-cell';
        document.getElementById("tdtipo").style.display = 'none';
        document.getElementById("tdnumero").style.display = 'none';
        document.getElementById("tdimporte3").style.display = 'none';
        document.getElementById("tdaproba").style.display = 'none';
        document.getElementById("botonpago").style.display = 'table-cell';
		document.getElementById('concepto').focus();
    }

}

function habilitadiv(){
    if(document.getElementById("direccion").value == ' ' && document.getElementById("cod_inmueble").value != ''){
        document.getElementById("divformapago").style.display = 'none';
        document.getElementById("divdatosformapago").style.display = 'none';
        //showDialog('Error Cargando Datos','El Inmueble N&deg; <?php echo $cod_inmueble?> No Existe.<br><br>Por Favor Verifique.','error',3);
    }
    if(document.getElementById("direccion").value != ' '){
        document.getElementById("divformapago").style.display = 'block';
        document.getElementById("divdatosformapago").style.display = 'block';
    }
}

function valida_conceptos(){
    var concepto, est_inm, servicio, tarifa_reco;
    concepto = document.getElementById('concepto').value;
    est_inm = document.getElementById('est_inm').value;
    tarifa_reco = document.getElementById('tarifa_reco').value;
    precio = document.getElementById('importe1').value;
    servicio = concepto.split(" ",1);
    if(document.getElementById('medio').value == 1){
        if((est_inm == 'SS' || est_inm == 'PC') && servicio != '20'){
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble tiene un pago de reconexion pendiente.<br><br>Por favor ingrese el pago.",
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
            document.getElementById('concepto').value = 0;
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
        }
        else if((est_inm == 'SS' || est_inm == 'PC') && servicio == '20' && tarifa_reco > 0 ){
            document.getElementById('importe1').value = tarifa_reco;
            document.getElementById('vuelta').value = document.getElementById('importe1').value - tarifa_reco;
            if(precio != ''){
                document.getElementById('procesar').style.display = 'table-cell';
            }
            else{
                document.getElementById('procesar').style.display = 'none';
            }
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = true;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio != '20'){
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio == '20' && tarifa_reco > 0){
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('concepto').value = 0;
            document.getElementById('procesar').style.display = 'none';
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble NO tiene orden de reconexi&oacute;n por pagar.<br><br>Por favor verifique.",
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
        }
    }

    if(document.getElementById('medio').value == 2){

        if((est_inm == 'SS' || est_inm == 'PC') && servicio != '20'){
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble tiene un pago de reconexion pendiente.<br><br>Por favor ingrese el pago.",
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
            document.getElementById('concepto').value = 0;
            document.getElementById('banco').value = 0;
            document.getElementById('cheque').value = '';
            document.getElementById('importe2').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('banco').readOnly = false;
            document.getElementById('cheque').readOnly = false;
            document.getElementById('importe2').readOnly = false;
        }
        else if((est_inm == 'SS' || est_inm == 'PC') && servicio == '20' && tarifa_reco > 0){
            document.getElementById('importe2').value = tarifa_reco;
            //document.getElementById('procesar').style.display = 'table-cell';
            document.getElementById('banco').readOnly = false;
            document.getElementById('cheque').readOnly = false;
            document.getElementById('importe2').readOnly = true;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio != '20'){
            document.getElementById('banco').value = 0;
            document.getElementById('cheque').value = '';
            document.getElementById('importe2').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('banco').readOnly = false;
            document.getElementById('cheque').readOnly = false;
            document.getElementById('importe2').readOnly = false;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio == '20' && tarifa_reco > 0){
            document.getElementById('banco').value = 0;
            document.getElementById('cheque').value = '';
            document.getElementById('importe2').value = '';
            document.getElementById('concepto').value = 0;
            document.getElementById('procesar').style.display = 'none';
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble NO tiene orden de reconexi&oacute;n por pagar.<br><br>Por favor verifique.",
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
        }
    }

    if(document.getElementById('medio').value == 3){
        if((est_inm == 'SS' || est_inm == 'PC') && servicio != '20'){
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble tiene un pago de reconexion pendiente.<br><br>Por favor ingrese el pago.",
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
            document.getElementById('concepto').value = 0;
            document.getElementById('tarjeta').value = 0;
            document.getElementById('numcard').value = '';
            document.getElementById('numaproba').value = '';
            document.getElementById('importe3').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('tarjeta').readOnly = false;
            document.getElementById('numcard').readOnly = false;
            document.getElementById('numaproba').readOnly = false;
            document.getElementById('importe3').readOnly = false;
        }
        else if((est_inm == 'SS' || est_inm == 'PC') && servicio == '20' && tarifa_reco > 0){
            document.getElementById('importe3').value = tarifa_reco;
            //document.getElementById('procesar').style.display = 'table-cell';
            document.getElementById('tarjeta').readOnly = false;
            document.getElementById('numcard').readOnly = false;
            document.getElementById('numaproba').readOnly = false;
            document.getElementById('importe3').readOnly = true;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio != '20'){
            document.getElementById('tarjeta').value = 0;
            document.getElementById('numcard').value = '';
            document.getElementById('numaproba').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('tarjeta').readOnly = false;
            document.getElementById('numcard').readOnly = false;
            document.getElementById('numaproba').readOnly = false;
            document.getElementById('importe3').readOnly = false;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio == '20' && tarifa_reco > 0){
            document.getElementById('tarjeta').value = 0;
            document.getElementById('numcard').value = '';
            document.getElementById('numaproba').value = '';
            document.getElementById('importe3').value = '';
            document.getElementById('concepto').value = 0;
            document.getElementById('procesar').style.display = 'none';
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble NO tiene orden de reconexi&oacute;n por pagar.<br><br>Por favor verifique.",
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
        }
    }

    if(document.getElementById('medio').value == 5){
        if((est_inm == 'SS' || est_inm == 'PC') && servicio != '20'){
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble tiene un pago de reconexion pendiente.<br><br>Por favor ingrese el pago.",
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
            document.getElementById('concepto').value = 0;
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
            document.getElementById('tarjeta').value = 0;
            document.getElementById('numcard').value = '';
            document.getElementById('numaproba').value = '';
            document.getElementById('importe3').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('tarjeta').readOnly = false;
            document.getElementById('numcard').readOnly = false;
            document.getElementById('numaproba').readOnly = false;
            document.getElementById('importe3').readOnly = false;
        }
        else if((est_inm == 'SS' || est_inm == 'PC') && servicio == '20' && tarifa_reco > 0){
            //document.getElementById('monto').value = tarifa_reco;
            document.getElementById('importe1').value = tarifa_reco;
            document.getElementById('importe3').value = '';
            if(precio != ''){
                document.getElementById('procesar').style.display = 'table-cell';
            }
            else{
                document.getElementById('procesar').style.display = 'none';
            }
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
            document.getElementById('tarjeta').readOnly = false;
            document.getElementById('numcard').readOnly = false;
            document.getElementById('numaproba').readOnly = false;
            document.getElementById('importe3').readOnly = false;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio != '20'){
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('importe3').value = '';
            document.getElementById('vuelta').value = '';
            document.getElementById('tarjeta').value = 0;
            document.getElementById('numcard').value = '';
            document.getElementById('numaproba').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
            document.getElementById('tarjeta').readOnly = false;
            document.getElementById('numcard').readOnly = false;
            document.getElementById('numaproba').readOnly = false;
            document.getElementById('importe3').readOnly = false;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio == '20'){
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('tarjeta').value = 0;
            document.getElementById('numcard').value = '';
            document.getElementById('numaproba').value = '';
            document.getElementById('importe3').value = '';
            document.getElementById('concepto').value = 0;
            document.getElementById('procesar').style.display = 'none';
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble NO tiene orden de reconexi&oacute;n por pagar.<br><br>Por favor verifique.",
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
        }
    }


    if(document.getElementById('medio').value == 6){
        if((est_inm == 'SS' || est_inm == 'PC') && servicio != '20'){
			swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble tiene un pago de reconexion pendiente.<br><br>Por favor ingrese el pago.",
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
            document.getElementById('concepto').value = 0;
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
            document.getElementById('banco').value = 0;
            document.getElementById('cheque').value = '';
            document.getElementById('importe2').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('banco').readOnly = false;
            document.getElementById('cheque').readOnly = false;
            document.getElementById('importe2').readOnly = false;
        }
        else if((est_inm == 'SS' || est_inm == 'PC') && servicio == '20' && tarifa_reco > 0){
            //document.getElementById('monto').value = tarifa_reco;
            document.getElementById('importe1').value = tarifa_reco;
            document.getElementById('importe2').value = '';
            if(precio != ''){
                document.getElementById('procesar').style.display = 'table-cell';
            }
            else{
                document.getElementById('procesar').style.display = 'none';
            }
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
            //document.getElementById('importe2').value = tarifa_reco;
            //document.getElementById('procesar').style.display = 'table-cell';
            document.getElementById('banco').readOnly = false;
            document.getElementById('cheque').readOnly = false;
            document.getElementById('importe2').readOnly = true;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio != '20'){
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('banco').value = 0;
            document.getElementById('cheque').value = '';
            document.getElementById('importe2').value = '';
            document.getElementById('vuelta').value = '';
            document.getElementById('procesar').style.display = 'none';
            document.getElementById('monto').readOnly = false;
            document.getElementById('importe1').readOnly = false;
            document.getElementById('banco').readOnly = false;
            document.getElementById('cheque').readOnly = false;
            document.getElementById('importe2').readOnly = false;
        }
        else if((est_inm != 'SS' || est_inm != 'PC') && servicio == '20' && tarifa_reco > 0){
            document.getElementById('monto').value = '';
            document.getElementById('importe1').value = '';
            document.getElementById('banco').value = 0;
            document.getElementById('cheque').value = '';
            document.getElementById('importe2').value = '';
            document.getElementById('concepto').value = 0;
            document.getElementById('procesar').style.display = 'none';
            swal({
            	title: "Imposible Ingresar Recaudo",
                text: "El inmueble NO tiene orden de reconexi&oacute;n por pagar.<br><br>Por favor verifique.",
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
        }
    }

}

function recarga(){
    document.getElementById('medio').value = 0;
    document.getElementById('monto').value = '';
    document.getElementById('importe1').value = '';
    document.getElementById('banco').value = 0;
    document.getElementById('cheque').value = '';
    document.getElementById('importe2').value = '';
    document.getElementById('tarjeta').value = 0;
    document.getElementById('numcard').value = '';
    document.getElementById('numaproba').value = '';
    document.getElementById('importe3').value = '';
    document.getElementById('concepto').value = 0;
    document.pagos.submit();
}

function habframe(){
    if (document.getElementById('medio').value == 0){
        document.getElementById('procesar').style.display = 'none';
        document.getElementById('medio').value = 0;
        document.getElementById('monto').value = '';
        document.getElementById('importe1').value = '';
        document.getElementById('banco').value = 0;
        document.getElementById('cheque').value = '';
        document.getElementById('importe2').value = '';
        document.getElementById('tarjeta').value = 0;
        document.getElementById('numcard').value = '';
        document.getElementById('importe3').value = '';
        document.getElementById('numaproba').value = '';
        document.getElementById('concepto').value = 0;
    }
    else if (document.getElementById('medio').value == 1){
        if (document.getElementById('importe1').value == '' || document.getElementById('monto').value == '' || document.getElementById('concepto').value == 0){
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
        }
        document.getElementById('banco').value = 0;
        document.getElementById('cheque').value = '';
        document.getElementById('importe2').value = '';
        document.getElementById('tarjeta').value = 0;
        document.getElementById('numcard').value = '';
        document.getElementById('importe3').value = '';
        document.getElementById('numaproba').value = '';
        //document.getElementById('concepto').focus();
    }
    else if (document.getElementById('medio').value == 2 ) {
        if (document.getElementById('banco').value == 0 || document.getElementById('cheque').value == '' || document.getElementById('importe2').value == '' || document.getElementById('concepto').value == 0){
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
        }
        document.getElementById('monto').value = '';
        document.getElementById('importe1').value = '';
        document.getElementById('tarjeta').value = 0;
        document.getElementById('numcard').value = '';
        document.getElementById('importe3').value = '';
        document.getElementById('numaproba').value = '';
        //document.getElementById('concepto').value = 0;
    }
    else if (document.getElementById('medio').value == 3) {
        if (document.getElementById('tarjeta').value == 0 || document.getElementById('numcard').value == '' || document.getElementById('importe3').value == '' || document.getElementById('numaproba').value == '' || document.getElementById('concepto').value == 0){
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
        }
        document.getElementById('monto').value = '';
        document.getElementById('importe1').value = '';
        document.getElementById('banco').value = 0;
        document.getElementById('cheque').value = '';
        document.getElementById('importe2').value = '';
        //document.getElementById('concepto').value = 0;
    }
    else if (document.getElementById('medio').value == 5) {
        if (document.getElementById('importe1').value == '' || document.getElementById('monto').value == '' || document.getElementById('tarjeta').value == 0 || document.getElementById('numcard').value == '' || document.getElementById('importe3').value == '' || document.getElementById('numaproba').value == '' || document.getElementById('concepto').value == 0){
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
        }
        document.getElementById('banco').value = 0;
        document.getElementById('cheque').value = '';
        document.getElementById('importe2').value = '';
        //document.getElementById('concepto').value = 0;
    }
    else if (document.getElementById('medio').value == 6) {
        if (document.getElementById('importe1').value == '' || document.getElementById('monto').value == '' || document.getElementById('banco').value == 0 || document.getElementById('cheque').value == '' || document.getElementById('importe2').value == '' || document.getElementById('concepto').value == 0){
            document.getElementById('procesar').style.display = 'none';
        }
        else{
            document.getElementById('procesar').style.display = 'table-cell';
        }
        document.getElementById('tarjeta').value = 0;
        document.getElementById('numcard').value = '';
        document.getElementById('importe3').value = '';
        document.getElementById('numaproba').value = '';
        //document.getElementById('concepto').value = 0;
    }
}

function calculavuelto(){
    var proyecto,monto,importe1,vuelta,importe2, importe3, valfact, des_uso,concepto, servicio;
    monto = document.getElementById('monto').value;
    importe1 = document.getElementById('importe1').value;
    importe2 = document.getElementById('importe2').value;
    importe3 = document.getElementById('importe3').value;
    proyecto = document.getElementById('cod_pro').value;
    valfact = document.getElementById('valfact').value;
    des_uso = document.getElementById('des_uso').value;
    concepto = document.getElementById('concepto').value;
    servicio = concepto.split(" ",1);

    if(importe1 == '') importe1 = 0;
    if(importe2 == '') importe2 = 0;
    if(importe3 == '') importe3 = 0;

    if(proyecto == 'BC') {
        vuelta = parseFloat(monto) - parseFloat(importe1);
        document.getElementById('vuelta').value = Math.round(vuelta);
        if (parseFloat(importe1) > parseFloat(monto)) {
            document.getElementById('vuelta').value = '';
            //showDialog('Error','El importe no puede ser mayor al monto','error');
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
        }
    }

    if(proyecto == 'SD') {
        if(des_uso == 'O') {
            vuelta = parseFloat(monto) - parseFloat(importe1);
            document.getElementById('vuelta').value = Math.round(vuelta);
            if (parseFloat(importe1) > parseFloat(monto)) {
                document.getElementById('vuelta').value = '';
                //showDialog('Error','El importe no puede ser mayor al monto','error');
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
            }
        }

        if(des_uso != 'O' && servicio == 20) {
            vuelta = parseFloat(monto) - parseFloat(importe1);
            document.getElementById('vuelta').value = Math.round(vuelta);
            if (parseFloat(importe1) > parseFloat(monto)) {
                document.getElementById('vuelta').value = '';
                //showDialog('Error','El importe no puede ser mayor al monto','error');
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
            }
        }
/*************ESTA PARTE DE CODIGO SE ELIMINARA UNA VEZ ESTE SOLUCIONADO EL TEMA DE PAGOS PARCIALES******/
     /*   if(des_uso != 'O' && servicio != 20) {
            vuelta = parseFloat(monto) - parseFloat(importe1);
            document.getElementById('vuelta').value = Math.round(vuelta);
            if (parseFloat(importe1) > parseFloat(monto)) {
                document.getElementById('vuelta').value = '';
                //showDialog('Error','El importe no puede ser mayor al monto','error');
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
            }
        }*/
/////////////////////////*************************************//////////////////////////

        if(des_uso != 'O' && servicio != 20) {
            var importereal = 0, saldo, veces;
            var importe = (parseFloat(importe1) + parseFloat(importe2) + parseFloat(importe3));
            veces = (parseFloat(importe) / parseFloat(valfact));
            importereal = (parseInt(veces) * parseFloat(valfact));
            vuelta = parseFloat(monto) - parseFloat(importereal);

            if (parseFloat(importe) > parseFloat(monto)) {
                document.getElementById('importe1').value = '';
                document.getElementById('vuelta').value = '';
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

            }

            else {
                document.getElementById('vuelta').value = Math.round(vuelta);
                if (importe1 > 0) {
                    document.getElementById('importe1').value = parseInt(importereal);
                }
                if (importe2 > 0) {
                    document.getElementById('importe2').value = parseInt(importereal);
                }
                if (importe3 > 0) {
                    document.getElementById('importe3').value = parseInt(importereal);
                }
            }
        }
    }
}

$(function(){
    $('#concepto').on('change',function(){
        var id = $('#concepto').val();
        var url = 'vista.selectTarifas.php';
        $.ajax({
            type:'POST',
            url:url,
            data:'id='+id,
            success: function(data){
                $('#tarifa option').remove();
                $('#tarifa').append(data);
            }
        });
        return false;

    });

    $('#diferir').on('click',function(){
        revCorte();
    });


});



function revCorte(){
    var datos=$("#revCorForm").serializeArray();
    datos.push({name: 'tip', value: 'revOrd'});
    datos.push({name: 'observacion', value: 'reversion de corte por cobro diferido'});
    datos.push({name: 'inmueble', value: $("#cod_inmueble").val()});
    $.ajax
    ({
        url : '../../cortes/datos/datos.revcorte.php',
        type : 'POST',
        dataType : 'json',
        data : datos ,
        success : function(json) {

            if(json){
                if(json["res"]=="true"){
                    swal({
                            title: "Mensaje",
                            text: "Has reversado exitosamente la orden",
                            type: "success"},
                        function(isConfirm){
                            if (isConfirm) {
                                addDif();

                            }
                        });
                }else if(json["res"]=="false"){
                    swal({
                            title: "Mensaje",
                            text: "error "+json['error'],
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                            }
                        });

                }


            }

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}


function addDif(){
    var datos=$("#revCorForm").serializeArray();
    datos.push({name: 'tip', value: 'gendif'});
    datos.push({name: 'reconexion',  value: $("#tarifarec").val()});
    datos.push({name: 'inmueble', value: $("#cod_inmueble").val()});
    $.ajax
    ({
        url : '../../cortes/datos/datos.revcorte.php',
        type : 'POST',
        dataType : 'json',
        data : datos ,
        success : function(json) {

            if(json){
                if(json["res"]=="true"){
                    swal({
                            title: "Mensaje",
                            text: "Has reversado exitosamente la orden",
                            type: "success"},
                        function(isConfirm){
                            if (isConfirm) {
                                window.location='vista.tipopagos.php'
                            }
                        });
                }else if(json["res"]=="false"){
                    swal({
                            title: "Mensaje",
                            text: "error "+json['error'],
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                            }
                        });

                }


            }

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}
