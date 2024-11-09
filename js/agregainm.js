




var popped = null;
function popup2(uri, awid, ahei, scrollbar) {
    var params;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        } 
        else {
            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";                               
            popped = window.open(uri, "popup4", params);
        }
    }
}	
function upCliente() {
	
	popup2("vista.agregaurb.php",600,300,'yes');
}	

function upNomVia() {
	
	popup2("vista.agregacalle.php",600,300,'yes');
}	

function upApto() {
	
	popup2("vista.agregaApto.php",600,300,'yes');
}	

function infomante(){
	document.agregainm.proc.value = 1;
		return true;
}

function agregarurb(){
	if(document.agregainm.urbanizacion.value == 'Agregar Urbanizacion'){
		upCliente();
		document.agregainm.urbanizacion.value ='';
	}
}

function agreganomvia(){
	if(document.agregainm.nom_via.value == 'Agregar Nombre Via'){
		upNomVia();
		document.agregainm.nom_via.value ='';
	}
}

function agregaapto(){
	if(document.agregainm.ETapartamento.value == 'Agregar Apto'){
		upApto();
		document.agregainm.ETapartamento.value ='';
	}
}