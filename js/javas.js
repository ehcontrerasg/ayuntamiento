//FUNCION DE VALIDACION DE PROGRAMACION DE VISITA

function programar() {	
	if (document.registro.direccion.value == "" || document.registro.direccion.value == null) {
		alert("Debe ingresar la direccion !!");
		document.registro.direccion.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.zona.selectedIndex <= 0) {
		alert("Debe ingresar la zona !!");
		document.registro.zona.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.porcion.selectedIndex <= 0) {
		alert("Debe ingresar la porcion !!");
		document.registro.porcion.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.fuente.selectedIndex <= 0) {
		alert("Debe ingresar la fuente !!");
		document.registro.fuente.focus();
		document.registro.proc.value=0;
		return false;	
	}		
	if (document.registro.tipologia.selectedIndex <= 0) {
		alert("Debe ingresar la tipologia !!");
		document.registro.tipologia.focus();
		document.registro.proc.value=0;
		return false;	
	}
	if (document.registro.programa.selectedIndex <= 0) {
		alert("Debe ingresar el programa !!");
		document.registro.programa.focus();
		document.registro.proc.value=0;
		return false;	
	}
	if (document.registro.aviso.value == "" || document.registro.aviso.value == null) {
		alert("Debe ingresar el numero de aviso T2 !!");
		document.registro.aviso.focus();
		document.registro.proc.value=0;
		return false;	
	}		
	if (document.registro.anomalia.selectedIndex <= 0) {
		alert("Debe seleccionar la anomalia !!");
		document.registro.anomalia.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.modo.selectedIndex <= 0) {
		alert("Debe seleccionar el modo de entrega !!");
		document.registro.modo.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.remitente.selectedIndex <= 0) {
		alert("Debe seleccionar el remitente !!");
		document.registro.remitente.focus();
		document.registro.proc.value=0;
		return false;	
	}
	if (document.registro.dependencia.selectedIndex <= 0) {
		alert("Debe seleccionar la dependencia!!");
		document.registro.dependencia.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if(document.registro.nomrem.style.visibility == "visible"){
		if (document.registro.nomrem.value == "" || document.registro.nomrem.value == null) {
			alert("Debe ingesar el nombre del remitente !!");
			document.registro.nomrem.focus();
			document.registro.proc.value=0;
			return false;	
		}	
	}
	if (document.registro.prioridad.selectedIndex <= 0) {
		alert("Debe seleccionar la prioridad !!");
		document.registro.prioridad.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.actividad.selectedIndex <= 0) {
		alert("Debe seleccionar la actividad !!");
		document.registro.actividad.focus();
		document.registro.proc.value=0;
		return false;	
	}	
	if (document.registro.area.value == 'CLP') {
		if(document.registro.solicitud.value == ""){
			alert("Debe ingresar el número de solicitud !!");
			document.registro.solicitud.focus();
			document.registro.proc.value=0;
			return false;
		}
	}	
	if (document.registro.observaciones.value == "" || document.registro.observaciones.value == null) {
		alert("Debe ingresar la observacion !!");
		document.registro.observaciones.focus();
		document.registro.proc.value=0;
		return false;	
	}		
	if (document.registro.observaciones.value.length <= 10){
		alert("La Observacion no puede ser tan corta!!");
   		document.registro.observaciones.focus();
		document.registro.proc.value=0;
		return false; 
	}			 
 	else { 
	   document.registro.proc.value = 1;	   
	   return true; 
	}	
}		

function masivas() {							  
	if (document.masiva.carga.value == "" || document.masiva.carga.value == null) {
	   	alert("Debe seleccionar el archivo a cargar !!");
		document.masiva.carga.focus();
		document.masiva.descarga.disabled = true;
		return false; 
	}					
	else{
		document.masiva.proc.value = 2;		
	}
}  				

function reasignar() {
	if(document.reasignacion.operarioa.value == "" || document.reasignacion.operarioa.value == null){
		alert("SELECCIONE UN OPERARIO PARA REASIGNAR !!");
		document.reasignacion.operarioa.focus();
		return false;
	}										
	else{  		
		document.reasignacion.proc.value = 4;
  		document.reasignacion.submit();		
		return true;
	}
	
}	 

function buscar(form){
	if ((form.cuentacontrato.value == "" || form.cuentacontrato.value == null) && (form.direc.value == "" || form.direc.value == null) && (form.aviso.value == "" || form.aviso.value == null) && (form.fechadesde.value == "" || form.fechadesde.value == null) && (form.fechahasta.value == "" || form.fechahasta.value == null)) {
		alert("Debe ingresar un parametro de busqueda !!");
		form.cuentacontrato.focus();
		form.proca.value=0;
		return false;	
	}	
	
	if (form.cuentacontrato.value != "" && form.direc.value != "") {
		alert("Debe ingresar solo un parametro para su busqueda !!");
		form.cuentacontrato.value = "";
		form.direc.value = "";		
		form.cuentacontrato.focus();		
		form.proca.value=0;		
		return false;	
	}	
	if (!form.RadioGroup1_0.checked && !form.RadioGroup1_1.checked) {
		alert("Debe Seleccionar programadas o visitadas!!");
		form.cuentacontrato.value = "";
		form.direc.value = "";		
		form.cuentacontrato.focus();		
		form.proca.value=0;		
		return false;	
	}					
}


function modificar(form){
	if (form.modifica.selectedIndex <= 0) {
	   alert("Debe escoger el tipo de información a modificar !!");
	   form.modifica.focus();	  
	   return false;
	}
	if ((form.cuentacontrato.value == "" || form.cuentacontrato.value == null) && (form.direc.value == "" || form.direc.value == null)) {
		alert("Debe ingresar una cuenta o dirección !!");
		form.cuentacontrato.focus();
		form.proca.value=0;
		return false;	
	}	
	
	if (form.cuentacontrato.value != "" && form.direc.value != "") {
		alert("Debe ingresar solo un parametro para su busqueda !!");
		form.cuentacontrato.value = "";
		form.direc.value = "";		
		form.cuentacontrato.focus();		
		form.proca.value=0;		
		return false;	
	}			
}

function buscarana(form){
	if (form.analista.selectedIndex <= 0) {
	   alert("Debe selecionar el analista !!");
	   form.analista.focus();	  
	   return false;
	}
	if ((form.fechadesde.value == "" || form.fechadesde.value == null)) {
		alert("Debe ingresar la fecha de Asignación Inicial !!");
		form.fechadesde.focus();		
		return false;	
	}	
	
	if ((form.fechahasta.value == "" || form.fechahasta.value == null)) {
		alert("Debe ingresar la fecha de Asignación Final !!");
		form.fechahasta.focus();		
		return false;	
	}
	if (form.fechahasta.value < form.fechahasta.value){
		alert("fecha menor");
	}
}


function buscarpref(form){
	if (form.zona.selectedIndex <= 0) {
	   alert("Debe selecionar la zona !!");
	   form.zona.focus();	  
	   return false;
	}
	if ((form.fechadesde.value == "" || form.fechadesde.value == null)) {
		alert("Debe ingresar la fecha de inicio !!");
		form.fechadesde.focus();		
		return false;	
	}	
	
	if ((form.fechahasta.value == "" || form.fechahasta.value == null)) {
		alert("Debe ingresar la fecha de fin !!");
		form.fechahasta.focus();		
		return false;	
	}			
}


function verifica(form) {
    if (form.operario.selectedIndex <= 0) {
	   alert("Debe selecionar el operario !!");
	   form.operario.focus();
	   form.proca.value=0;
	   return false;
	}
	if (form.fechavis.value == "" || form.fechavis.value == null) {
	   alert("Debe seleccionar la fecha de toma de las fotos !!");
	   form.fechavis.focus();
	   form.proca.value=0;
	   return false;
	}
}

function asocia(form){
	if (form.acta.value == "" || form.acta.value == null) {
	   alert("Debe ingresar un numero de acta para asociar las fotos !!");
	   form.acta.focus();
	   form.proc.value=0;
	   return false;
	}	
	else{  		
		form.proc.value = 4;  		
		return true;
	}
}

function actualiza(){
	if (document.acta.numacta.value == "" || document.acta.numacta.value == null) {
		alert("Debe ingresar el numero de acta !!");
		document.acta.numacta.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.fecacta.value == "" || document.acta.fecacta.value == null) {
		alert("Debe ingresar la fecha de acta !!");
		document.acta.fecacta.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.estrato.value == "" || document.acta.estrato.value == null) {
		alert("Debe ingresar el estrato !!");
		document.acta.estrato.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.localidad.selectedIndex <= 0) {
		alert("Debe seleccionar la localidad !!");
		document.acta.localidad.focus();
		document.acta.proc.value=0;
		return false;	
	}	
	if (document.acta.resvisita.selectedIndex <= 0) {
		alert("Debe seleccionar el resultado de la visita !!");
		document.acta.resvisita.focus();
		document.acta.proc.value=0;
		return false;	
	}	
	if (document.acta.resvisita.selectedIndex == 1 && document.acta.anomalia.selectedIndex <= 0) {
		alert("Debe seleccionar la anomalia !!");
		document.acta.anomalia.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.resvisita.selectedIndex == 2){
		if(document.acta.inefectivas.selectedIndex <= 0) {
			alert("Debe seleccionar el motivo de inefectividad !!");
			document.acta.inefectivas.focus();
			document.acta.proc.value=0;
			return false;	
		}
		if (document.acta.revisita.checked){
			if(document.acta.motivorev.selectedIndex <= 0){
				alert("Debe seleccionar el motivo de revisita");
				document.acta.motivorev.focus();
				document.acta.proc.value=0;
				return false;
			}
		}
		if (document.acta.obsoper.value == "" || document.acta.obsoper.value == null) {
			alert("Debe ingresar la observacion del operario !!");
			document.acta.obsoper.focus();
			document.acta.proc.value=0;
			return false;
		}
		if (document.acta.fecfinacta.value == "" || document.acta.fecfinacta.value == null) {
			alert("Debe ingresar la fecha de finalización del acta !!");
			document.acta.fecfinacta.focus();
			document.acta.proc.value=0;
			return false;
		}
		else {
	   		document.acta.proc.value = 5;
	   		return true;
		}
	}
	if (document.acta.resvisita.selectedIndex == 3){
		if (document.acta.revisita.checked){
			if(document.acta.motivorev.selectedIndex <= 0){
				alert("Debe seleccionar el motivo de revisita");
				document.acta.motivorev.focus();
				document.acta.proc.value=0;
				return false;
			}
		}
		if (document.acta.obsoper.value == "" || document.acta.obsoper.value == null) {
			alert("Debe ingresar la observacion del operario !!");
			document.acta.obsoper.focus();
			document.acta.proc.value=0;
			return false;
		}
		if (document.acta.fecfinacta.value == "" || document.acta.fecfinacta.value == null) {
			alert("Debe ingresar la fecha de finalización del acta !!");
			document.acta.fecfinacta.focus();
			document.acta.proc.value=0;
			return false;
		}
		else {
	   		document.acta.proc.value = 5;
	   		return true;
		}
	}
	if (document.acta.revisita.checked){
		if(document.acta.motivorev.selectedIndex <= 0){
			alert("Debe seleccionar el motivo de revisita");
			document.acta.motivorev.focus();
			document.acta.proc.value=0;
			return false;
		}
	}
	if (document.acta.operario.selectedIndex <= 0) {
		alert("Debe seleccionar el operario que realizo la visita !!");
		document.acta.operario.focus();
		document.acta.proc.value=0;
		return false;	
	}			
	if (document.acta.marca.selectedIndex <= 0) {
		alert("Debe seleccionar la marca del medidor !!");
		document.acta.marca.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.estado.selectedIndex <= 0) {
		alert("Debe seleccionar el estado del medidor !!");
		document.acta.estado.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.diametro.selectedIndex <= 0) {
		alert("Debe seleccionar el diametro del medidor !!");
		document.acta.diametro.focus();
		document.acta.proc.value=0;
		return false;	
	}		
	if (document.acta.marca.selectedIndex >= 2 && document.acta.nummed.value == "") {
		alert("Debe ingresar el número del medidor !!");
		document.acta.nummed.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.lectura.value == "") {
		alert("Debe ingresar la lectura del medidor !!");
		document.acta.lectura.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.ubicacion.selectedIndex <= 0) {
		alert("Debe seleccionar la ubicacion del medidor !!");
		document.acta.ubicacion.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.clase.selectedIndex <= 0) {
		alert("Debe seleccionar la clase de uso !!");
		document.acta.clase.focus();
		document.acta.proc.value=0;
		return false;	
	}
	
	if (document.acta.obsoper.value == "" || document.acta.obsoper.value == null) {
		alert("Debe ingresar la observacion del operario !!");
		document.acta.obsoper.focus();
		document.acta.proc.value=0;
		return false;
	}
	if (document.acta.fecfinacta.value == "" || document.acta.fecfinacta.value == null) {
		alert("Debe ingresar la fecha de finalización del acta !!");
		document.acta.fecfinacta.focus();
		document.acta.proc.value=0;
		return false;
	}
	else {
	   document.acta.proc.value = 5;
	   return true;
	}
}

function infovisita() {	
if(document.acta.txt_boton.value=="Visitadas"){
		alert("No puede guardar el valor para visitadas, solo programadas!!");		
		document.acta.proc.value=0;
		return false;
	}
	
	if (document.acta.numacta.value == "" || document.acta.numacta.value == null) {
		
		alert("Debe ingresar el numero de acta !!");
		document.acta.numacta.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if(document.acta.numacta.value.length < 5){
		alert("Número de acta no valido !!");		
		document.acta.numacta.focus();
		document.acta.proc.value=0;
		return false;
	}
	if (document.acta.fecacta.value == "" || document.acta.fecacta.value == null) {
		alert("Debe ingresar la fecha de acta !!");
		document.acta.fecacta.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.estrato.value == "" || document.acta.estrato.value == null) {
		alert("Debe ingresar el estrato !!");
		document.acta.estrato.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.localidad.selectedIndex <= 0) {
		alert("Debe seleccionar la localidad !!");
		document.acta.localidad.focus();
		document.acta.proc.value=0;
		return false;	
	}	
	if (document.acta.resvisita.selectedIndex <= 0) {
		alert("Debe seleccionar el resultado de la visita !!");
		document.acta.resvisita.focus();
		document.acta.proc.value=0;
		return false;	
	}	
	if (document.acta.resvisita.selectedIndex == 1 && document.acta.anomalia.selectedIndex <= 0) {
		alert("Debe seleccionar la anomalia !!");
		document.acta.anomalia.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.resvisita.selectedIndex == 2){
		if(document.acta.inefectivas.selectedIndex <= 0) {
			alert("Debe seleccionar el motivo de inefectividad !!");
			document.acta.inefectivas.focus();
			document.acta.proc.value=0;
			return false;	
		}
		if (document.acta.revisita.checked){
			if(document.acta.motivorev.selectedIndex <= 0){
				alert("Debe seleccionar el motivo de revisita");
				document.acta.motivorev.focus();
				document.acta.proc.value=0;
				return false;
			}
		}
		if (document.acta.obsoper.value == "" || document.acta.obsoper.value == null) {
			alert("Debe ingresar la observacion del operario !!");
			document.acta.obsoper.focus();
			document.acta.proc.value=0;
			return false;
		}
		if (document.acta.fecfinacta.value == "" || document.acta.fecfinacta.value == null) {
			alert("Debe ingresar la fecha de finalización del acta !!");
			document.acta.fecfinacta.focus();
			document.acta.proc.value=0;
			return false;
		}
		else {
	   		document.acta.proc.value = 1;
	   		return true;
		}
	}
	if (document.acta.resvisita.selectedIndex == 3){
		if (document.acta.revisita.checked){
			if(document.acta.motivorev.selectedIndex <= 0){
				alert("Debe seleccionar el motivo de revisita");
				document.acta.motivorev.focus();
				document.acta.proc.value=0;
				return false;
			}
		}
		if (document.acta.obsoper.value == "" || document.acta.obsoper.value == null) {
			alert("Debe ingresar la observacion del operario !!");
			document.acta.obsoper.focus();
			document.acta.proc.value=0;
			return false;
		}
		if (document.acta.fecfinacta.value == "" || document.acta.fecfinacta.value == null) {
			alert("Debe ingresar la fecha de finalización del acta !!");
			document.acta.fecfinacta.focus();
			document.acta.proc.value=0;
			return false;
		}
		else {
	   		document.acta.proc.value = 1;
	   		return true;
		}
	}
	if (document.acta.revisita.checked){
		if(document.acta.motivorev.selectedIndex <= 0){
			alert("Debe seleccionar el motivo de revisita");
			document.acta.motivorev.focus();
			document.acta.proc.value=0;
			return false;
		}
	}
	if (document.acta.operario.selectedIndex <= 0) {
		alert("Debe seleccionar el operario que realizo la visita !!");
		document.acta.operario.focus();
		document.acta.proc.value=0;
		return false;	
	}			
	if (document.acta.marca.selectedIndex <= 0) {
		alert("Debe seleccionar la marca del medidor !!");
		document.acta.marca.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.estado.selectedIndex <= 0) {
		alert("Debe seleccionar el estado del medidor !!");
		document.acta.estado.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.diametro.selectedIndex <= 0) {
		alert("Debe seleccionar el diametro del medidor !!");
		document.acta.diametro.focus();
		document.acta.proc.value=0;
		return false;	
	}		
	if (document.acta.marca.selectedIndex >= 2 && document.acta.nummed.value == "") {
		alert("Debe ingresar el número del medidor !!");
		document.acta.nummed.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.lectura.value == "") {
		alert("Debe ingresar la lectura del medidor !!");
		document.acta.lectura.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.marca.selectedIndex >= 2 && document.acta.ubicacion.selectedIndex <= 0) {
		alert("Debe seleccionar la ubicacion del medidor !!");
		document.acta.ubicacion.focus();
		document.acta.proc.value=0;
		return false;	
	}
	if (document.acta.clase.selectedIndex <= 0) {
		alert("Debe seleccionar la clase de uso !!");
		document.acta.clase.focus();
		document.acta.proc.value=0;
		return false;	
	}
	
	if (document.acta.obsoper.value == "" || document.acta.obsoper.value == null) {
		alert("Debe ingresar la observacion del operario !!");
		document.acta.obsoper.focus();
		document.acta.proc.value=0;
		return false;
	}
	if (document.acta.fecfinacta.value == "" || document.acta.fecfinacta.value == null) {
		alert("Debe ingresar la fecha de finalización del acta !!");
		document.acta.fecfinacta.focus();
		document.acta.proc.value=0;
		return false;
	}
	else {
	   document.acta.proc.value = 1;
	   return true;
	}
}

function preliquidacion() {			
	if(document.preliquida.clase.value != 6){
		if (document.preliquida.tipologia.selectedIndex <= 0) {
			alert("Debe seleccionar la tipologia!!");
			document.preliquida.tipologia.focus();
			document.preliquida.proc.value=0;
			return false;	
		}
		if (document.preliquida.anomalia.selectedIndex <= 0) {
			alert("Debe seleccionar la anomalia!!");
			document.preliquida.anomalia.focus();
			document.preliquida.proc.value=0;
			return false;	
		}
		if (document.preliquida.resultado.selectedIndex <= 0) {
			alert("Debe seleccionar el resultado!!");
			document.preliquida.resultado.focus();
			document.preliquida.proc.value=0;
			return false;	
		}
		
		if (document.preliquida.resultado.selectedIndex == 2) {
			if (document.preliquida.causa.selectedIndex <= 0) {
				alert("Debe seleccionar la causa de no procedencia !!");
				document.preliquida.causa.focus();
				document.preliquida.proc.value=0;
				return false;	
			}
		}
		
		if (document.preliquida.tipologia.selectedIndex == 1 ) {
			document.preliquida.proc.value = 2;
	   		return true;
		}
		if (document.preliquida.tipologia.selectedIndex == 2 ) {
			if(document.preliquida.difer[0].checked){
				if(document.preliquida.lecinicio.value == ""){
					alert("Debe ingresar la lectura de inicio!!");
					document.preliquida.lecinicio.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				if(document.preliquida.lecfin.value == ""){
					alert("Debe ingresar la lectura de fin!!");
					document.preliquida.lecfin.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				else {
					document.preliquida.proc.value = 2;
					return true;
				}
			}
			else if(document.preliquida.difer[1].checked){
				if(document.preliquida.fecinicio.value == ""){
					alert("Debe ingresar la fecha de inicio!!");
					document.preliquida.fecinicio.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				if(document.preliquida.fecfin.value == ""){
					alert("Debe ingresar la fecha de fin!!");
					document.preliquida.fecfin.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				if(document.preliquida.cmoprom.value == ""){
					alert("Debe ingresar el consumo promedio!!");
					document.preliquida.cmoprom.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				else {
					document.preliquida.proc.value = 2;
					return true;
				}	
			}
			else{
				alert("Seleccione una diferencia!!");
				document.preliquida.proc.value=0;
				return false;	
			}
		}
	}
	
	else if(document.preliquida.clase.value == 6){
		if (document.preliquida.tipologia.selectedIndex <= 0) {
			alert("Debe seleccionar la tipologia!!");
			document.preliquida.tipologia.focus();
			document.preliquida.proc.value=0;
			return false;	
		}
		if (document.preliquida.anomalia.selectedIndex <= 0) {
			alert("Debe seleccionar la anomalia!!");
			document.preliquida.anomalia.focus();
			document.preliquida.proc.value=0;
			return false;	
		}
		if (document.preliquida.tipologia.selectedIndex == 1 ) {
			document.preliquida.proc.value = 2;
	   		return true;
		}
		if (document.preliquida.tipologia.selectedIndex == 2 ) {
			if(document.preliquida.difer[0].checked){
				if(document.preliquida.lecinicio.value == ""){
					alert("Debe ingresar la lectura de inicio!!");
					document.preliquida.lecinicio.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				if(document.preliquida.lecfin.value == ""){
					alert("Debe ingresar la lectura de fin!!");
					document.preliquida.lecfin.focus();
					document.preliquida.proc.value=0;
					return false;	
				}				
				else {
					document.preliquida.proc.value = 2;
					return true;
				}
			}
			else if(document.preliquida.difer[1].checked){
				if(document.preliquida.fecinicio.value == ""){
					alert("Debe ingresar la fecha de inicio!!");
					document.preliquida.fecinicio.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				if(document.preliquida.fecfin.value == ""){
					alert("Debe ingresar la fecha de fin!!");
					document.preliquida.fecfin.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				if(document.preliquida.cmoprom.value == ""){
					alert("Debe ingresar el consumo promedio!!");
					document.preliquida.cmoprom.focus();
					document.preliquida.proc.value=0;
					return false;	
				}
				//if (document.preliquida.multi[0].checked || document.preliquida.multi[1].checked){									
					/*if(document.preliquida.cmoprommulti.value == ""){
						alert("Debe ingresar el consumo promedio para multiusuario!!");
						document.preliquida.cmoprommulti.focus();
						document.preliquida.proc.value=0;
						return false;	
					}*/
				//}
				else {
					document.preliquida.proc.value = 2;
					return true;
				}	
			}
			else{
				alert("Seleccione una diferencia!!");
				document.preliquida.proc.value=0;
				return false;	
			}
		}
	}
}


function analisis(form){
	if (form.estrato.value == "" || form.estrato.value == null) {
		alert("Debe ingresar el estrato !!");
		form.estrato.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.zona.selectedIndex <= 0) {
		alert("Debe ingresar la zona !!");
		form.zona.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.localidad.selectedIndex <= 0) {
		alert("Debe ingresar la localidad !!");
		form.localidad.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.barrio.selectedIndex <= 0) {
		alert("Debe ingresar el barrio !!");
		form.barrio.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.porcion.selectedIndex <= 0) {
		alert("Debe ingresar la porción !!");
		form.porcion.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.interlocutor.value == "" || form.interlocutor.value == null) {
		alert("Debe ingresar el interlocutor !!");
		form.interlocutor.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.deuda.value == "" || form.deuda.value == null) {
		alert("Debe ingresar el valor de la deuda SAP !!");
		form.deuda.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.operario.selectedIndex <= 0) {
		alert("Debe ingresar el operario !!");
		form.operario.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.clase.selectedIndex <= 0) {
		alert("Debe ingresar la clase de uso !!");
		form.clase.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.uh.value == "" || form.uh.value == null) {
		alert("Debe ingresar las unidades habitacionales !!");
		form.uh.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.unh.value == "" || form.unh.value == null) {
		alert("Debe ingresar las unidades no habitacionales !!");
		form.unh.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.fecinicio.value == "" || form.fecinicio.value == null) {
		alert("Debe ingresar la fecha de inicio de cobro !!");
		form.fecinicio.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.lecinicio.value == "" || form.lecinicio.value == null) {
		alert("Debe ingresar la lectura de inicio !!");
		form.lecinicio.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.tipologia.selectedIndex <= 0) {
		alert("Debe ingresar la tipología !!");
		form.tipologia.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.anomalia.selectedIndex <= 0) {
		alert("Debe ingresar la anomalia !!");
		form.anomalia.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.resultado.selectedIndex <= 0) {
		alert("Debe ingresar el resultado del analisis !!");
		form.resultado.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.causa.selectedIndex <= 0 && form.resultado.selectedIndex == 2) {
		alert("Debe ingresar la causa de no procedencia !!");
		form.causa.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.procedimiento.value == "" || form.procedimiento.value == null) {
		alert("Debe ingresar el procedimiento realizado !!");
		form.procedimiento.focus();
		form.proc.value=0;
		return false;	
	}
	else {
	   form.proc.value = 1;
	   return true;
	}
}


function liquidaciones (form){
	if (form.valor_taponamiento.selectedIndex <= 0) {
		alert("Debe ingresar el valor del taponamiento !!");
		form.valor_taponamiento.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.valor_investigacion.selectedIndex <= 0) {
		alert("Debe ingresar el valor de la investigación !!");
		form.valor_investigacion.focus();
		form.proc.value=0;
		return false;	
	}
	else {
	   form.proc.value = 2;
	   return true;
	}
}

function liquidacionesa (form){
	if (form.analista.selectedIndex <= 0) {
		alert("Debe seleccionar el analista !!");
		form.analista.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.usua2.value == "") {
		alert("Debe digitar el nombre !!");
		form.usua2.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.vinculo.value == "") {
		alert("Debe seleccionar el vinculo con el predio !!");
		form.vinculo.focus();
		form.proc.value=0;
		return false;	
	}
	
	if (form.valor_taponamiento.selectedIndex <= 0) {
		alert("Debe ingresar el valor del taponamiento !!");
		form.valor_taponamiento.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.valor_investigacion.selectedIndex <= 0) {
		alert("Debe ingresar el valor de la investigación !!");
		form.valor_investigacion.focus();
		form.proc.value=0;
		return false;	
	}
	else {
	   form.proc.value = 2;
	   return true;
	}
}

function liquidacionesb (form){
	if (form.analista.selectedIndex <= 0) {
		alert("Debe seleccionar el analista !!");
		form.analista.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.usua2.value == "") {
		alert("Debe digitar el nombre !!");
		form.usua2.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.vinculo.value == "") {
		alert("Debe seleccionar el vinculo con el predio !!");
		form.vinculo.focus();
		form.proc.value=0;
		return false;	
	}
	
	if (form.valor_taponamiento.selectedIndex <= 0) {
		alert("Debe ingresar el valor del taponamiento !!");
		form.valor_taponamiento.focus();
		form.proc.value=0;
		return false;	
	}
	if (form.valor_investigacion.selectedIndex <= 0) {
		alert("Debe ingresar el valor de la investigación!!");
		form.valor_investigacion.focus();
		form.proc.value=0;
		return false;	
	}
	else {
	   form.proc.value = 3;
	   return true;
	}
}
function subeanexos(form) {							  
    if (form.anexo.value == "" || form.anexo.value == null) {
	   alert("Debe seleccionar el archivo a cargar !!");
	   form.anexo.focus();
   		return false;
	}
    if (form.actas.selectedIndex <= 0) {
	   alert("Debe selecionar el acta !!");
	   form.actas.focus();
   		return false;
	}
	else {
		form.proc.value = 3;
	}
}

function validarBotonRadio() {
  var s = "no";
  with (document.formulario){
    for ( var i = 0; i < sexo.length; i++ ) {
      if ( sexo.checked ) {
      s= "si";
      window.alert("Ha seleccionado: \n" + sexo.value);
      break;
      }
    }
    if ( s == "no" ){
      window.alert("Debe seleccionar hombre o mujer" ) ;
    }
  }
}

function descacuenta (form){
	if (!form.formdes[0].checked && !form.formdes[1].checked && !form.formdes[2].checked && !form.formdes[3].checked && !form.formdes[4].checked) {
		alert("Debe seleccionar un tipo de formato de descargo !!");
		form.proc.value=0;
		return false;
   	}
	if(form.formdes[0].checked || form.formdes[1].checked || form.formdes[3].checked || form.formdes[4].checked){	
		if(form.usuariodes.value == ""){
			alert("Debe ingresar el usuario que interpuso el descargo !!");
			form.usuariodes.focus();
			form.proc.value=0;
			return false;
		}
		if(form.dirdes.value == ""){
			alert("Debe ingresar la dirección del usuario !!");
			form.dirdes.focus();
			form.proc.value=0;
			return false;
		}
		if(form.teldes.value == ""){
			alert("Debe ingresar el teléfono del usuario !!");
			form.teldes.focus();
			form.proc.value=0;
			return false;
		}
		if(form.cedula.value == ""){
			alert("Debe ingresar la cédula del usuario !!");
			form.cedula.focus();
			form.proc.value=0;
			return false;
		}
		if(form.numrad.value == ""){
			alert("Debe ingresar el numero de radicado !!");
			form.numrad.focus();
			form.proc.value=0;
			return false;
		}
		if(form.fecrad.value == ""){
			alert("Debe ingresar la fecha de radicación !!");
			form.fecrad.focus();
			form.proc.value=0;
			return false;
		}
		if(form.fecnoti.value == ""){
			alert("Debe ingresar la fecha de notificación al usuario del pliego de cargos !!");
			form.fecnoti.focus();
			form.proc.value=0;
			return false;
		}
		else {
		   form.proc.value = 2;
		   return true;
		}
	}
	if(form.formdes[2].checked){
		if(form.usuariodes.value == ""){
			alert("Debe ingresar el usuario que interpuso el descargo !!");
			form.usuariodes.focus();
			form.proc.value=0;
			return false;
		}
		if(form.dirdes.value == ""){
			alert("Debe ingresar la dirección del usuario !!");
			form.dirdes.focus();
			form.proc.value=0;
			return false;
		}
		if(form.teldes.value == ""){
			alert("Debe ingresar el teléfono del usuario !!");
			form.teldes.focus();
			form.proc.value=0;
			return false;
		}
		if(form.cedula.value == ""){
			alert("Debe ingresar la cédula del usuario !!");
			form.cedula.focus();
			form.proc.value=0;
			return false;
		}
		if(form.numrad.value == ""){
			alert("Debe ingresar el numero de radicado !!");
			form.numrad.focus();
			form.proc.value=0;
			return false;
		}
		if(form.fecrad.value == ""){
			alert("Debe ingresar la fecha de radicación !!");
			form.fecrad.focus();
			form.proc.value=0;
			return false;
		}
		if(form.fecnoti.value == ""){
			alert("Debe ingresar la fecha de notificación al usuario del pliego de cargos !!");
			form.fecnoti.focus();
			form.proc.value=0;
			return false;
		}
		if(form.memorando.value == ""){
			alert("Debe ingresar el número del memorando del laboratorio de medidores!!");
			form.memorando.focus();
			form.proc.value=0;
			return false;
		}
		else {
		   form.proc.value = 2;
		   return true;
		}
	}
}


function pliegosc (form){
	if (!form.tipopliego[0].checked && !form.tipopliego[1].checked && !form.tipopliego[2].checked && !form.tipopliego[3].checked && !form.tipopliego[4].checked) {
		alert("Debe seleccionar un tipo de pliego !!");
		form.proc.value=0;
		return false;
   	}
   	if(form.tipopliego[0].checked){		
		if(form.feclec.value == ""){
			alert("Debe ingresar la fecha de lectura periodica !!");
			form.feclec.focus();
			form.proc.value=0;
			return false;
		}		
		if(form.acta_tapon.value == ""){
			alert("Debe ingresar el numero del acta de taponamiento !!");
			form.acta_tapon.focus();
			form.proc.value=0;
			return false;
		}
		if (form.observacion.value == "" || form.observacion.value == null) {
			alert("Debe ingresar la observación técnica!!");
			form.observacion.focus();
			form.proc.value=0;
			return false;	
		}
		if(!form.atendio.checked && !form.solo.checked){
			alert("Debe chequear si se atendio la visita o si el predio estaba solo!!");
			form.atendio.focus();
			form.proc.value=0;
			return false;	
		}
		else {
		   form.proc.value = 1;
		   return true;
		}
	}
	if(form.tipopliego[1].checked){		
		if(form.feclec.value == ""){
			alert("Debe ingresar la fecha de lectura periodica !!");
			form.feclec.focus();
			form.proc.value=0;
			return false;
		}		
		if (form.observacion.value == "" || form.observacion.value == null) {
			alert("Debe ingresar la observación técnica!!");
			form.observacion.focus();
			form.proc.value=0;
			return false;	
		}
		if(!form.atendio.checked && !form.solo.checked){
			alert("Debe chequear si se atendio la visita o si el predio estaba solo!!");
			form.atendio.focus();
			form.proc.value=0;
			return false;	
		}
		else {
		   form.proc.value = 1;
		   return true;
		}
	}
	if(form.tipopliego[2].checked){		
		if(form.fecdict.value == ""){
			alert("Debe ingresar la fecha de dictamen del laboratorio !!");
			form.fecdict.focus();
			form.proc.value=0;
			return false;
		}
		if(form.medinst.value == ""){
			alert("Debe ingresar el número del medidor nuevo instalado !!");
			form.medinst.focus();
			form.proc.value=0;
			return false;
		}
		if(form.lecinst.value == ""){
			alert("Debe ingresar la lectura del medidor instalado !!");
			form.lecinst.focus();
			form.proc.value=0;
			return false;
		}
		if (form.observacion.value == "" || form.observacion.value == null) {
			alert("Debe ingresar la observación técnica!!");
			form.observacion.focus();
			form.proc.value=0;
			return false;	
		}
		if (form.observaciont2.value == "" || form.observaciont2.value == null) {
			alert("Debe ingresar la observación del aviso T2!!");
			form.observaciont2.focus();
			form.proc.value=0;
			return false;	
		}		
		if (form.observacionlab.value == "" || form.observacionlab.value == null) {
			alert("Debe ingresar la observación del laboratorio de medidores!!");
			form.observacionlab.focus();
			form.proc.value=0;
			return false;	
		}
		if(!form.atendio.checked && !form.solo.checked){
			alert("Debe chequear si se atendio la visita o si el predio estaba solo!!");
			form.atendio.focus();
			form.proc.value=0;
			return false;	
		}
		else {
		   form.proc.value = 1;
		   return true;
		}
	}
	
	if(form.tipopliego[3].checked){		
		if(form.feclec.value == ""){
			alert("Debe ingresar la fecha de lectura periodica !!");
			form.feclec.focus();
			form.proc.value=0;
			return false;
		}
		if(form.lectper.value == ""){
			alert("Debe ingresar la lectura periodica !!");
			form.lectper.focus();
			form.proc.value=0;
			return false;
		}
		if(form.acta_tapon.value == ""){
			alert("Debe ingresar el numero del acta de taponamiento !!");
			form.acta_tapon.focus();
			form.proc.value=0;
			return false;
		}
		if (form.observacion.value == "" || form.observacion.value == null) {
			alert("Debe ingresar la observación técnica!!");
			form.observacion.focus();
			form.proc.value=0;
			return false;	
		}
		if(!form.atendio.checked && !form.solo.checked){
			alert("Debe chequear si se atendio la visita o si el predio estaba solo!!");
			form.atendio.focus();
			form.proc.value=0;
			return false;	
		}
		else {
		   form.proc.value = 1;
		   return true;
		}
	}
	
	if(form.tipopliego[4].checked){		
		if(!form.facturacion.checked){			
			if(form.mora.value == ""){
				alert("Debe ingresar el valor en mora !!");
				form.mora.focus();
				form.proc.value=0;
				return false;
			}
		}		
		if (form.observacion.value == "" || form.observacion.value == null) {
			alert("Debe ingresar la observación técnica!!");
			form.observacion.focus();
			form.proc.value=0;
			return false;	
		}
		if(!form.atendio.checked && !form.solo.checked){
			alert("Debe chequear si se atendio la visita o si el predio estaba solo!!");
			form.atendio.focus();
			form.proc.value=0;
			return false;	
		}
		else {
		   form.proc.value = 1;
		   return true;
		}
	}
}


function checkform (form){
	if(!form.obsok[0].checked && !form.obsok[1].checked){
		alert("Debe seleccionar si esta ok o no !!");			
			form.proc.value=0;
			return false;	
	}
	if(form.obsok[0].checked){
		 form.proc.value = 1;
		  return true;	
	}	
	if(form.obsok[1].checked){
		if (form.obs_forma.value == "" || form.obs_forma.value == null) {
			alert("Debe ingresar la observación de revision !!");
			form.obs_forma.focus();
			form.proc.value=0;
			return false;	
		}
		else{
			 form.proc.value = 1;
			 return true;	
		}
	}
	if(form.obsok[2].checked){		
		form.proc.value = 2;
		return true;	
	}
}

function checkformfondo (form){
	if(!form.obsfonok[0].checked && !form.obsfonok[1].checked && !form.obsfonok[2].checked){
		alert("Debe seleccionar si esta ok o no !!");			
			form.proc.value=0;
			return false;	
	}
	if(form.obsfonok[0].checked){
		 form.proc.value = 2;
		  return true;	
	}	
	if(form.obsfonok[1].checked){
		if (form.obs_fondo.value == "" || form.obs_fondo.value == null) {
			alert("Debe ingresar la observación de revision !!");
			form.obs_fondo.focus();
			form.proc.value=0;
			return false;	
		}
		else{
			 form.proc.value = 2;
			 return true;	
		}
	}
	if(form.obsfonok[2].checked){		
		form.proc.value = 2;
		return true;	
	}
}

function descuenta (form){
}

function dcori(form){
	if(form.cori.value == "" || form.cori.value == null){
		alert("Debe ingresar la salida CORI !!");
		form.cori.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fechacori.value == "" || form.fechacori.value == null){
		alert("Debe ingresar la fecha de salida CORI !!");
		form.fechacori.focus();
		form.proc.value=0;
		return false;
	}
	else{
		form.proc.value = 1;
		return true;	
	}
}

function dcorid(form){
	if(form.cori.value == "" || form.cori.value == null){
		alert("Debe ingresar la salida CORI !!");
		form.cori.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fechacori.value == "" || form.fechacori.value == null){
		alert("Debe ingresar la fecha de salida CORI !!");
		form.fechacori.focus();
		form.proc.value=0;
		return false;
	}
	else{
		form.proc.value = 2;
		return true;	
	}
}

function dcoria(form){
	if(form.cori.value == "" || form.cori.value == null){
		alert("Debe ingresar la salida CORI !!");
		form.cori.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fechacori.value == "" || form.fechacori.value == null){
		alert("Debe ingresar la fecha de salida CORI !!");
		form.fechacori.focus();
		form.proc.value=0;
		return false;
	}
	else{
		form.proc.value = 3;
		return true;	
	}
}

function dcoric(form){
	if(form.cori.value == "" || form.cori.value == null){
		alert("Debe ingresar la salida CORI !!");
		form.cori.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fechacori.value == "" || form.fechacori.value == null){
		alert("Debe ingresar la fecha de salida CORI !!");
		form.fechacori.focus();
		form.proc.value=0;
		return false;
	}
	else{
		form.proc.value = 4;
		return true;	
	}
}

function dcorir(form){
	if(form.cori.value == "" || form.cori.value == null){
		alert("Debe ingresar la salida CORI !!");
		form.cori.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fechacori.value == "" || form.fechacori.value == null){
		alert("Debe ingresar la fecha de salida CORI !!");
		form.fechacori.focus();
		form.proc.value=0;
		return false;
	}
	else{
		form.proc.value = 5;
		return true;	
	}
}


function acacuenta(form){
	if (!form.actoadmin[0].checked && !form.actoadmin[1].checked && !form.actoadmin[2].checked && !form.actoadmin[3].checked && !form.actoadmin[4].checked) {
		alert("Debe seleccionar un tipo de Acto Administrativo !!");
		form.proc.value=0;
		return false;
   	}
	if(form.actoadmin[2].checked){		
		if(form.num_memorando.value == "" || form.num_memorando.value == null) {
			alert("Debe ingresar el número de memorando emitido por el laboratorio de medidores !!");
			form.num_memorando.focus();
			form.proc.value=0;
			return false;
		}
	}	
 	if(form.notificacion.selectedIndex <= 0) {
		alert("Debe ingresar el tipo de notificación !!");
		form.notificacion.focus();
		form.proc.value=0;
		return false;
	}
	if(form.notificacion.selectedIndex == 1) {
		if(form.fechanotper.value == "" || form.fechanotper.value == null){
			alert("Debe ingresar la fecha de notificación personal !!");
			form.fechanotper.focus();
			form.proc.value=0;
			return false;
		}
	}
	if(form.notificacion.selectedIndex == 2) {
		if(form.fechafijacion.value == "" || form.fechafijacion.value == null){
			alert("Debe ingresar la fecha de fijacion !!");
			form.fechafijacion.focus();
			form.proc.value=0;
			return false;
		}
		if(form.fechadesfijacion.value == "" || form.fechadesfijacion.value == null){
			alert("Debe ingresar la fecha de desfijacion !!");
			form.fechadesfijacion.focus();
			form.proc.value=0;
			return false;
		}
		else {
	   		form.proc.value = 3;
	   		return true;
		}
	}
	else {
	   form.proc.value = 3;
	   return true;
	}
}

function repcuenta(form){
	if(form.usuariorep.value == "" || form.usuariorep.value == null){
		alert("Debe ingresar el nombre del usuario que interpuso el recurso !!");
		form.usuariorep.focus();
		form.proc.value=0;
		return false;
	}
	if(form.dirnot.value == "" || form.dirnot.value == null){
		alert("Debe ingresar la dirección de notificación !!");
		form.dirnot.focus();
		form.proc.value=0;
		return false;
	}
	if(form.telnot.value == "" || form.telnot.value == null){
		alert("Debe ingresar el teléfono de notificación !!");
		form.telnot.focus();
		form.proc.value=0;
		return false;
	}
	if(form.numrad.value == "" || form.numrad.value == null){
		alert("Debe ingresar el número de radicación del recurso !!");
		form.numrad.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fecrad.value == "" || form.fecrad.value == null){
		alert("Debe ingresar la fecha de radicación del recurso !!");
		form.fecrad.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fecnoti.value == "" || form.fecnoti.value == null){
		alert("Debe ingresar la fecha de notificación !!");
		form.fecnoti.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fecmax.value == "" || form.fecmax.value == null){
		alert("Debe ingresar la fecha máxima de interposición del recurso !!");
		form.fecmax.focus();
		form.proc.value=0;
		return false;
	}
	if(form.fecint.value == "" || form.fecint.value == null){
		alert("Debe ingresar la fecha de interposición del recurso !!");
		form.fecint.focus();
		form.proc.value=0;
		return false;
	}
	else{
		form.proc.value = 4;
		return true;	
	}
}

function comunicac(form){	
	form.proc.value = 1;
	return true;	
}

function expedientec(form){	
	form.proc.value = 2;
	return true;	
}

function azbc(form){
	if(form.numazb.value == "" || form.numazb.value == null){
		alert("Debe ingresar el número de AZB !!");
		form.numazb.focus();
		form.proc.value = 0;
		return false;
	}
	else{
		form.proc.value = 4;
		return true;
	}
}





































function validactacp() {		
	if (document.actual.nacta.value == "" || document.actual.nacta.value == null){
		alert("Debe ingresar el numero de acta !!");
		document.actual.nacta.focus();		
		return false;	
	}
	if (document.actual.nacta.value.length < 5 || document.actual.nacta.value.length > 5){
		alert("El número de acta debe contener cinco digitos!!");
   		document.actual.nacta.focus();		
		return false; 
	}		
	if (document.actual.facta.value == "" || document.actual.facta.value == null){
		alert("Debe ingresar la fecha de acta !!");
		document.actual.facta.focus();		
		return false;	
	}
	if ((document.actual.suscriptor.value == "" || document.actual.suscriptor.value == null) && (document.actual.user.value == "" || document.actual.user.value == null)){
		alert("Debe ingresar el nombre del usuario o el nombre del suscriptor !!");
		document.actual.suscriptor.focus();		
		return false;	
	}
	
	if (document.actual.encalidad.selectedIndex <= 0){
		alert("Debe seleccionar el campo en calidad !!");
		document.actual.encalidad.focus();		
		return false;	
	}	
	
	if (document.actual.dirterreno.value == "" || document.actual.dirterreno.value == null){
		alert("Debe ingresar la direccion encontrada en terreno !!");
		document.actual.dirterreno.focus();		
		return false;	
	}
	if (document.actual.zona.selectedIndex <= 0){
		alert("Debe seleccionar la zona !!");
		document.actual.zona.focus();		
		return false;	
	}	
	if (document.actual.localidad.selectedIndex <= 0){
		alert("Debe seleccionar la localidad !!");
		document.actual.localidad.focus();		
		return false;	
	}		
	if (document.actual.barrio.value == "" || document.actual.barrio.value == null){
		alert("Debe ingresar el barrio !!");
		document.actual.barrio.focus();		
		return false;	
	}	
	if (document.actual.clase.selectedIndex <= 0){
		alert("Debe seleccionar la clase de uso !!");
		document.actual.clase.focus();		
		return false;	
	}	
	if (document.actual.sector.selectedIndex <= 0){
		alert("Debe seleccionar el sector !!");
		document.actual.sector.focus();		
		return false;	
	}	
	if (document.actual.uhabita.value == "" || document.actual.uhabita.value == null){
		alert("Debe ingresar el número de unidades habitacionales !!");
		document.actual.uhabita.focus();		
		return false;	
	}
	if (document.actual.unohabita.value == "" || document.actual.unohabita.value == null){
		alert("Debe ingresar el número de unidades no habitacionales !!");
		document.actual.unohabita.focus();		
		return false;	
	}
	if (document.actual.marcam.selectedIndex <= 0){
		alert("Debe seleccionar la marca del medidor si no existe medidor seleccione SIN MEDIDOR !!");
		document.actual.efectividad.focus();		
		return false;	
	}
	if (document.actual.diametrom.selectedIndex <= 0){
		alert("Debe seleccionar el diametro !!");
		document.actual.diametrom.focus();		
		return false;	
	}		
	if (document.actual.efectividad.selectedIndex <= 0){
		alert("Debe seleccionar la efectividad de la visita !!");
		document.actual.efectividad.focus();		
		return false;	
	}	
	if (document.actual.efectividad.selectedIndex == 2 && document.actual.causa.selectedIndex <= 0){
		alert("Debe seleccionar la causa de la inefectividad de la visita !!");
		document.actual.causa.focus();		
		return false;	
	}	
	if (document.actual.anomalia.selectedIndex <= 0){
		alert("Debe seleccionar la anomalia encontrada en terreno !!");
		document.actual.anomalia.focus();		
		return false;	
	}	
	if (document.actual.estado.selectedIndex <= 0){
		alert("Debe seleccionar el estado de la visita !!");
		document.actual.estado.focus();		
		return false;	
	}	
	if (document.actual.inicial.value == "" || document.actual.inicial.value == null){
		alert("Debe ingresar el rango inicial de las fotos !!");
		document.actual.inicial.focus();		
		return false;	
	}
	if (document.actual.final.value == "" || document.actual.final.value == null){
		alert("Debe ingresar el rango final de las fotos !!");
		document.actual.final.focus();		
		return false;	
	}
	if (document.actual.inspector.selectedIndex <= 0){
		alert("Debe seleccionar el inspector que realizo la visita !!");
		document.actual.inspector.focus();		
		return false;	
	}	
 	else { 
	   document.actual.proc.value = 1;	   
	   return true; 
	}	
}		


function validaliquida(){
	if (document.liquidacion.metros.value == "" || document.liquidacion.metros.value == null){
			alert("Debe ingresar el número de metros facturados !!");
			document.liquidacion.metros.focus();		
			return false;	
	}
	if (document.liquidacion.consumo.value == "" || document.liquidacion.consumo.value == null){
			alert("Debe ingresar el consumo promedio !!");
			document.liquidacion.consumo.focus();		
			return false;	
	}
	if(document.liquidacion.valor_taponamiento.selectedIndex <= 0){
			alert("Debe seleccionar el valor del taponamiento !!");
			document.liquidacion.valor_taponamiento.focus();
			return false;
	}
	if(document.liquidacion.valor_investigacion.selectedIndex <= 0){
			alert("Debe seleccionar el valor de la investigación !!");
			document.liquidacion.valor_investigacion.focus();
			return false;
	}
	if (document.liquidacion.valor_espacio.value == "" || document.liquidacion.valor_espacio.value == null){
			alert("Debe ingresar el valor de espacio público !!");
			document.liquidacion.valor_espacio.focus();		
			return false;	
	}
	else { 
	   		document.liquidacion.proc.value = 1;
			document.liquidacion.submit();
	 	    return true;
	}
}

function validacasomod(){
	document.caso.proc.value = 1;
	document.caso.submit();
    return true; 
}

function validafotos(){
	document.fotos.proc.value = 1;
	document.fotos.submit();
    return true; 
}

function validacaso(){
	if(document.caso.res_efectividad.selectedIndex <= 0 || document.caso.res_efectividad.selectedIndex == 1){					
		if(document.caso.tipopredio.selectedIndex <= 0){
			alert("Debe seleccionar el tipo de predio !!");
			document.caso.tipopredio.focus();
			return false;
		}
		if (document.caso.estrato.value == "" || document.caso.estrato.value == null){
			alert("Debe ingresar el estrato !!");
			document.caso.estrato.focus();		
			return false;	
		}
		if (document.caso.porciona.selectedIndex <= 0){
			alert("Debe ingresar la porcion !!");
			document.caso.porciona.focus();		
			return false;	
		}
		if (document.caso.t2.value == "" || document.caso.t2.value == null){
			alert("Debe ingresar el número de aviso T2 !!");
			document.caso.t2.focus();		
			return false;	
		}		
		if (document.caso.rubi.value == "" || document.caso.rubi.value == null){
			alert("Debe ingresar el código AZB !!");
			document.caso.rubi.focus();		
			return false;	
		}		
		if (document.caso.finicio.value == "" || document.caso.finicio.value == null){
			alert("Debe ingresar la fecha de inicio de cobro defraudación !!");
			document.caso.finicio.focus();		
			return false;	
		}		
		if (document.caso.linicio.value == "" || document.caso.linicio.value == null){
			alert("Debe ingresar la lectura de inicio de liquidación !!");
			document.caso.linicio.focus();		
			return false;	
		}				
		if (document.caso.interlocutor.value == "" || document.caso.interlocutor.value == null){
			alert("Debe ingresar el interlocutor comercial !!");
			document.caso.interlocutor.focus();		
			return false;	
		}
		if(document.caso.anomalia.selectedIndex <= 0){
			alert("Debe seleccionar la anomalìa del analista !!");
			document.caso.anomalia.focus();
			return false;
		}
		if(document.caso.anomalia.selectedIndex == 4){
			if(document.caso.fRC.value == "" || document.caso.fRC.value == null){
				alert("Debe seleccionar la fecha del reporte de calibración !!");
				document.caso.fRC.focus();
				return false;
			}
			if(document.caso.dRC.value == "" || document.caso.dRC.value == null){
				alert("Debe seleccionar la descripción del reporte de calibración !!");
				document.caso.dRC.focus();
				return false;
			}			
		}
		if(document.caso.res_efectividad.selectedIndex <= 0){
			alert("Debe seleccionar el resultado de efectividad !!");
			document.caso.res_efectividad.focus();
			return false;
		}
		if (document.caso.procedimiento.value == "" || document.caso.procedimiento.value == null){
			alert("Debe ingresar el procedimiento realizado !!");
			document.caso.procedimiento.focus();		
			return false;	
		}
		else { 
	   		document.caso.proc.value = 1;
			document.caso.submit();
	 	    return true; 
		}	
	}
	if(document.caso.res_efectividad.selectedIndex > 1){
		if(document.caso.anomalia.selectedIndex <= 0){
			alert("Debe seleccionar la anomalìa del analista !!");
			document.caso.anomalia.focus();
			return false;
		}
		if (document.caso.procedimiento.value == "" || document.caso.procedimiento.value == null){
			alert("Debe ingresar el procedimiento realizado !!");
			document.caso.procedimiento.focus();		
			return false;	
		}
		else { 
	   		document.caso.proc.value = 1;
			document.caso.submit();
	 	    return true; 
		}	
	}
}
function validacta() {		
	if (document.actual.nacta.value == "" || document.actual.nacta.value == null){
		alert("Debe ingresar el numero de acta !!");
		document.actual.nacta.focus();		
		return false;	
	}
	if (document.actual.nacta.value.length < 8 || document.actual.nacta.value.length > 8){
		alert("El número de acta no es valido. Ej: CP-***** !!");
   		document.actual.nacta.focus();		
		return false; 
	}		
	if (document.actual.facta.value == "" || document.actual.facta.value == null){
		alert("Debe ingresar la fecha de acta !!");
		document.actual.facta.focus();		
		return false;	
	}
	if ((document.actual.suscriptor.value == "" || document.actual.suscriptor.value == null) && (document.actual.user.value == "" || document.actual.user.value == null)){
		alert("Debe ingresar el nombre del usuario o el nombre del suscriptor !!");
		document.actual.suscriptor.focus();		
		return false;	
	}
	if (document.actual.dirterreno.value == "" || document.actual.dirterreno.value == null){
		alert("Debe ingresar la direccion encontrada en terreno !!");
		document.actual.dirterreno.focus();		
		return false;	
	}
	if (document.actual.zona.selectedIndex <= 0){
		alert("Debe seleccionar la zona !!");
		document.actual.zona.focus();		
		return false;	
	}	
	if (document.actual.localidad.selectedIndex <= 0){
		alert("Debe seleccionar la localidad !!");
		document.actual.localidad.focus();		
		return false;	
	}		
	if (document.actual.barrio.value == "" || document.actual.barrio.value == null){
		alert("Debe ingresar el barrio !!");
		document.actual.barrio.focus();		
		return false;	
	}	
	if (document.actual.clase.selectedIndex <= 0){
		alert("Debe seleccionar la clase de uso !!");
		document.actual.clase.focus();		
		return false;	
	}	
	if (document.actual.uhabita.value == "" || document.actual.uhabita.value == null){
		alert("Debe ingresar el número de unidades habitacionales !!");
		document.actual.uhabita.focus();		
		return false;	
	}
	if (document.actual.unohabita.value == "" || document.actual.unohabita.value == null){
		alert("Debe ingresar el número de unidades no habitacionales !!");
		document.actual.unohabita.focus();		
		return false;	
	}
	if (document.actual.marcam.selectedIndex <= 0){
		alert("Debe seleccionar la marca del medidor si no existe medidor seleccione SIN MEDIDOR !!");
		document.actual.efectividad.focus();		
		return false;	
	}
	if (document.actual.diametrom.selectedIndex <= 0){
		alert("Debe seleccionar el diametro !!");
		document.actual.diametrom.focus();		
		return false;	
	}		
	if (document.actual.efectividad.selectedIndex <= 0){
		alert("Debe seleccionar la efectividad de la visita !!");
		document.actual.efectividad.focus();		
		return false;	
	}	
	if (document.actual.efectividad.selectedIndex == 2 && document.actual.causa.selectedIndex <= 0){
		alert("Debe seleccionar la causa de la inefectividad de la visita !!");
		document.actual.causa.focus();		
		return false;	
	}	
	if (document.actual.anomalia.selectedIndex <= 0){
		alert("Debe seleccionar la anomalia encontrada en terreno !!");
		document.actual.anomalia.focus();		
		return false;	
	}	
	if (document.actual.estado.selectedIndex <= 0){
		alert("Debe seleccionar el estado de la visita !!");
		document.actual.estado.focus();		
		return false;	
	}	
	if (document.actual.inicial.value == "" || document.actual.inicial.value == null){
		alert("Debe ingresar el rango inicial de las fotos !!");
		document.actual.inicial.focus();		
		return false;	
	}
	if (document.actual.final.value == "" || document.actual.final.value == null){
		alert("Debe ingresar el rango final de las fotos !!");
		document.actual.final.focus();		
		return false;	
	}
	if (document.actual.inspector.selectedIndex <= 0){
		alert("Debe seleccionar el inspector que realizo la visita !!");
		document.actual.inspector.focus();		
		return false;	
	}	
 	else { 
	   document.actual.proc.value = 1;
	   document.actual.submit();
	   return true; 
	}	
}		

/*function validainfo() {
	document.infocargue.proc.value = 1;
	//document.infocargue.submit();
	return true; 
}*/

function valida() {
	if (document.ingreso.usuario.value == "" || document.ingreso.usuario.value == null) {
		alert("Debe ingresar el nombre de usuario !!");
		document.ingreso.usuario.focus();
		return false;	
	}
	if (document.ingreso.password.value == "" || document.ingreso.password.value == null) {
		alert("Debe ingresar la contraseña !!");
		document.ingreso.password.focus();
		return false;	
	}		
	if (document.ingreso.modulo.selectedIndex <= 0) {
		alert("Debe seleccionar el modulo !!");
		document.ingreso.modulo.focus();
		return false;	
	}			
}		

function validareport_actas() {	
	if (document.reporteactas.numacta.value == "" || document.reporteactas.numacta.value == null) {
		alert("Debe ingresar el numero de acta !!");
		document.reporteactas.numacta.focus();
		return false;	
	}
	else { 
	   document.reporteactas.proc.value = 1;
	   document.reporteactas.submit();
	   return true; 
	}	
}


function validareporte1() {
	if (document.reporte1.fechainicio.value == "" || document.reporte1.fechainicio.value == null) {
		alert("Debe ingresar la fecha inicial !!");
		document.reporte1.fechainicio.focus();
		return false;	
	}
	if (document.reporte1.fechafin.value == "" || document.reporte1.fechafin.value == null) {
		alert("Debe ingresar la fecha fin !!");
		document.reporte1.fechafin.focus();
		return false;	
	}
	else { 
	   document.reporte1.proc.value = 1;
	   document.reporte1.submit();
	   return true; 
	}	
}

function validareporte2() {
	if (document.reporte2.fechainicio.value == "" || document.reporte2.fechainicio.value == null) {
		alert("Debe ingresar la fecha inicial !!");
		document.reporte2.fechainicio.focus();
		return false;	
	}
	if (document.reporte2.fechafin.value == "" || document.reporte2.fechafin.value == null) {
		alert("Debe ingresar la fecha fin !!");
		document.reporte2.fechafin.focus();
		return false;	
	}
	else { 
	   document.reporte2.proc.value = 1;
	   document.reporte2.submit();
	   return true; 
	}	
}		



//FUNCION QUE VERIFICA QUE LOS DATOS DEL FORMULARIO DE REGISTRO DE VISITAS TENGA LOS CAMPOS OBLIGATORIOS

function validav() {		
	/*if (document.actualiza.motivo.selectedIndex <= 0) {
		alert("Debe seleccionar el motivo !!");
		document.actualiza.motivo.focus();		
		return false;	
	}*/
	if (document.actualiza.zona.selectedIndex <= 0) {
		alert("Debe seleccionar la zona !!");
		document.actualiza.zona.focus();		
		return false;	
	}
	if (document.actualiza.fechadesde.value == "" || document.actualiza.fechadesde.value == null) {
		alert("Debe ingresar la fecha desde !!");
		document.actualiza.fechadesde.focus();	
		return false;	
	}				
	if (document.actualiza.fechahasta.value == "" || document.actualiza.fechahasta.value == null) {
		alert("Debe ingresar la fecha hasta !!");
		document.actualiza.fechahasta.focus();		
		return false;	
	}				
 	else { 
	   document.actualiza.proc.value = 1;
	   document.actualiza.submit();
	   return true; 
	}	
}	

function validacom() {		
	
	if (document.comunicacion.zona.selectedIndex <= 0) {
		alert("Debe seleccionar la zona !!");
		document.comunicacion.zona.focus();		
		return false;	
	}
	if (document.comunicacion.fechadesde.value == "" || document.comunicacion.fechadesde.value == null) {
		alert("Debe ingresar la fecha desde !!");
		document.comunicacion.fechadesde.focus();	
		return false;	
	}				
	if (document.comunicacion.fechahasta.value == "" || document.comunicacion.fechahasta.value == null) {
		alert("Debe ingresar la fecha hasta !!");
		document.comunicacion.fechahasta.focus();		
		return false;	
	}				
 	else { 
	   document.comunicacion.proc.value = 1;
	   document.comunicacion.submit();
	   return true; 
	}	
}	

function validacori() {			
	if (document.cori.zona.selectedIndex <= 0) {
		alert("Debe seleccionar la zona !!");
		document.cori.zona.focus();		
		return false;	
	}
	if (document.cori.fechadesde.value == "" || document.cori.fechadesde.value == null) {
		alert("Debe ingresar la fecha desde !!");
		document.cori.fechadesde.focus();	
		return false;	
	}				
	if (document.cori.fechahasta.value == "" || document.cori.fechahasta.value == null) {
		alert("Debe ingresar la fecha hasta !!");
		document.cori.fechahasta.focus();		
		return false;	
	}				
 	else { 
	   document.cori.proc.value = 1;
	   document.cori.submit();
	   return true; 
	}	
}	

function validacoria() {		
	if (document.coria.cuentacon.value == "" || document.coria.cuentacon.value == null) {
		alert("Debe ingresar la cuenta contrato!!");
		document.coria.cuentacon.focus();		
		return false;	
	}				
 	else { 
	   document.coria.proc.value = 2;
	   document.coria.submit();
	   return true; 
	}	
}	

function validadatoscori() {	
	if (document.datoscori.cori.value == "" || document.datoscori.cori.value == null){
		alert("Debe seleccionar la salida cori !!");
		document.datoscori.cori.focus();		
		return false;	
	}
	if (document.datoscori.fcori.value == "" || document.datoscori.fcori.value == null){
		alert("Debe seleccionar la fecha de la salida cori !!");
		document.datoscori.fcori.focus();		
		return false;	
	}
	else { 
	   document.datoscori.proc.value = 1;
	   document.datoscori.submit();
	   return true; 
	}	
}


function validaa() {			
	if (document.analiza.zona.selectedIndex <= 0) {
		alert("Debe seleccionar la zona !!");
		document.analiza.zona.focus();		
		return false;	
	}
	if (document.analiza.fechadesde.value == "" || document.analiza.fechadesde.value == null){
		alert("Debe ingresar la fecha desde !!");
		document.analiza.fechadesde.focus();	
		return false;	
	}				
	if (document.analiza.fechahasta.value == "" || document.analiza.fechahasta.value == null){
		alert("Debe ingresar la fecha hasta !!");
		document.analiza.fechahasta.focus();		
		return false;	
	}				
 	else { 
	   document.analiza.proc.value = 1;
	   document.analiza.submit();
	   return true; 
	}	
}		

function validacargue() {			
	if (document.cargue.zona.selectedIndex <= 0) {
		alert("Debe seleccionar la zona !!");
		document.cargue.zona.focus();		
		return false;	
	}
	if (document.cargue.fechadesde.value == "" || document.cargue.fechadesde.value == null){
		alert("Debe ingresar la fecha desde !!");
		document.cargue.fechadesde.focus();	
		return false;	
	}				
	if (document.cargue.fechahasta.value == "" || document.cargue.fechahasta.value == null){
		alert("Debe ingresar la fecha hasta !!");
		document.cargue.fechahasta.focus();		
		return false;	
	}				
 	else { 
	   document.cargue.proc.value = 1;
	   document.cargue.submit();
	   return true; 
	}	
}	

function validacuenta() {			
	if (document.carguea.cuentacon.value == "" || document.carguea.cuentacon.value == null) {
		alert("Debe ingresar la cuenta contrato !!");
		document.carguea.cuentacon.focus();		
		return false;	
	}	
 	else { 
	   document.carguea.proc.value = 2;
	   document.carguea.submit();
	   return true; 
	}	
}

function validacuentaan() {			
	if (document.analizaa.cuentacon.value == "" || document.analizaa.cuentacon.value == null) {
		alert("Debe ingresar la cuenta contrato !!");
		document.analizaa.cuentacon.focus();		
		return false;	
	}	
 	else { 
	   document.analizaa.proc.value = 2;
	   document.analizaa.submit();
	   return true; 
	}	
}

function validacuentali() {			
	if (document.liquidara.cuentacon.value == "" || document.liquidara.cuentacon.value == null) {
		alert("Debe ingresar la cuenta contrato !!");
		document.liquidara.cuentacon.focus();		
		return false;	
	}	
 	else { 
	   document.liquidara.proc.value = 2;
	   document.liquidara.submit();
	   return true; 
	}	
}

function validacuentapru() {			
	if (document.pruebaa.cuentacon.value == "" || document.pruebaa.cuentacon.value == null) {
		alert("Debe ingresar la cuenta contrato !!");
		document.pruebaa.cuentacon.focus();		
		return false;	
	}	
 	else { 
	   document.pruebaa.proc.value = 2;
	   document.pruebaa.submit();
	   return true; 
	}	
}

function validal() {			
	if (document.liquidar.zona.selectedIndex <= 0) {
		alert("Debe seleccionar la zona !!");
		document.liquidar.zona.focus();		
		return false;	
	}
	if (document.liquidar.fechadesde.value == "" || document.liquidar.fechadesde.value == null){
		alert("Debe ingresar la fecha desde !!");
		document.liquidar.fechadesde.focus();	
		return false;	
	}				
	if (document.liquidar.fechahasta.value == "" || document.liquidar.fechahasta.value == null){
		alert("Debe ingresar la fecha hasta !!");
		document.liquidar.fechahasta.focus();		
		return false;	
	}				
 	else { 
	   document.liquidar.proc.value = 1;
	   document.liquidar.submit();
	   return true; 
	}	
}		




//FUNCION QUE VERIFICA QUE EL USUARIO INGRESE POR BD
function IngBase(vari,vari2) {  
	window.location.replace("./"+vari2+"/");
}

//FUNCION QUE VERIFICA SI EL USUARIO EXISTE EN LA BD
function UsuarioNoExiste() {
	alert('Nombre de usuario o contrase\xf1a incorrecta!!\n Por favor vuelva a intentarlo');
	document.ingreso.submit();
}

//FUNCION QUE VERIFICA SI EL USUARIO TIENE PERMISO AL MODULO
function PermisoModulo() {
	alert('Ud no tiene permisos para ingresar al modulo !!');
	document.ingreso.submit();
}

//FUNCION DE BORRAR REGISTRO DEL GRID
 function borrar_grid(form) {
    var j = 0, arch;
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type == 'radio') {
           if (form.elements[i].checked) { j++;
		      arch = form.elements[i].value;
			  form.proc.value = 5; }
	    }
    }
    if (j == 0) { alert ("No ha seleccionado el proceso para borrar !!"); return false; }
	else {
	   if (confirm("Desea borrar el proceso: " + arch + " ?")) form.del.value = arch; form.grid.submit();  }
  }  


//FUNCION PARA ABRIR UN POPUP
var popped = null;
function popup(uri, awid, ahei) {
	var params;
   	if (uri != "") {
		if (popped && !popped.closed) {
			popped.location.href = uri;
			popped.focus();
        } 
		else {
			params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=yes,scrollbars=yes,menubar=no,resizable=no";								
            popped = window.open(uri, "popup", params);
        }
	}
}

