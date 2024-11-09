/**
 * Created by PC on 7/14/2016.
 */

var tBodyDetPag;
var idCaja;
var fecIni;
var fecFin;
var proyecto;

function detallePagInicio(){
    verificaSession();
}

function verificaSession(funcion){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
                tBodyDetPag= document.getElementById("tBodyDetallePag");
                idCaja=getParameterByName('id_caja');
                fecIni=getParameterByName('fecini');
                fecFin=getParameterByName('fecfin');
				proyecto=getParameterByName('proyecto');
                obtDatTabl();
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su session ha finalizado.",
                        showConfirmButton: true },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.detallePag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function detallePagCreaTabla(varJson){
    var importe = 0;
    var facturado = 0;
	try {length = varJson.length; 
		for(var x=0;x<varJson.length;x++){
			var hilera =document.createElement("tr");
            var celda=document.createElement("td");
            var textoCelda=document.createTextNode(x+1);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["INMUEBLE"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["ID_PAGO"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
            var celda=document.createElement("td");
            var textoCelda=document.createTextNode(varJson[x]["NOMBRE_CLIENTE"]);
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["FECHA_PAGO"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["IMPORTE"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["FACTURADO"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["LOGIN"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
			var celda=document.createElement("td");
			var textoCelda=document.createTextNode(varJson[x]["DESCRIPCION"]);
			celda.appendChild(textoCelda);
			hilera.appendChild(celda);
			tBodyDetPag.appendChild(hilera);
            importe = importe + parseInt((varJson[x]["IMPORTE"]));
            facturado = facturado + parseInt((varJson[x]["FACTURADO"]));
		}
        var hileraSum =document.createElement("tr");
        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode('Total');
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);
        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode('');
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);
        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode('');
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);
        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode('');
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);
        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode('');
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);

        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode(importe);
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);
        var celdaSum=document.createElement("td");
        var textoCeldaSum=document.createTextNode(facturado);
        celdaSum.appendChild(textoCeldaSum);
        hileraSum.appendChild(celdaSum);
        tBodyDetPag.appendChild(hileraSum);
	}  
	catch(error){
		return null;
	}
}


function obtDatTabl(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            datos=JSON.parse(xmlhttp.responseText);
            detallePagCreaTabla(datos);

            var hilera =document.createElement("tr");
            hilera.classList.add('titTabla');
			var celda=document.createElement("th");
            var textoCelda=document.createTextNode("No");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Inmueble");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
            var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Id Otro Recaudo");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
            var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Cliente");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
            var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Fecha Pago");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
            var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Importe");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Facturado");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			var celda=document.createElement("th");
			var textoCelda=document.createTextNode("Usuario");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			var celda=document.createElement("th");
            var textoCelda=document.createTextNode("Forma Pago");
            celda.appendChild(textoCelda);
            hilera.appendChild(celda);
			tBodyDetPag.appendChild(hilera);


            obtDatOtrTabl();
        }
        else if(xmlhttp.readyState == 1){

        }
    }
    xmlhttp.open("POST", "../datos/datos.detallePag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=pagCaj&caja="+idCaja+"&fecIni="+fecIni+"&fecFin="+fecFin+"&proyecto="+proyecto);
}


function obtDatOtrTabl(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            datos=JSON.parse(xmlhttp.responseText);
            detallePagCreaTabla(datos);
        }
        else if(xmlhttp.readyState == 1){

        }
    }
    xmlhttp.open("POST", "../datos/datos.detallePag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=recCaj&caja="+idCaja+"&fecIni="+fecIni+"&fecFin="+fecFin+"&proyecto="+proyecto);
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}