/**
 * Created by PC on 6/14/2016.
 */

var butAct;
var inpCli;
var inm;

function inicioInm(){
    inpCli=document.getElementById("inpDatInmCli");
    inm=document.getElementById("inpDatInmInm");
    butAct=document.getElementById("butActDat");
    butAct.addEventListener("click",actdatos);
}

function actdatos(){
    var cliente=inpCli.value;
    window.open("../../catastro/vistas/vista.actualizarcliente.php?cod_cliente="+cliente+"&inm="+inm.value, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400');
}

