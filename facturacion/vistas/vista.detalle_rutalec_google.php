<?php
session_start();
include_once ('../../include.php');
include_once ('../clases/class.coordenadasfac.php');
$coduser = $_SESSION['codigo'];
$cod_ruta = ($_GET['cod_ruta']);
$fechaini = ($_GET['fecini']);
$fechafin = ($_GET['fecfin']);
$operario =($_GET['usuario']);
echo '<br>';
?>
<html> 
<head> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="http://www.google.com/jsapi?key=ABQIAAAA1YNBrpDz-yi7J5-Jvk4CWxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQr8UbwBTNQXfe6c910iVmjrp4ZUQ"> </script> 

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
 	 var contentString = '<div id="content"><center>'+cod_inm+'</center><p><center>fecha:'+fechafi+'</center></div>';

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

	var contentString = '<div id="content"><center>'+cod_inm+'</center><p><center>fecha:'+fechafi+'</center> <p>Orden:'+sec+'</div>';

	 var infowindow = new google.maps.InfoWindow({
	      content: contentString
	  });
	 google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });

	 
	
}

function ended(xgeo,ygeo,cod_inm,fechafi) { 
//y=ygeo;
//x=xgeo;
var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
var icon_green = '../../images/flag-green.png';	
	
	var myLatlng = new google.maps.LatLng(ygeo,xgeo);  
	
	var marker = new google.maps.Marker({       
		position: myLatlng,        
		map: map, 
		title: 'inmueble', 
		icon: icon_green      
	});  
	var contentString = '<div id="content"><center>'+cod_inm+'</center><p><center>fecha:'+fechafi+'</center> <p></div>';

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
  $l=new coordenadasfac();
  $registros=$l->obtenerpripuntolec($cod_ruta, $fechaini, $fechafin, $operario);
  while (oci_fetch($registros)) {
 	$xi = oci_result($registros, 'LONGITUD');
   	$yi = oci_result($registros, 'LATITUD');	
   	$id_sistema_ini = oci_result($registros, 'COD_INMUEBLE');
   	$fechafinal = oci_result($registros, 'FECHAFIN');
 }oci_free_statement($registros);
 if (($xi!=NULL && $xi!='0') || ($yi!=NULL && $yi!='0')){
    ?>
		<script>;
		newmap("<?php echo $xi ?>","<?php echo $yi ?>","<?php echo $id_sistema_ini ?>","<?php echo $fechafinal ?>");
		</script>
   <?php
 }
 //traemos ultimo punto leido con coordenadas
  $l=new coordenadasfac();
  $registros=$l->obtenerultpuntoLEC($cod_ruta, $fechaini, $fechafin, $operario);
  while (oci_fetch($registros)) {
 	$xi = oci_result($registros, 'LONGITUD');
   	$yi = oci_result($registros, 'LATITUD');	
   	$id_sistema_fin = oci_result($registros, 'COD_INMUEBLE');
   	$fechafinal = oci_result($registros, 'FECHAFIN');
 }oci_free_statement($registros);
 if (($xi!=NULL && $xi!='(sin_datos)') || ($yi!=NULL && $yi!='(sin_datos)')){
    ?>
		<script>;
		ended("<?php echo $xi ?>","<?php echo $yi ?>","<?php echo $id_sistema_fin ?>","<?php echo $fechafinal ?>");
		</script>
   <?php
 }
 //traemos todos los puntos de la ruta excepto el primero y el ultimo
 $l=new coordenadasfac();
 $registros=$l->obtenerrestpuntolec($cod_ruta, $fechaini, $fechafin, $operario,$id_sistema_ini,$id_sistema_fin);
 $i=0;
 while (oci_fetch($registros)) {
 	$i=$i+1;
	$x = oci_result($registros, 'LONGITUD');
   	$y = oci_result($registros, 'LATITUD');
   	$cod_sistema = oci_result($registros, 'COD_INMUEBLE');
   	$fechafinal = oci_result($registros, 'FECHAFIN');
   	$secq=oci_result($registros, 'SEC');
   	 if (($x!=NULL && $x!='(sin_datos)') || ($y!=NULL && $y!='(sin_datos)')){
 ?>
	<script>;
	initialize("<?php echo $x ?>","<?php echo $y ?>","<?php echo $cod_sistema ?>","<?php echo $fechafinal ?>","<?php echo $i ?>");
	</script>
  <?php
  	  }
  	  else{}
	}oci_free_statement($registros);

//traemos todos los puntos de la ruta 
$array_coordenadas = "";
 $l=new coordenadasfac();
 $registros=$l->obtenertodtpuntolec($cod_ruta, $fechaini, $fechafin, $operario);
 while (oci_fetch($registros)) {
  	$x = oci_result($registros, 'LONGITUD');
    $y = oci_result($registros, 'LATITUD');
    $array_y =  "$y";
	$array_x =  "$x";
	$array_total .= $array_y.",".$array_x."/";
 }oci_free_statement($registros);  
 //$array_coordenadas = substr($array_coordenadas, 0, strlen($array_coordenadas)-1);
//echo $array_total;

  ?>
 <script>
 ruteo("<?php echo $array_total?>");
 </script>
</form>
</body>
</html>