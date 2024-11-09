/**
 * Created by PC on 6/14/2016.
 */

var rbutDatFacTipoFac;
var inpdatfacfac;
var ifdatfacimpfac;
var inpdatfacinm;
var factura;
var objPdf;
var linkEstPorConc;
var linkEstPorCuent;

function inicioFacturas(){
	objPdf=document.getElementById("ifpdf");
    inpdatfacinm=document.getElementById("inpdatfacinm");
    rbutDatFacTipoFac=document.getElementsByName("rbfactipofac");
    inpdatfacfac=document.getElementById("inpdatfaccodfac");
    ifdatfacimpfac=document.getElementById("ifpdf");
    linkEstPorConc=document.getElementById("linkImpEstConcepto");
    linkEstPorConc.href="../datos/datos.RepEstCon.php?tip=rep&inmueble="+inpdatfacinm.value;
    linkEstPorCuent=document.getElementById("linkImpEstCuenta");
    linkEstPorCuent.href="../datos/datos.RepEstCuen.php?tip=rep&inmueble="+inpdatfacinm.value;
    linkEstPorConc.addEventListener("click",popupEstConc);
    linkEstPorCuent.addEventListener("click",popupEstcuneta);

}


function  popupEstConc(){
    window.open(this.href, this.target, 'width=300,height=400');
    return false;
}


function  popupEstcuneta(){
    window.open(this.href, this.target, 'width=300,height=400');
    return false;
}


function recargaifr(){

    var valorrdbutton=getRadioButtonSelectedValue(rbutDatFacTipoFac);
    if(valorrdbutton=='M'){
        ifdatfacimpfac.data="../clases/classFacturaPdf.php?factura="+factura;
    }else{
        ifdatfacimpfac.data="../clases/classFacturaPdf2.php?factura="+factura;
    }

}



function getRadioButtonSelectedValue(ctrl)
{
    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) return ctrl[i].value;
}


function rel(id1) { // Traer la fila seleccionada
    popup("vista.facturarel.php?factura="+id1,600,400,'yes');
}


function asigevenFactura() {
    tabflexFac = document.getElementById("flexfacturas");
    filflexFac = tabflexFac.getElementsByTagName("tr");
    for (i = 0; i < filflexFac.length; i++) {
        if(filflexFac[i]){
            var currentRow = filflexFac[i];
            currentRow.addEventListener("click",eventoFactura);
        }

    }
}

function eventoFactura(){
	var valorrdbutton=getRadioButtonSelectedValue(rbutDatFacTipoFac);

    factura=this.getAttribute("id").replace("row","");
	if(valorrdbutton=='M'){
    	objPdf.data="../clases/classFacturaPdf.php?factura="+factura;
    }else{
    	objPdf.data="../clases/classFacturaPdf2.php?factura="+factura;
    }
   
    flexydetfactura();
	flexydetlectura();
	//flexyestconcepto();
}

function flexyfacturas(){
    var inmueble=inpdatfacinm.value;
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexfacturas").flexigrid	(
        {
            url: './../datos/datos.facturas.php?inmueble='+inmueble,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:20,  align: 'center'},
                {display: 'Consec<br/>Factura', name: 'CONSEC_FACTURA', width: 55, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'Periodo', width: 44, sortable: true, align: 'center'},
                {display: 'Fecha<br/>Lectura', name: 'FEC_LECT', width: 60, sortable: true, align: 'center'},
                {display: 'Consumo<br/>Fact', name: 'LECTURA', width: 54, sortable: true, align: 'center'},
                {display: 'Fecha<br/>Expedicion', name: 'FEC_EXPEDICION', width: 60, sortable: true, align: 'center'},
                {display: 'Nfc', name: 'NCF', width: 120, sortable: true, align: 'center'},
                {display: 'Valor', name: 'TOTAL', width: 35, sortable: true, align: 'center'},
                {display: 'Pagado', name: 'TOTAL_PAGADO', width: 35, sortable: true, align: 'center'},
                {display: 'Fecha <br/> pago', name: 'FECHA_PAGO', width: 60, sortable: true, align: 'center'},
                {display: 'Dias', name: 'Dias', width: 30, sortable: true, align: 'center'},
                {display: 'Reliquida', name: 'ANTERIORES', width: 40, sortable: true, align: 'center'}
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
            width: 650,
            height: 235
        }
    );
}

function flexydetfactura(){
   // var inmueble=inpdatfacinm.value;
	 $('.flexme1').flexigrid();
     $('.flexme2').flexigrid({height:'auto',striped:false});
     $("#flexdatdetfactura").flexigrid	(
       {
            url: './../datos/datos.detfactpend.php?factura='+factura,
            dataType: 'json',
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
            width: 426,
            height: 170
        }
    );
	$("#flexdatdetfactura").flexOptions({url: './../datos/datos.detfactpend.php?factura='+factura});
    $("#flexdatdetfactura").flexReload();
}

function flexydetlectura(){
	$('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatdetlectura").flexigrid	(
        {

            url: './../datos/datos.totalfactpend.php?factura='+factura,
            dataType: 'json',
            colModel : [
                {display: 'Lectura', name: 'LECTURA', width:60,  align: 'center'},
                {display: 'Observaci&oacute;n', name: 'OBSERVACION', width: 90, sortable: true, align: 'center'},
                {display: 'Lector', name: 'LECTOR', width: 120, sortable: true, align: 'center'},
                {display: 'Consumo', name: 'CONSUMO', width: 60, sortable: true, align: 'center'}
            ],

            sortname: "LOGIN",
            sortorder: "ASC",
            usepager: false,
            title: 'Detalle Lectura y Entrega de Factura',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 426,
            height: 170
        }
    );
	$("#flexdatdetlectura").flexOptions({url: './../datos/datos.totalfactpend.php?factura='+factura});
    $("#flexdatdetlectura").flexReload();
}


function flexyestconcepto(){
	var inmueble=inpdatfacinm.value;
	$('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatestconcepto").flexigrid	( 
    {

            url: './../datos/datos.estadoconcepto.php?inmueble='+inmueble,
            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:64,  align: 'center'},
                {display: 'Concepto', name: 'CONCEPTO', width: 120, sortable: true, align: 'center'},
                {display: 'N&deg; Facturas', name: 'NUMFAC', width: 120, sortable: true, align: 'center'},
                {display: 'Valor', name: 'VALOR', width: 70, sortable: true, align: 'center'}
            ],

            usepager: false,
            title: 'Estado Por Concepto',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 428,
            height: 170
        }
    );
	$("#flexdatestconcepto").flexOptions({url: './../datos/datos.estadoconcepto.php?inmueble='+inmueble});
    $("#flexdatestconcepto").flexReload();
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
                {display: 'Valor Diferido', name: 'VALOR_DIFERIDO', width: 90, sortable: true, align: 'center'},
                {display: 'Total Cuotas', name: 'RANGO', width: 100, sortable: true, align: 'center'},
                {display: 'Cuotas Pagadas', name: 'CUTAS_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Pagado', name: 'VALOR_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Pendiente', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'}
            ],

            sortname: "numero_cuotas",
            sortorder: "ASC",
            usepager: false,
            title: 'Diferidos Inmueble '+inmueble,
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 640,
            height: 50
        }
    );
	//$("#flexdatdiferidos").flexOptions({url: './../datos/datos.detdif.php?inmueble='+inmueble});
    //$("#flexdatdiferidos").flexReload();
}


function flexypdc(){
	var inmueble=inpdatfacinm.value;
	$('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatpdc").flexigrid	(
        {

            url: './../datos/datos.detdeudacero.php?inmueble='+inmueble,
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:30,  align: 'center'},
                {display: 'Valor PDC', name: 'VALOR_DIFERIDO', width: 90, sortable: true, align: 'center'},
                {display: 'Total Cuotas', name: 'RANGO', width: 100, sortable: true, align: 'center'},
                {display: 'Cuotas Pagadas', name: 'CUTAS_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Pagado', name: 'VALOR_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Valor Pendiente', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'}
            ],

            sortname: "TOTAL_DIFERIDO",
            sortorder: "ASC",
            usepager: false,
            title: 'Plan Deuda Cero Inmueble '+inmueble,
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 640,
            height: 50
        }
    );
}
function flexyestcuenta(){
    var inmueble=inpdatfacinm.value;
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdatfacestcuen").flexigrid	(
        {
            url: './../datos/datos.flexyEstCuenta.php?inmueble='+inmueble,

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
            title: 'Estado de cuenta Inmueble '+inmueble,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 640,
            height: 150
        }
    );
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


