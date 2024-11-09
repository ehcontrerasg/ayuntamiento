/**
 * Created by PC on 6/14/2016.
 */

var inpInm;
var tabflex;
var filflex;
var codpago;
var lbFormPag;

function inicio(){
    lbFormPag=document.getElementById("lbfrmpago");
}

function asigeven() {
    tabflex = document.getElementById("flex6");
    filflex = tabflex.getElementsByTagName("tr");
    for (i = 0; i < filflex.length; i++) {
        if(filflex[i]){
            var currentRow = filflex[i];
            currentRow.addEventListener("click",evento);
        }

    }
}

function flexy(){
    inpInm=document.getElementById("inm").value;
    //alert(inpInm);
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex6").flexigrid	(
        {
            url: './../datos/datos.pagos.php?inmueble='+inpInm,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Cod. pago', name: 'ID_PAGO', width: 59, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA_PAGO', width: 48, sortable: true, align: 'center'},
                {display: 'Referencia', name: 'REFERENCIA', width: 371, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 32, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_PAGO",
            sortorder: "desc",
            usepager: false,
            //title: 'facturas',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            onSuccess: function(){asigeven()},
            //width: 686,
            height: 180
        }
    );
    //inicio();
}


function evento(){
    codpago=this.getAttribute("id").replace("row","");
    detallespago();
}


function detallespago(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++)
            {
                lbFormPag.value=lbFormPag.value+','+datos[x]["DESCRIPCION"];
            }
        }
        else if(xmlhttp.readyState == 1){
           // spinner = new Spinner(opts).spin(formRealizaRes);
           // formRealizaRes.appendChild(spinner.el);
        }
    }
    xmlhttp.open("POST", "../datos/datos.detpag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=tipPago&pag="+codpago);


}
