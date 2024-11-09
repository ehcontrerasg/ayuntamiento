// JavaScript Document
var inpdatfacinm;
var diferido;
var inpNumDifRev;
var botonRevDiferido;
var txtObsRevDif;

function inicioInmueblesDiferido(){
	inpdatfacinm=document.getElementById("inpdatfacinm");
}

function inicioDiferidoRev(){
	diferido = getUrlVars()["diferido"];
	inpNumDifRev=document.getElementById("numDifRev");
	inpNumDifRev.value = diferido;
	botonRevDiferido = document.getElementById("botonRevDiferido");
	txtObsRevDif=document.getElementById("txtObsRevDif");
	botonRevDiferido.addEventListener("click",verificaSession);
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function verificaSession(){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var datos=xmlhttp.responseText;
                if(datos=="true"){
                    if(validaCampos()){
                        swal({
                                title: "Advertencia",
                                text: "Desea reversar el diferido",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Si!",
                                cancelButtonText: "No!",
                                closeOnConfirm: false,
                                closeOnCancel: true },
                            function(isConfirm){
                                if (isConfirm) {
                                    alerta("entro");
									revDif();
                                }
                            });

                    }
                }else{
                    swal({
                            title: "Mensaje!",
                            text: "Su sesion ha finalizado.\n Por favor vuelva a loguearse",
                            showConfirmButton: true },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
								//top.location.replace("../../index.php");
                                window.close(this); 
                            }
                        }
                    );
                    return false;
                }
            }
        }
        xmlhttp.open("POST", "../datos/datos.diferidos.php", true);   // async
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("tip=sess");

}


function revDif(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=JSON.parse(xmlhttp.responseText);
            if (datos["res"]=="true"){
                swal({
					title: "Mensaje!", 
					text: "Se ha reversado el diferido "+inpNumDifRev.value, 
					type: "success",
					confirmButtonText: "Aceptar",
					closeOnConfirm: true
					},
					function(){
                    	window.close(this);
					});
            }else if(datos["res"]=="false"){
                swal({
                    title: "Error",
                    text: "No se pudo reversar el diferido. \n"+datos["error"],
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "No!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    closeOnCancel: true 
					},
					function(){
                    	window.close(this);
					}
				);
            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.diferidos.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=reversaDif&diferido="+inpNumDifRev.value+"&motivo="+txtObsRevDif.value);
}

function validaCampos(){
   
    if(txtObsRevDif.value.trim()==""){
        swal("Error!", "Ingrese una observaci\u00f3n para la reversi\u00f3n", "error");
        return false;
    }

    return true;
}


function asigevenDiferidoRev() {
    tabflexFac = document.getElementById("flexdatdiferidos");
    filflexFac = tabflexFac.getElementsByTagName("tr");
    for (i = 0; i < filflexFac.length; i++) {
        if(filflexFac[i]){
            var currentRow = filflexFac[i];
            currentRow.addEventListener("click",eventoDiferidoRev);
        }

    }
}

function eventoDiferidoRev(){
    diferido=this.getAttribute("id").replace("row","");
}

function flexyInmDif(){
	var inmueble=inpdatfacinm.value;
 	$('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexinmueblediferido").flexigrid	(
        {
		url: './../datos/datos.listado_inmuebles.php?codinmueble='+inmueble,
        dataType: 'json',
        colModel : [
			{display: 'N&deg;', name: 'rnum', width:20,  align: 'center'},
			{display: 'Acueducto', name: 'ID_PROYECTO', width: 60, sortable: true, align: 'center'},
			{display: 'Cod<br>Sistema', name: 'codigo_inm', width: 60, sortable: true, align: 'center'},
			{display: 'Zona', name: 'ID_ZONA', width: 35, sortable: true, align: 'center'},
			{display: 'Urbanización', name: 'DESC_URBANIZACION', width: 82, sortable: true, align: 'center'},
			{display: 'Dirección', name: 'DIRECCION', width: 135, sortable: true, align: 'center'},
			{display: 'Estado', name: 'ID_ESTADO', width: 50, sortable: true, align: 'center'},
			{display: 'Catastro', name: 'CATASTRO', width: 120, sortable: true, align: 'center'},
			{display: 'Proceso', name: 'ID_PROCESO', width: 75, sortable: true, align: 'center'},
			{display: 'Cliente', name: 'CODIGO_CLI', width: 50, sortable: true, align: 'center'},
			{display: 'Nombre', name: 'ALIAS', width: 260, sortable: true, align: 'center'},
			{display: 'Cédula', name: 'DOCUMENTO', width: 85, sortable: true, align: 'center'}
		],
		buttons: [
        	{name:'Crear Diferido', bclass:'add', onpress: test},
            {separator: true},
			{name:'Crear Acuerdo de Pago', bclass:'add', onpress: test},

        ],
		
        sortname: "ID_SECTOR, ID_ZONA, CODIGO_INM",
        sortorder: "ASC",
		//onSuccess: function(){asigevenFacturaRel()},
        useRp: false,
        rp: 500,
        page: 1,
		width: 1120,
        height: 40,
        }
    );
}

function flexydiferidos(){
	var inmueble=inpdatfacinm.value;
	$('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatdiferidos").flexigrid	(
        {
            url: './../datos/datos.detdif.php?inmueble='+inmueble,
            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:30,  align: 'center'},
				{display: 'C&oacute;digo<br>Diferido', name: 'codigo', width: 90, sortable: true, align: 'center'},
				{display: 'Concepto', name: 'desc_servicio', width: 120, sortable: true, align: 'center'},
                {display: 'Valor<br>Diferido', name: 'VALOR_DIFERIDO', width: 90, sortable: true, align: 'center'},
                {display: 'Total<br>Cuotas', name: 'RANGO', width: 100, sortable: true, align: 'center'},
				{display: 'Valor<br>Cuota', name: 'VALOR_CUOTA', width: 100, sortable: true, align: 'center'},
                {display: 'Cuotas<br>Pagadas', name: 'CUTAS_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor<br>Pagado', name: 'VALOR_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor<br>Pendiente', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'}
            ],
			buttons: [
        		{name:'Reversar Diferido', bclass:'delete', onpress: test},
            	{separator: true}
        	],
            sortname: "numero_cuotas",
            sortorder: "ASC",
            usepager: false,
			onSuccess: function(){asigevenDiferidoRev()},
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 1120,
            height: 250
        }
    );
	$("#flexdatdiferidos").flexOptions({url: './../datos/datos.detdif.php?inmueble='+inmueble});
    $("#flexdatdiferidos").flexReload();
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

function CreaDiferido() {
	var inmueble=inpdatfacinm.value;
    popup("vista.diferido2.php?codinmueble="+inmueble,500,400,'yes');
}

function CreaAcuerdoPago() {
	var inmueble=inpdatfacinm.value;
    popup("vista.diferido.php?codinmueble="+inmueble,930,450,'yes');
}

function CreaPlanDeudaCero() {
    var inmueble=inpdatfacinm.value;
    popup("vista.creadeudacero.php?codinmueble="+inmueble,930,450,'yes');
}

function ReversaDiferido(diferido) {
	var inmueble=inpdatfacinm.value;
    popup("vista.reversadiferido.php?diferido="+diferido+"&inmueble="+inmueble,600,450,'yes');
}

function test(com,grid){
	if (com=='Crear Diferido'){
    	CreaDiferido();
	}
	if (com=='Crear Acuerdo de Pago'){
    	CreaAcuerdoPago();
	}
	if (com=='Reversar Diferido'){
		if($('.trSelected',grid).length>0){
			ReversaDiferido(diferido);
		}
		else{
			alerta('Reversi\u00f3n de Diferidos','Por favor seleccione el diferido a reversar','info','',true,'Aceptar','',false,'');
		}
	}
    if (com =='Crear Deuda Cero') {
        CreaPlanDeudaCero();
    }
}

function alerta(titulo,texto,tipo,colorBotonConfirma,muestraBotonConfirma,textoBotonConfirma,colorBotonCancel,muestraBotonCancel,textoBotonCancel){
	swal({   
		title: titulo,  
		text: texto,   
		type: tipo, 	
		confirmButtonColor: colorBotonConfirma,   
		showConfirmButton: muestraBotonConfirma,
		confirmButtonText: textoBotonConfirma ,
		cancelButtonColor: colorBotonCancel,   
		showCancelButton: muestraBotonCancel,   
		cancelButtonText: textoBotonCancel
	}); 
}

function flexydeudatotal(){
	var inmueble=inpdatfacinm.value;
	$('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexDeudaTotal").flexigrid	(
    {
    	url: './../datos/datos.deudatotalpendiente.php?codinmueble='+inmueble,
        dataType: 'json',
        colModel : [
        	{display: 'Cantidad<br>Facturas', name: 'NUMFAC', width: 50, sortable: true, align: 'center'},
			{display: 'Periodo<br>Inicial', name: 'PERINI', width: 55, sortable: true, align: 'center'},
				{display: 'Periodo<br>Final', name: 'PERFIN', width: 55, sortable: true, align: 'center'},
                {display: 'Total', name: 'VALOR', width: 80, sortable: true, align: 'center'}
            ],
            usepager: false,
            title: 'Total Deuda',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 311,
            height: 33
        }
    );
}

function calculacuota(){
		var numero1,numero2, numero3;
		numero1 = document.getElementById('valfinancia').value;
	  	numero2 = document.getElementById('numcuotas').value;
	  	numero3 = parseFloat(numero1) / parseFloat(numero2);
	  	document.getElementById('valcuotas').value = Math.round(numero3);
	}

function facpenddeuda(){
	var inmueble=inpdatfacinm.value;
	$('.flexme1').flexigrid();
	$('.flexme2').flexigrid({height:'auto',striped:false});
	$("#flexyfacpendiente").flexigrid	(
		{
          url: './../datos/datos.facturasPendientesDeuda.php?codinmueble='+inmueble,
          dataType: 'json',
          colModel : [
               {display: 'Item', name: 'rnum', width:18,  align: 'center'},
               {display: 'N&deg; Factura', name: 'CONSEC_FACTURA', width: 65, sortable: true, align: 'center'},
               {display: 'Periodo', name: 'Periodo', width: 47, sortable: true, align: 'center'},
               {display: 'Expedición', name: 'FEC_EXPEDICION', width: 60, sortable: true, align: 'center'},
               {display: 'Vencimiento', name: 'NCF', width: 60, sortable: true, align: 'center'},
               {display: 'Total', name: 'TOTAL', width: 70, sortable: true, align: 'center'}
          ],
          sortname: "PERIODO",
          sortorder: "asc",
          usepager: false,
          title: 'Facturas Pendientes Por Pagar',
		  nSuccess: function(){asigevenFacturaRel()},
          useRp: false,
          rp: 1000,
          page: 1,
          showTableToggleBtn: false,
          width: 480,
          height: 252
		}
	);
}