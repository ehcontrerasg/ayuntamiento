/**
 * Created by ehcontrerasg on 7/1/2016.
 */
var inmueble;
var butAgrega;
var selCodObs;
var inpDesc;
var inpAsunto;

function obsInicio() {
    inmueble = getParameterByName('cod_inmueble');
    butAgrega = document.getElementById("butCatObsGuardar");
    selCodObs = document.getElementById("selCatObsTipoObs");
    inpDesc = document.getElementById("inpCatObsDesc");
    inpAsunto = document.getElementById("inpCatObsAsunto");
    butAgrega.addEventListener("click",guardaObsBut);
    obsLlenaSelTiposObs();
}

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




function flexyObs(){
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {

            url: './../datos/datos.observaciones.php?cod_inmueble='+inmueble+'&tip=flexy',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width: 20,  align: 'center'},
                {display: 'Consecutivo', name: 'CONSECUTIVO', width: 70, sortable: true, align: 'center'},
                {display: 'Asunto', name: 'ASUNTO', width: 150, sortable: true, align: 'center'},
                {display: 'Codigo', name: 'CODIGO_OBS', width: 80, sortable: true, align: 'center'},
                {display: 'Descripcion', name: 'DESCRIPCION', width: 1000, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA', width: 100, sortable: true, align: 'center'},
                {display: 'Usuario', name: 'LOGIN', width: 100, sortable: true, align: 'center'},

            ],



            sortname: "FECHA",
            sortorder: "DESC",
            usepager: true,
            title: 'Observaciones',
            useRp: true,
            rp: 100,
            page: 1,
            showTableToggleBtn: true,
            width: 900,
            height: 250
        }
    );
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function guardaObsBut(){
    if(validaCampos()){
        guardaObs();
    }
}

function validaCampos(){
    if(selCodObs.selectedIndex==0){
        swal("Error!", "debe seleccionar el codigo de observacion", "error");
        return false;
    }

    if(inpAsunto.value==""){
        swal("Error!", "el asunto no puede ser vacio", "error");
        return false;
    }

    if(inpDesc.value==''){
        swal("Error!", "la descripcion no puede ser vacia", "error");
        return false;
    }

    return true;
}

function obsLlenaSelTiposObs(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selCodObs.add(option,selCodObs[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                selCodObs.add(option,selCodObs[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.observaciones.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selTipo");

}

function guardaObs(){

    var asu=inpAsunto.value;
    var des=inpDesc.value;
    var cod=selCodObs.value;
    var inm=inmueble;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var coderror= xmlhttp.responseText;
            if(coderror=="false"){
                swal("Error!", "error desconocido en la base de datos", "error");
            }else if(coderror=="true"){
                $("#flex1").flexReload();
                swal("Transaccion Exitosa!", "Has guardado correctamente la observacion", "success");
            }else{
                swal("Error!", coderror, "error");
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.observaciones.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingDatos&asu="+asu+"&des="+des+"&cod="+cod+"&inm="+inm);

}
