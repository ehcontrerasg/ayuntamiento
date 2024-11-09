/**
 * Created by Ehcontrerasg on 7/5/2016.
 */

var butRepInmCatGen;
var selRepInmCatPro;


function repMensInmCatInicio(){
    butRepInmCatGen = document.getElementById("butRepMenCat");
    butRepInmCatGen.addEventListener("click",verificaSession);
    selRepInmCatPro=document.getElementById("selRepMenCatProy");
    repMensInmIncLleSelPro();
}

function genRepMensInmCat() {

    if(validaCampos()){
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "../datos/datos.datRepMensInm.php");
        //form.setAttribute("target", "view");
        var campo1 = document.createElement("input");
        campo1.setAttribute("type", "hidden");
        campo1.setAttribute("name", "tip");
        campo1.setAttribute("value", "repCat");
        form.appendChild(campo1);
        var campo2 = document.createElement("input");
        campo2.setAttribute("type", "hidden");
        campo2.setAttribute("name", "pro");
        campo2.setAttribute("value", selRepInmCatPro.value);
        form.appendChild(campo2);
        document.body.appendChild(form);
        //window.open('', 'view');
        form.submit();
    }


}



function repMensInmIncLleSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selRepInmCatPro.add(option,selRepInmCatPro[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                selRepInmCatPro.add(option,selRepInmCatPro[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepMensInm.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

}


function validaCampos(){
    if(selRepInmCatPro.selectedIndex==0){
        swal("Error!", "debe seleccionar el proyecto", "error");
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
                genRepMensInmCat();
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