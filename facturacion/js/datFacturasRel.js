// JavaScript Document
var inpdatfacinm;
//var cod_inmueble;
function inicioFacturasRel(){
	inpdatfacinm=document.getElementById("inpdatfacinm");
	//cod_inmueble=document.getElementById("cod_inmueble");
	//cod_inmueble.addEventListener("blur",RecargaFlexFacRel);
}

/*function RecargaFlexFacRel(){
	inmueble=inpdatfacinm.getAttribute("value").replace("row","");
	//$("#flexfactreliquida").flexOptions({url: './../datos/datos.facturasPendientes.php?inmueble='+inmueble});
    //$("#flexfactreliquida").flexReload();	
	alert(inmueble);
}*/

function asigevenFacturaRel() {
    tabflexFac = document.getElementById("flexfactreliquida");
    filflexFac = tabflexFac.getElementsByTagName("tr");
    for (i = 0; i < filflexFac.length; i++) {
        if(filflexFac[i]){
            var currentRow = filflexFac[i];
            currentRow.addEventListener("click",eventoFacturaRel);
        }

    }
}

function eventoFacturaRel(){
	//var valorrdbutton=getRadioButtonSelectedValue(rbutDatFacTipoFac);

    factura=this.getAttribute("id").replace("row","");
	//alert(factura);
	/* if(valorrdbutton=='M'){
        objPdf.data="../clases/classFacturaPdf.php?factura="+factura;
    }else{
        objPdf.data="../clases/classFacturaPdf2.php?factura="+factura;
    }*/
  	flexyestconcepto();
    flexydetfactura();
}

function flexyFacRel(){
	 var inmueble=inpdatfacinm.value;
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexfactreliquida").flexigrid	(
        {

            url: './../datos/datos.facturasPendientes.php?inmueble='+inmueble,

            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:30,  align: 'center'},
                {display: 'Consecutivo<br/>Factura', name: 'CONSEC_FACTURA', width: 80, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'Periodo', width: 50, sortable: true, align: 'center'},
                {display: 'Fecha<br/>Expedicion', name: 'FEC_EXPEDICION', width: 80, sortable: true, align: 'center'},
                {display: 'Nfc', name: 'NCF', width: 130, sortable: true, align: 'center'},
                {display: 'Valor', name: 'TOTAL', width: 70, sortable: true, align: 'center'}
            ],
            buttons: [
                {name:'Reliquidar a cero', bclass:'edit', onpress: test},
                {separator: true},
                {name:'Reliquidar X Concepto', bclass:'edit', onpress: test},
                {separator: true}
            ],



            sortname: "PERIODO",
            sortorder: "desc",
            usepager: false,
            //title: 'Listado Facturas Pendientes',
			onSuccess: function(){asigevenFacturaRel()},
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 1120,
            height: 270
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
            width: 560,
            height: 170
        }
    );
	$("#flexdatdetfactura").flexOptions({url: './../datos/datos.detfactpend.php?factura='+factura});
    $("#flexdatdetfactura").flexReload();
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
            width: 560,
            height: 170
        }
    );
	$("#flexdatestconcepto").flexOptions({url: './../datos/datos.estadoconcepto.php?inmueble='+inmueble});
    $("#flexdatestconcepto").flexReload();
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


    //-->



    function Relporconcepto() {
			var inmueble=inpdatfacinm.value;
        popup("vista.rel_con_per2.php?codinmueble="+inmueble,770,610,'yes');
    }

    function Relcero() {
		var inmueble=inpdatfacinm.value;
        popup("vista.rel_tot2.php?codinmueble="+inmueble,770,610,'yes');
    }


    function test(com,grid)
    {

        if (com=='Reliquidar X Concepto')
        {
            //alert('Add New Item Action');
            Relporconcepto();

        }

        if (com=='Reliquidar a cero')
        {
            Relcero();
        }




    }

