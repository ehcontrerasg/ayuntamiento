/**
 * Created by PC on 6/14/2016.
 */

var inpInmOtrosRec;
var tabflexOtrosRec;
var filflexOtrosRec;
var codpagoOtrosRec;
var lbFormPagOtrosRec;
var lbEntPagOtrosRec;
var lbPuntPagOtrosRec;
var lbCajPagOtrosRec;


function inicioOtrosRec(){
    inpInmOtrosRec=document.getElementById("inmOtrosRec").value;
    lbFormPagOtrosRec=document.getElementById("DatOtrReclbfrmpago");
    lbEntPagOtrosRec=document.getElementById("DatOtrReclbentidad");
    lbPuntPagOtrosRec=document.getElementById("DatOtrReclbpunto");
    lbCajPagOtrosRec=document.getElementById("DatOtrReclbcaja");
}

function asigevenOtrosRec() {
    tabflexOtrosRec = document.getElementById("flexOtroRec");
    filflexOtrosRec = tabflexOtrosRec.getElementsByTagName("tr");
    for (i = 0; i < filflexOtrosRec.length; i++) {
        if(filflexOtrosRec[i]){
            var currentRow = filflexOtrosRec[i];
            currentRow.addEventListener("click",eventoOtrosRec);
        }

    }
}

function flexyOtrosRec(){
    //alert(inpInm);
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexOtroRec").flexigrid	(
        {
            url: './../datos/datos.flexyOtrosRec.php?inmueble='+inpInmOtrosRec,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:20,  align: 'center'},
                {display: 'Codigo', name: 'CODIGO', width: 50, sortable: true, align: 'center'},
                {display: 'Fecha Dig.', name: 'FECHADIG', width: 85, sortable: true, align: 'center'},
                {display: 'Fecha Pago.', name: 'FECHAPAG', width: 85, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'DESC_SERVICIO', width: 200, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
                {display: 'Ingreso', name: 'LOGIN', width: 130, sortable: true, align: 'center'},
                {display: 'Fecha anula.', name: 'FECHA_REV', width: 85, sortable: true, align: 'center'},
                {display: 'Usuario anula.', name: 'USRREV', width: 130, sortable: true, align: 'center'},
            ],

            sortname: "ORE.FECHA",
            sortorder: "desc",
            usepager: false,
            title: 'Otros Recaudos',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            onSuccess: function(){asigevenOtrosRec()},
            //width: 686,
            height: 180
        }
    );
    //inicio();
}



function eventoOtrosRec(){

    codpagoOtrosRec=this.getAttribute("id").replace("row","");
    formpagoOtrosRec();
    entpagoOtrosRec();
}


function formpagoOtrosRec(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            var datos=obj = JSON.parse(xmlhttp.responseText);
            lbFormPagOtrosRec.innerHTML="";

            for(var x=0;x<datos.length;x++)
            {

                if(x==0){
                    lbFormPagOtrosRec.innerHTML=datos[x]["DESCRIPCION"];
                }else{
                    lbFormPagOtrosRec.innerHTML=lbFormPagOtrosRec.textContent+','+datos[x]["DESCRIPCION"];
                }
            }
        }
        else if(xmlhttp.readyState == 1){
           // spinner = new Spinner(opts).spin(formRealizaRes);
           // formRealizaRes.appendChild(spinner.el);
        }
    }
    xmlhttp.open("POST", "../datos/datos.detOtrRec.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=tipPago&pag="+codpagoOtrosRec);


}


function entpagoOtrosRec(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=obj = JSON.parse(xmlhttp.responseText);

            lbEntPagOtrosRec.innerHTML="";
            lbPuntPagOtrosRec.innerHTML="";
            lbCajPagOtrosRec.innerHTML="";

            for(var x=0;x<datos.length;x++)
            {
                if(x==0){
                    lbEntPagOtrosRec.innerHTML=datos[x]["DESC_ENTIDAD"];
                    lbPuntPagOtrosRec.innerHTML=datos[x]["DESC_PUNTO"];
                    lbCajPagOtrosRec.innerHTML=datos[x]["DESC_CAJA"];

                }else{

                    lbEntPagOtrosRec.innerHTML=lbEntPagOtrosRec.textContent+','+datos[x]["DESC_ENTIDAD"];
                    lbPuntPagOtrosRec.innerHTML=lbPuntPagOtrosRec.textContent+','+datos[x]["DESC_PUNTO"];
                    lbCajPagOtrosRec.innerHTML=lbCajPagOtrosRec.textContent+','+datos[x]["DESC_CAJA"];

                }
            }
        }
        else if(xmlhttp.readyState == 1){
            // spinner = new Spinner(opts).spin(formRealizaRes);
            // formRealizaRes.appendChild(spinner.el);
        }
    }
    xmlhttp.open("POST", "../datos/datos.detOtrRec.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ubPago&pag="+codpagoOtrosRec);
}



