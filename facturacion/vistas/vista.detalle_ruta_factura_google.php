<?php
session_start();
include_once ('../../include.php');
$coduser = $_SESSION['codigo'];
$cod_operario = $_GET['cod_operario'];

$temporal=explode(" ",$cod_operario);
$cod_operario=$temporal[0];
$proyecto=$temporal[1];
$periodo=$temporal[2];
$cod_ruta=$temporal[3];
$fecini=$temporal[4];
$fecfin=$temporal[5];

$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
//echo '<br>';
?>
<html> 
<head> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script src="https://www.google.com/jsapi?key=ABQIAAAA1YNBrpDz-yi7J5-Jvk4CWxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQr8UbwBTNQXfe6c910iVmjrp4ZUQ"> </script> 

<script type="text/javascript"> 
 //<![CDATA[
var x,y;   
var map; 
var markersArray = []; 
var ge; 
var arreglo=''; 
var c1=0,c2=0,ca=''; 
var ruta = [];  
//,

//hoja de estilos  para boton aguazul
 
function HomeControl(controlDiv, map) {    
	// Set CSS styles for the DIV containing the control   
	// Setting padding to 5 px will offset the control   
	// from the edge of the map  
	 controlDiv.style.padding = '6px';    // Set CSS for the control border  
	 var controlUI = document.createElement('DIV');   
	 controlUI.style.backgroundColor = 'AliceBlue';   
	 controlUI.style.borderStyle = 'solid';   
	 controlUI.style.borderWidth = '1px';   
	 controlUI.style.cursor = 'pointer';   
	 controlUI.style.textAlign = 'center';   
	 controlUI.title = 'Click para volver';   
	 controlDiv.appendChild(controlUI);    
	 // Set CSS for the control interior   
	 var controlText = document.createElement('DIV');   
	 controlText.style.fontFamily = 'Arial,sans-serif';   
	 controlText.style.fontSize = '12px';   
	 controlText.style.paddingLeft = '5px';   
	 controlText.style.paddingRight = '5px';   
	 controlUI.appendChild(controlText);    
	 // Setup the click event listeners: simply set the map to  	
	 google.maps.event.addDomListener(controlUI, 'click', function() {      	 google.earth.createInstance('map_canvas', initCB, failureCB);    
// Create the placemark. 
	 }); 
	
      
 } 
 function newmap(xigeo,yigeo,cod_inm,fechafi){
 	//yi=yigeo;
	//xi=xigeo;
 	var myLatlng = new google.maps.LatLng(yigeo,xigeo);  
	var icon_red = '../../images/flag-red.png';	
 	var myOptions = {     
		zoom: 15,     
		center: myLatlng,
		// para mostrar la escala en el mapa
		scaleControl: true,     
		//scaleControlOptions: {position: google.maps.ControlPosition.TOP_LEFT },	
		//para los contoles de tipo de mapa
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },//DROPDOWN_MENU-DEFAULT -HORIZONTAL_BAR 
		//pata los controles de navegacion del mapa
		navigationControl: true,
		navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL },//SMALL -ZOOM_PAN -DEFAULT   -ANDROID 
		mapTypeId: google.maps.MapTypeId.ROADMAP   
	}   
	
 	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
 	var marker = new google.maps.Marker({       
		position: myLatlng,        
		map: map, 
		title: 'inmueble', 
		icon: icon_red      
	});  
 	 var contentString = '<div id="content"><left>Codigo Inmueble: '+cod_inm+'</left><p><left>Fecha Ent: '+fechafi+'</left> <p><center><a target="_blank" href="http://developermovil01.serveftp.net:8081/acea/facturacion/vistas/vista.fotos_factura.php?cod_sistema='+cod_inm+'&periodo=<?php echo $periodo; ?>">Ver fotos</a></center><p>Orden: 1</div>';

	 var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });
	 google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });

	
	
 }

 function ruteo(total_coordenadas){
 var xtot =total_coordenadas;
 	var elem = xtot.split('/');
 	var cantidad = elem.length;
 	for(i=0;i<cantidad-1;i++){
 		ca = elem[i].split(',');
 		c1 = ca[0];
 		c2 = ca[1];
 		ruta[i] = new google.maps.LatLng(c1,c2);
 	}
 	var lineas = new google.maps.Polyline({        
    path: ruta,
    map: map, 
    strokeColor: '#222000', 
    strokeWeight: 4,  
    strokeOpacity: 0.6, 
    clickable: false     });  
 }

function initialize(xgeo,ygeo,cod_inm,fechafi,sec) { 
//y=ygeo;
//x=xgeo;
var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
var icon_cred = '../../images/circle_red.png';	
	
	var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
	
	var marker = new google.maps.Marker({       
		position: myLatlng,        
		map: map,
		title: 'inmueble', 
		icon: icon_cred      
	});

	var contentString = '<div id="content"><left>Codigo Inmueble: '+cod_inm+'</left><p><left>Fecha Ent: '+fechafi+'</left> <p><center><a target="_blank" href="http://developermovil01.serveftp.net:8081/acea/facturacion/vistas/vista.fotos_factura.php?cod_sistema='+cod_inm+'&periodo=<?php echo $periodo; ?>">Ver fotos</a></center><p>Orden: '+sec+'</div>';

	 var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });
	 google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });

	 
	
}

function ended(xgeo,ygeo,cod_inm,fechafi,sec) { 
//y=ygeo;
//x=xgeo;
var secfin = sec+1;
var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
var icon_green = '../../images/flag-green.png';	
	
	var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
	
	var marker = new google.maps.Marker({       
		position: myLatlng,        
		map: map, 
		title: 'inmueble', 
		icon: icon_green      
	});  
	var contentString = '<div id="content"><left>Codigo Inmueble: '+cod_inm+'</left><p><left>Fecha Ent: '+fechafi+'</left> <p><center><a  href="http://developermovil01.serveftp.net:8081/acea/facturacion/vistas/vista.fotos_factura.php?cod_sistema='+cod_inm+'&periodo=<?php echo $periodo; ?>">Ver fotos</a></center><p>Orden: '+secfin+'</div>';

	 var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });
	 google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });
}


	y
	

// funcion que agrega el globo de la informacion de la direccion geocodificada
function attachSecretMessage(marker) {   
var coordenadas="RUTA: "+document.formulario.cod_sistema.value;
		var infowindow = new google.maps.InfoWindow({ 
			content:coordenadas,			
			size: new google.maps.Size(2,1)       
			});  
				google.maps.event.addListener(marker, 'click',function() {     
				infowindow.open(map,marker);   
			}
	); 
}
function addMarker(location) {   
	marker = new google.maps.Marker({     position: location,     map: map   });  
	markersArray.push(marker); 
} 
// no permiete ver los puntos dibujados en el mapa
function clearOverlays() {   
if (markersArray) {     
		for (i in markersArray) {       
		markersArray[i].setMap(null);     
		}   
	} 
}  
// permite ver los puntos dibujados en le mapa
function showOverlays() {   
	if (markersArray) {     
		for (i in markersArray) {       
			markersArray[i].setMap(map);     
		}   
	} 
}  
// borra los puntos dibujados en el mapa
function deleteOverlays() {   
	if (markersArray) {     
		for (i in markersArray) {       
		markersArray[i].setMap(null);     
		}     
		markersArray.length = 0;   
	} 
}
     
	       
	function failureCB(errorCode) {       
	}   
     
	  
   //]]>
   </script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ACEA - GOOGLE</title>
</head>
<body>
<form action="vista.detalle_ruta_factura_google.php"  name="formulario" method="post" >
	<table align="left" style="margin-left:-8px; margin-top:-4px; border:0px solid #ccc;">
		<tr>
        	<td width="330"><div id="map_canvas" style="width: 553px; height: 385px; padding:0px"></div></td>
    	</tr>   
	</table>
	<?php
		if($periodo != "") $where = " AND PERIODO = $periodo ";
		if($fecini != "" && $fecfin != "") 
			$where = " AND FECHA_EJECUCION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
	
  //traemos el primer punto leido con coordenada
 $sql="SELECT * FROM (
 SELECT LONGITUD, LATITUD, COD_INMUEBLE, TO_CHAR(FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
 FROM SGC_TT_REGISTRO_ENTREGA_FAC
 WHERE RUTA = $cod_ruta  AND LONGITUD IS NOT NULL 
 AND LONGITUD <> '0' AND USR_EJE = '$cod_operario'
 $where 
 ORDER BY FECHA_EJECUCION ASC) 
 WHERE ROWNUM = 1";
 //echo $sql;
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 while (oci_fetch($stid)) {
 	$xi = oci_result($stid, 'LONGITUD');
   	$yi = oci_result($stid, 'LATITUD');	
   	$id_sistema_ini = oci_result($stid, 'COD_INMUEBLE');
   	$fechafin = oci_result($stid, 'FECHAFIN');
 }oci_free_statement($stid);
 if (($xi!=NULL && $xi!='(sin_datos)') || ($yi!=NULL && $yi!='(sin_datos)')){
    ?>
		<script>;
		newmap("<?php echo $xi ?>","<?php echo $yi ?>","<?php echo $id_sistema_ini ?>","<?php echo $fechafin ?>");
		</script>
   <?php
 }
 //traemos ultimo punto leido con coordenadas
 $sql="SELECT * FROM (
 SELECT LONGITUD, LATITUD, COD_INMUEBLE ,TO_CHAR(FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
 FROM SGC_TT_REGISTRO_ENTREGA_FAC
 WHERE RUTA = $cod_ruta AND LONGITUD IS NOT NULL 
 AND LONGITUD <> '0' AND USR_EJE = '$cod_operario'
 $where 
 ORDER BY FECHA_EJECUCION DESC) 
 WHERE ROWNUM = 1";
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 while (oci_fetch($stid)) {
 	$xi = oci_result($stid, 'LONGITUD');
 	$yi = oci_result($stid, 'LATITUD');	
 	$id_sistema_fin = oci_result($stid, 'COD_INMUEBLE');
 	$fechafin = oci_result($stid, 'FECHAFIN');
 }oci_free_statement($stid);
 if (($xi!=NULL && $xi!='(sin_datos)') || ($yi!=NULL && $yi!='(sin_datos)')){
    ?>
		<script>;
		ended("<?php echo $xi ?>","<?php echo $yi ?>","<?php echo $id_sistema_fin ?>","<?php echo $fechafin ?>");
		</script>
   <?php
 }
 //traemos todos los puntos de la ruta excepto el primero y el ultimo
 $sql="SELECT LONGITUD, LATITUD, COD_INMUEBLE ,TO_CHAR(FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHAFIN, (rownum+1) SEC
 FROM SGC_TT_REGISTRO_ENTREGA_FAC
 WHERE RUTA = $cod_ruta AND COD_INMUEBLE <> $id_sistema_ini AND COD_INMUEBLE <> $id_sistema_fin 
 AND LONGITUD IS NOT NULL 
 AND LONGITUD <> '0' AND USR_EJE = '$cod_operario'
 $where
 ORDER BY FECHA_EJECUCION ASC";
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 $i=1;
 while (oci_fetch($stid)) {
 	$i=$i+1;
	$x = oci_result($stid, 'LONGITUD');
   	$y = oci_result($stid, 'LATITUD');
   	$cod_sistema = oci_result($stid, 'COD_INMUEBLE');
   	$fechafin = oci_result($stid, 'FECHAFIN');
   	$secq=$i;
   	 if (($x!=NULL && $x!='(sin_datos)') || ($y!=NULL && $y!='(sin_datos)')){
 ?>
	<script>;
	initialize("<?php echo $x ?>","<?php echo $y ?>","<?php echo $cod_sistema ?>","<?php echo $fechafin ?>","<?php echo $secq ?>");
	</script>
  <?php
  	  }
  	  else{}
	}oci_free_statement($stid);

//traemos todos los puntos de la ruta 
$array_coordenadas = "";
 $sql="SELECT LONGITUD, LATITUD
FROM SGC_TT_REGISTRO_ENTREGA_FAC
WHERE RUTA = $cod_ruta AND LONGITUD IS NOT NULL 
AND LONGITUD <> '0' AND USR_EJE = '$cod_operario'
$where
ORDER BY FECHA_EJECUCION ASC";
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 while (oci_fetch($stid)) {
  $x = oci_result($stid, 'LONGITUD');
    $y = oci_result($stid, 'LATITUD');
    $array_y =  "$y";
	$array_x =  "$x";
	$array_total .= $array_y.",".$array_x."/";
 }oci_free_statement($stid);  
 //$array_coordenadas = substr($array_coordenadas, 0, strlen($array_coordenadas)-1);
//echo $array_total;

  ?>
 <script>
 ruteo("<?php echo $array_total?>");
 </script>
</form>
</body>
</html>