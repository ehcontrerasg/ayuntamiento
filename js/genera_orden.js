/**
 * Created by PC on 7/7/2016.
 */
var genOrdSelAcu;
var genOrdInpCodSis;
var genOrdInpProIni;
var genOrdInpProFin;
var genOrdInpManIni;
var genOrdInpManFin;
var genOrdButPro;
var genOrdSelMed;
var genOrdSelEstInm;

function complementaInpProFin() {
    var faltante =11-genOrdInpProIni.value.length;
    genOrdInpProFin.value=(genOrdInpProIni.value+faltanteFunc('9',faltante)).substr(0,11);
    genOrdInpProIni.value= (genOrdInpProIni.value+faltanteFunc('0',faltante)).substr(0,11);
}

function complementaInpProFin2() {
    var faltante =11-genOrdInpProFin.value.length;
    genOrdInpProFin.value=(genOrdInpProFin.value+faltanteFunc('9',faltante)).substr(0,11);

}

function completaManzFin() {
    genOrdInpManFin.value=genOrdInpManIni.value;
}
function genOrdInicio(){

    genOrdSelAcu     = document.getElementById("genOrdSelPro");
    genOrdInpCodSis  = document.getElementById("genOrdInpCodSis");
    genOrdInpProIni  = document.getElementById("genOrdInpProIni");
    genOrdInpProFin  = document.getElementById("genOrdInpProFin");
    genOrdInpManIni  = document.getElementById("genOrdInpManIni");
    genOrdInpManFin  = document.getElementById("genOrdInpManFin");
    genOrdButPro     = document.getElementById("genOrdButPro");
    genOrdSelMed     = document.getElementById("genOrdSelMed");
    genOrdSelEstInm  = document.getElementById("genOrdSelEstInm");


    genOrdLleSelPro();
    genOrdInpProIni.addEventListener("blur",complementaInpProFin);
    genOrdInpProFin.addEventListener("blur",complementaInpProFin2);
    genOrdInpManIni.addEventListener("blur",completaManzFin);

    genOrdButPro.addEventListener("click",genOrdSes);



}

function faltanteFunc(constante,numero){

    var res="";
    for(x=1;x<=numero;x++){
        res +=""+constante ;
    }
    return res;
}


function flexyGenOrd(){

    var proyecto=genOrdSelAcu.value, proini=genOrdInpProIni.value,profin=genOrdInpProFin.value,
        codsis=genOrdInpCodSis.value, manini=genOrdInpManIni.value, manfin=genOrdInpManFin.value,
        medido=genOrdSelMed.value,estado=genOrdSelEstInm.value;

    var parametros = {
        "proyecto" : proyecto,
        "proini" :   proini,
        "profin" :   profin,
        "codsis" :   codsis,
        "manini" :   manini,
        "manfin" :   manfin,
        "medido" :   medido,
        "estado" :   estado,
        "tip"    :   "sess"
    };

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexGeneraOrd").flexigrid	(
        {

            url: './../datos/datos.genera_orden.php',
            dataType: 'json',
            type:  'post',
            params: { sess:'qfilte'},
            colModel : [
                {display: 'Zona', name: 'rnum', width:30,  align: 'center'},
                {display: 'Estado', name: 'VALOR_DIFERIDO', width: 90, sortable: true, align: 'center'},
                {display: 'Cod. Sistma', name: 'RANGO', width: 100, sortable: true, align: 'center'},
                {display: 'Direccion', name: 'CUTAS_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Medidor', name: 'VALOR_PAG', width: 100, sortable: true, align: 'center'},
                {display: 'Serial', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'},
                {display: 'Calibre', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'},
                {display: 'Fecha Alta', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'},
                {display: 'Fecha Baja', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'},
                {display: 'Proyecto', name: 'VALOR_PEND', width: 100, sortable: true, align: 'center'}
            ],
            proyecto : proyecto,
            qtype: "qweqwe",
            sortname: "TOTAL_DIFERIDO",
            sortorder: "ASC",
            usepager: false,
            title: 'Inmuebles generacion de cambio medidor',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 640,
            height: 50
        }
    );
}








function genOrdSes(){

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var datos=xmlhttp.responseText;
                if(datos=="true"){
                    if(validaCampos()){
                        swal({
                                title: "Mensaje",
                                text: "Desea Generar las ordenes con los datos ingresados",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Si!",
                                cancelButtonText: "No!",
                                showLoaderOnConfirm: true,
                                closeOnConfirm: true,
                                closeOnCancel: true },
                            function(isConfirm){
                                if (isConfirm) {
                                    flexyGenOrd();
                                }
                            });



                    }
                }else{
                    swal({
                            title: "Mensaje!",
                            text: "Su sesion ha finalizado.",
                            showConfirmButton: true },
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
        xmlhttp.open("POST", "../datos/datos.genera_orden.php", true);   // async
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("tip=sess");

}

function genOrdLleSelPro(){
    var select= genOrdSelAcu;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

}




function validaCampos(){
    if(genOrdSelAcu.selectedIndex==0){
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    if(genOrdInpCodSis.value.trim()=="" && (genOrdInpProIni.value.trim()=="" || genOrdInpProFin.value.trim()=="")){
        swal("Error!", "Debe seleccionar un codigo de sistema o un rango de procesos", "error");
        return false;
    }

    if(genOrdInpManIni.value!="" && genOrdInpManIni.value.length<3 ){
        swal("Error!", "Manzana inicial incorrecta", "error");
        return false;
    }

    if(genOrdInpManFin.value!="" && genOrdInpManFin.value.length<3 ){
        swal("Error!", "Manzana final incorrecta", "error");
        return false;
    }

    return true;
}



