/**
 * Created by Edwin on 26/05/2016.
 */
var selProyecto;
var selSector;
var selZona;
var selOperario;
var butProcesar;
var radbMetodo;
var formHojaRec;
var spinner;
var iframePdf;
var fecha;



function inicio(){

    iframePdf=document.getElementById("ifHojaRec");
    selProyecto=document.getElementById("selProyecto");
    selProyecto.addEventListener("change",cambiaSelProy);
    selSector=document.getElementById("selSector");
    selSector.addEventListener("change",cambiaSelsector);

    selZona=document.getElementById("selZona");
    selZona.addEventListener("change",cambiaSelZona);

    selOperario=document.getElementById("selOperario");
    butProcesar=document.getElementById("butOrdRec");
    butProcesar.addEventListener("click",procHojaRec);

    radbMetodo=document.getElementsByName("rbModo");
    formHojaRec=document.getElementById("formHojasRec");

    fechaAsig = document.getElementById('fecha_asig');
    
    llenarSelProy();
}

function procHojaRec(){
    var tipo=getRadioButtonSelectedValue(radbMetodo);
    var pro=selProyecto.value;
    var sec=selSector.value;
    var zon=selZona.value;
    var oper=selOperario.value;
    var fecha = fechaAsig.value;
    iframePdf.src="../datos/datos.hojaReco.php?tipo="+tipo+"&proy="+pro+"&sec="+sec+"&zon="+zon+"&oper="+oper+"&fechaAsig="+fecha;
}



function cambiaSelProy(){
    llenarSelSector();
    llenarSelOperario();
}

function cambiaSelsector(){
    if(selSector.value!=""){
        selProyecto.disabled=true;
    }else{
        selProyecto.disabled=false;
    }
    llenarSelZona();
    llenarSelOperario();
}

function  cambiaSelZona(){
    if(selZona.value!=""){
        selSector.disabled=true;
    }else{
        selSector.disabled=false;
    }
    //spinner = new Spinner(opts).spin(formHojaRec);
    //formHojaRec.appendChild(spinner.el);
    llenarSelOperario();
}


function llenarSelProy(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selProyecto.add(option,selProyecto[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);

            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                selProyecto.add(option,selProyecto[x+1]);
            }
            //formHojaRec.removeChild(spinner.el);
            habilitarBotones();
        }
        else if(xmlhttp.readyState == 1){
            //spinner = new Spinner(opts).spin(formHojaRec);
            //formHojaRec.appendChild(spinner.el);
            desabilitarBotones();
        }
    }
    xmlhttp.open("POST", "../datos/datos.hojaReco.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tipo=proy");
}




function llenarSelSector(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            ///////////// vaciamos el select en caso de que tenga datos//////
            var length = selSector.options.length;
            for (i = 0; i < length; i++) {
                selSector.remove(0);
            }
            //////////////creamos un option vacio////////////////
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selSector.add(option,selSector[0]);
            var datos= JSON.parse(xmlhttp.responseText);
            if(datos!=null){
                /////////////// llenamos los option////////////7
                for(var x=0;x<datos.length;x++){
                    var option=document.createElement("OPTION");
                    option.value=(datos[x]["ID_SECTOR"]);
                    option.text=datos[x]["ID_SECTOR"];
                    selSector.add(option,selSector[x+1]);
                }
            }
        }
        else if(xmlhttp.readyState == 1){
            //spinner = new Spinner(opts).spin(formHojaRec);
            //formHojaRec.appendChild(spinner.el);

        }
    }
    xmlhttp.open("POST", "../datos/datos.hojaReco.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tipo=sec&proy="+selProyecto.value);
}



function llenarSelZona(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            ///////////// vaciamos el select en caso de que tenga datos//////
            var length = selZona.options.length;
            for (i = 0; i < length; i++) {
                selZona.remove(0);
            }
            //////////////creamos un option vacio////////////////
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selZona.add(option,selZona[0]);
            var datos= JSON.parse(xmlhttp.responseText);

            if(datos!=null){
                /////////////// llenamos los option////////////7
                for(var x=0;x<datos.length;x++){
                    var option=document.createElement("OPTION");
                    option.value=(datos[x]["ID_ZONA"]);
                    option.text=datos[x]["ID_ZONA"];
                    selZona.add(option,selZona[x+1]);
                }
            }
        }
        else if(xmlhttp.readyState == 1){
            //spinner = new Spinner(opts).spin(formHojaRec);
            //formHojaRec.appendChild(spinner.el);

        }
    }
    xmlhttp.open("POST", "../datos/datos.hojaReco.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tipo=zon&sec="+selSector.value);
}



function llenarSelOperario(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            ///////////// vaciamos el select en caso de que tenga datos//////
            var length = selOperario.options.length;
            for (i = 0; i < length; i++) {
                selOperario.remove(0);
            }
            //////////////creamos un option vacio////////////////
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selOperario.add(option,selOperario[0]);
            var datos= JSON.parse(xmlhttp.responseText);
            if(datos!=null){
                /////////////// llenamos los option////////////7
                for(var x=0;x<datos.length;x++){
                    var option=document.createElement("OPTION");
                    option.value=(datos[x]["USR_EJE"]);
                    option.text=datos[x]["NOMBRE"];
                    selOperario.add(option,selOperario[x+1]);
                }
            }
            //formHojaRec.removeChild(spinner.el);
        }
        else if(xmlhttp.readyState == 1){

        }
    }
    xmlhttp.open("POST", "../datos/datos.hojaReco.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tipo=ope&proy="+selProyecto.value+"&sec="+selSector.value+"&zon="+selZona.value);
}

function desabilitarBotones()
{

    for (a=0; a < document.getElementsByTagName('input').length; a++)
    {
        // Desabilitamos todos para bloquear el formulario
        document.getElementsByTagName('input')[a].disabled = true;
    }

    for (a=0; a < document.getElementsByTagName('select').length; a++)
    {
        // Desabilitamos todos para bloquear el formulario
        document.getElementsByTagName('select')[a].disabled = true;
    }


}

function habilitarBotones()
{

    for (a=0; a < document.getElementsByTagName('input').length; a++)
    {
        document.getElementsByTagName('input')[a].disabled = false;
    }

    for (a=0; a < document.getElementsByTagName('select').length; a++)
    {
        document.getElementsByTagName('select')[a].disabled = false;
    }


}

function getRadioButtonSelectedValue(ctrl)
{
    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) return ctrl[i].value;
}