
<script type="text/javascript">

//FUNCION PARA ABRIR UN POPUP
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

function cambioestado(id2) { // Traer la fila seleccionada
	popup("../Vistas/Popupcambioestado.php?user="+id2,110,80,'yes');
}

</script>