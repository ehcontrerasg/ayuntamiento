<?php
session_start();
include_once ('../../include.php');
$coduser = $_SESSION['codigo'];
$cod_inms = $_GET['inmueble'];
$periodo = $_GET['periodo'];
$fecini = $_GET['fecini'];
$fecfin = $_GET['fecfin'];
$cod_operario=$_GET['cod_operario'];

$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
echo '<br>';
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
	 controlUI.title = 'Click para ir a guazul';   
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
 function newmap(xigeo,yigeo){
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
		icon: icon_red      
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

function initialize(xgeo,ygeo,cod_inm) { 
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

	 var contentString = '<div id="content">'+cod_inm+'</div>';

	 var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });
	 google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });

	 
	
}

function ended(xgeo,ygeo) { 
//y=ygeo;
//x=xgeo;
var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
var icon_green = '../../images/flag-green.png';	
	
	var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
	
	var marker = new google.maps.Marker({       
		position: myLatlng,        
		map: map, 
		icon: icon_green      
	});  
}

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
<form action="acea_google.php"  name="formulario" method="post" >
  <table width="622" border="1" align="center" bordercolor="#CCCCCC" bgcolor="#EAECEC">
          <tr>
            <td width="612" height="358"><!--  -->
              <table align="center"cellspacing="1" cellpadding="0"><tr class="LabelColor"><td height="30" class="textico"><table width="71%" height="350" border="1" align="center" cellpadding="0" cellspacing="1"><tr>
                <td height="300"><table width="66%" height="345" border="1" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="330"><div id="map_canvas" style="width: 1000px; height: 500px"></div></td>
                </tr>                
              </table></td>
                </tr>
              </table></td>
                    </tr>
            </table>
            <!--  --></td>
          </tr>
  </table>
  <?php
  //traemos el primer punto leido con coordenada
  
if ($periodo != '') $where = "AND RL.PERIODO = $periodo";
else $where = "AND RL.FECHA_LECTURA BETWEEN TO_DATE('$fecini','yyyymmdd hh24:mi:ss')  AND TO_DATE('$fecfin','yyyymmdd hh24:mi:ss')"; 

 $sql="SELECT * FROM (
 SELECT LONGITUD, LATITUD,COD_INMUEBLE 
 FROM SGC_TT_REGISTRO_LECTURAS RL
 WHERE RL.COD_INMUEBLE IN ($cod_inms)  
 AND RL.COD_LECTOR='$cod_operario'
 AND LONGITUD <> 0
 $where
 ORDER BY FECHA_LECTURA ASC) 
 WHERE ROWNUM = 1";
 //ECHO $sql;
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 while (oci_fetch($stid)) {
 	$xi = oci_result($stid, 'LONGITUD');
   	$yi = oci_result($stid, 'LATITUD');	
   	$id_sistema_ini = oci_result($stid, 'COD_INMUEBLE');
 }oci_free_statement($stid);
 if (($xi!=NULL && $xi!='(sin_datos)') || ($yi!=NULL && $yi!='(sin_datos)')){
    ?>
		<script>;
		newmap("<?php echo $xi ?>","<?php echo $yi ?>");
		</script>
   <?php
 }
 //traemos ultimo punto leido con coordenadas
 $sql="SELECT * FROM (
 SELECT LONGITUD, LATITUD,COD_INMUEBLE 
 FROM SGC_TT_REGISTRO_LECTURAS RL
 WHERE RL.COD_INMUEBLE IN ($cod_inms) 
 AND RL.COD_LECTOR='$cod_operario'
 AND LONGITUD <> 0
 $where
 ORDER BY FECHA_LECTURA desc) 
 WHERE ROWNUM = 1";
 //ECHO $sql;
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 while (oci_fetch($stid)) {
 	$xi = oci_result($stid, 'LONGITUD');
 	$yi = oci_result($stid, 'LATITUD');	
 	$id_sistema_fin = oci_result($stid, 'COD_INMUEBLE');
 }oci_free_statement($stid);
 if (($xi!=NULL && $xi!='(sin_datos)') || ($yi!=NULL && $yi!='(sin_datos)')){
    ?>
		<script>;
		ended("<?php echo $xi ?>","<?php echo $yi ?>");
		</script>
   <?php
 }
 //traemos todos los puntos de la ruta excepto el primero y el ultimo
 $sql="SELECT LONGITUD, LATITUD,COD_INMUEBLE 
 FROM SGC_TT_REGISTRO_LECTURAS RL
 WHERE RL.COD_INMUEBLE IN ($cod_inms)
 AND RL.COD_LECTOR='$cod_operario'
 AND LONGITUD <> 0
 $where
 ORDER BY FECHA_LECTURA ASC";
 //ECHO $sql;
 $stid = oci_parse($link, $sql);
 oci_execute($stid, OCI_DEFAULT);
 while (oci_fetch($stid)) {
	$x = oci_result($stid, 'LONGITUD');
   	$y = oci_result($stid, 'LATITUD');
   	$cod_sistema = oci_result($stid, 'COD_INMUEBLE');
   	 if (($x!=NULL && $x!='(sin_datos)') || ($y!=NULL && $y!='(sin_datos)')){
 ?>
	<script>;
	initialize("<?php echo $x ?>","<?php echo $y ?>","<?php echo $cod_sistema ?>");
	</script>
  <?php
  	  }
  	  else{}
	}oci_free_statement($stid);

//traemos todos los puntos de la ruta 
$array_coordenadas = "";
 $sql="SELECT LONGITUD, LATITUD,COD_INMUEBLE 
 FROM SGC_TT_registro_LECTURAS RL
 WHERE RL.COD_INMUEBLE IN ($cod_inms) 
 AND RL.COD_LECTOR='$cod_operario'
 AND LONGITUD <> 0
 $where
 ORDER BY FECHA_LECTURA ASC";
// ECHO $sql;
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