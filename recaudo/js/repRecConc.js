/**
 * Created by PC on 6/23/2016.
 */
var selRepConAcu;
var selRepConCon;
var inpRepConIniEnt;
var inpRepConFinEnt;
var inpRepConIniPun;
var inpRepConFinPun;
var inpRepConIniCaj;
var inpRepConFinCaj;
var inpRepConIniFec;
var inpRepConFinFec;
var butRepConButGen;
var divRepPagConcRep;

function repRecConInicio(){
    selRepConAcu = document.getElementById("selRepPagConcAcu");
    selRepConCon = document.getElementById("selRepPagConcConc");
    inpRepConIniEnt = document.getElementById("inpRepPagConcIniEnt");
    inpRepConFinEnt = document.getElementById("inpRepPagConcFinEnt");
    inpRepConIniPun = document.getElementById("inpRepPagConcIniPun");
    inpRepConFinPun = document.getElementById("inpRepPagConcFinPun");
    inpRepConIniCaj = document.getElementById("inpRepPagConcIniCaj");
    inpRepConFinCaj = document.getElementById("inpRepPagConcFinCaj");
    inpRepConIniFec = document.getElementById("inpRepPagConcIniFec");
    inpRepConFinFec = document.getElementById("inpRepPagConcFinFec");
    butRepConButGen = document.getElementById("butRepPagConcFinGenRep");
    divRepPagConcRep = document.getElementById("divRepPagConcRep");

    butRepConButGen.addEventListener("click",repRecConLlegaGenRep);
    repRecConLlegaSelAcu();
    repRecConLlegaSelCon();

}

function repRecConLlegaSelAcu(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selRepConAcu.add(option,selRepConAcu[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                selRepConAcu.add(option,selRepConAcu[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.repPagConc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=acu");

}

function repRecConLlegaSelCon(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selRepConCon.add(option,selRepConCon[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["COD_SERVICIO"]);
                option.text=datos[x]["DESC_SERVICIO"];
                selRepConCon.add(option,selRepConCon[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.repPagConc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=con");

}

function repRecConLlegaGenRep(){
    if(validaCampos()) {
        var fecIni = inpRepConIniFec.value;
        var fecFin = inpRepConFinFec.value;
        var textConc = selRepConCon.options[selRepConCon.selectedIndex].text;
        var idConcepto = selRepConCon.value;
        var acueducto = selRepConAcu.value;
        var entIni = inpRepConIniEnt.value;
        var entFin = inpRepConFinEnt.value;
        var punIni = inpRepConIniPun.value;
        var punFIn = inpRepConFinPun.value;
        var cajaIni = inpRepConIniCaj.value;
        var cajaFIn = inpRepConFinCaj.value;
        divRepPagConcRep.data = '../reportes/reporte.recaudoPorConcepto.php?fecPagIni=' + fecIni + '&fecPagFin=' + fecFin +
        "&idConcepto=" + idConcepto + "&descConcepto=" + textConc + "&acueducto=" + acueducto + "&entIni=" + entIni +
        "&entFin=" + entFin + "&punIni=" + punIni + "&punFin=" + punFIn + "&cajaIni=" + cajaIni + "&cajaFin=" + cajaFIn;
    }
}



function validaCampos(){
    if(selRepConAcu.selectedIndex==0){
        swal("Error!", "debe seleccionar el acueducto", "error");
        return false;
    }
    
    if(inpRepConIniFec.value==""){
        swal("Error!", "debe seleccionar la fecha inicial", "error");
        return false;
    }

    if(inpRepConFinFec.value==""){
        swal("Error!", "debe seleccionar la fecha final", "error");
        return false;
    }

    return true;
}







