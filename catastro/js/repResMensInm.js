/**
 * Created by Ehcontrerasg on 7/5/2016.
 */

var butRepResInmGen;
var inpRepResInmPer;
var selRepResInmPro;


function repMensInmInicio(){
    butRepResInmGen = document.getElementById("butRepResMenGen");
    inpRepResInmPer = document.getElementById("inpRepResMenPer");
    butRepResInmGen.addEventListener("click",verificaSession);
    selRepResInmPro=document.getElementById("selRepResMenProy");
    repResMensInmLleSelPro();
}

function genRepResMensInm() {
    if(validaCampos()){
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "../datos/datos.datRepMensInm.php");
        //form.setAttribute("target", "view");
        var campo1 = document.createElement("input");
        campo1.setAttribute("type", "hidden");
        campo1.setAttribute("name", "tip");
        campo1.setAttribute("value", "repRes");
        form.appendChild(campo1);
        var campo2 = document.createElement("input");
        campo2.setAttribute("type", "hidden");
        campo2.setAttribute("name", "per");
        campo2.setAttribute("value", inpRepResInmPer.value);
        form.appendChild(campo2);

        var campo3 = document.createElement("input");
        campo3.setAttribute("type", "hidden");
        campo3.setAttribute("name", "pro");
        campo3.setAttribute("value", selRepResInmPro.value);
        form.appendChild(campo3);
        document.body.appendChild(form);
        //window.open('', 'view');
        form.submit();
    }


}


function repResMensInmLleSelPro(){
    var select=selRepResInmPro;
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
    xmlhttp.open("POST", "../datos/datos.datRepMensInm.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

}


function validaCampos(){
    if(selRepResInmPro.selectedIndex==0){
        swal("Error!", "debe seleccionar el proyecto", "error");
        return false;
    }
    if(inpRepResInmPer.value==""){
        swal("Error!", "el periodo no puede ser vacio", "error");
        return false;
    }
    return true;
}

function verificaSession(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
                genRepResMensInm();
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su session ha finalizado.",
                        showConfirmButton: true },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            top.replace("../../index.php")
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepMensInm.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}