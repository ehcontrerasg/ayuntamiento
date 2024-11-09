/**
 * Created by Ehcontrerasg on 7/5/2016.
 */

var inpIngCliTemInm;
var inpIngCliTemNomCli;
var selIngCliTemTipDoc;
var inpIngCliTemDoc;
var butIngCliTemAgr;


function ingCliTemInicio(){
        inpIngCliTemInm     = document.getElementById("inpAgrCliTemInm");
        inpIngCliTemNomCli  = document.getElementById("inpAgrCliTemNomCli");
        selIngCliTemTipDoc  = document.getElementById("selAgrCliTemTipDoc");
        inpIngCliTemDoc     = document.getElementById("inpAgrCliTemDoc");
        butIngCliTemAgr     = document.getElementById("butAgrCliTemAgr");
        ingCliTemLleSelDoc();
        butIngCliTemAgr.addEventListener("click",verificaSession);
}



function ingCliTemLleSelDoc(){
    var select= selIngCliTemTipDoc;
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
                option.value=(datos[x]["ID_TIPO_DOC"]);
                option.text=datos[x]["DESCRIPCION_TIPO_DOC"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.agregaCliTemp.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selDoc");

}


function validaCampos(){
    return true;
}

function ingCliTemAgregaNombre() {
    var inm    =inpIngCliTemInm.value;
    var nom    =inpIngCliTemNomCli.value;
    var tipDoc =selIngCliTemTipDoc.value;
    var doc    =inpIngCliTemDoc.value;
    var tel    ='';
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
                swal("Ok!", "Has realizadoel proceso con exito!", "success");
            }else{
                swal("error!",datos, "error");
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.agregaCliTemp.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=agrNom&inm="+inm+"&nom="+nom+"&tipDoc="+tipDoc+"&doc="+doc+"&tel="+tel);


}


function verificaSession(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
                ingCliTemAgregaNombre();
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su session ha finalizado.",
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
    xmlhttp.open("POST", "../datos/datos.agregaCliTemp.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}