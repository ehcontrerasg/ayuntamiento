function cancelRefresh() {
  return true;
}
// Deshabilitar boton derecho
function notice() {
  menutext.style.left = document.body.scrollLeft+event.clientX;
  menutext.style.top = document.body.scrollTop+event.clientY;
  menutext.style.visibility = "visible";
  return false  }
	
function hidenotice() {
  menutext.style.visibility = "hidden";
}

window.status = "FELA ";

//<div id="menutext"><div url="">
//<script language="JavaScript" type="text/javascript">
//<!--
//document.oncontextmenu = notice;
//if (document.all && window.print) { document.body.onclick = hidenotice; }
//document.onkeydown = cancelRefresh;
// -->
//</script>
//</div></div>

var popped = null;

function popup(uri, awid, ahei) {
   var params;
   if (uri != "") {
        if (popped && !popped.closed) {
                popped.location.href = uri;
                popped.focus();
        } else {
				//awid = window.screen.availWidth-50;
				//ahei = window.screen.availHeight-80;
                params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=yes,menubar=no,resizable=no";
								//params = "toolbar=no,width=200,height=200,directories=no,status=yes,scrollbars=yes,menubar=no,resizable=no";
                popped = window.open(uri, "popup", params);
        }
		}
}


//CRONOMETRO
  var CronoID = null;
  var CronoEjecutandose = false;
  var decimas, segundos, minutos, horas;

  function DetenerCrono(){
    if (CronoEjecutandose) {
       clearTimeout(CronoID);
       CronoEjecutandose = false; }
  }

  function InicializarCrono() {  //inicializa contadores globales
    decimas = 0;
    segundos = 0;
    minutos = 0;
    horas = 0;

    //pone a cero los marcadores
    document.crono.display.value = '00:00:00:0';
    //document.crono.parcial.value = '00:00:00:0';
  }

  function MostrarCrono() {   //incrementa el crono
    decimas++;
    if ( decimas > 9 ) {
       decimas = 0;
       segundos++;
       if ( segundos > 59 ) {
          segundos = 0;
          minutos++;
          if ( minutos > 59 ) {
             minutos = 0;
             horas ++;
             if (horas > 99) {
                alert('Fin de la cuenta !!');
                DetenerCrono();
                return true;   }
          }
       }
    }
    //configura la salida
    var ValorCrono = "";
    ValorCrono = (horas < 10) ? "0" + horas : horas;
    ValorCrono += (minutos < 10) ? ":0" + minutos : ":" + minutos;
    ValorCrono += (segundos < 10) ? ":0" + segundos : ":" + segundos;
    ValorCrono += ":" + decimas; 

    document.crono.display.value = ValorCrono
    CronoID = setTimeout("MostrarCrono()", 100);
    CronoEjecutandose = true;
    return true;
  }

  function IniciarCrono() {
    DetenerCrono();
    InicializarCrono();
    MostrarCrono();
  }

  function ObtenerParcial() { //obtiene cuenta parcial
    document.crono.parcial.value = document.crono.display.value;
  }

