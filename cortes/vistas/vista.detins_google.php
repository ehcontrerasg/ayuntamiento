<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include_once ('../../include.php');
    $coduser = $_SESSION['codigo'];
    $y=$_GET['latitud'];
    $x=$_GET['longitud'];

//echo "Codigo: ".$cod_sistema;
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
            var aguazul = new google.maps.LatLng(18.50012, -69.98857);
            var markersArray = [];
            var ge;
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
                // Chicago
                //crea un nuevo punto en esta caso en aguazul y lo llama aguazul
                /*	 var image = 'aguazul2.PNG';
                 var marker = new google.maps.Marker({
                 position: aguazul,
                 // title:"Aguazul"
                 icon: image
                 }
                 );
                 //diguja este punto en el mapa

                 marker.setMap(map);  */
                google.maps.event.addDomListener(controlUI, 'click', function() {      	 google.earth.createInstance('map_canvas', initCB, failureCB);
// Create the placemark.
                });


            }

            function initialize(xgeo,ygeo) {
                y=ygeo;
                x=xgeo;
                var myLatlng = new google.maps.LatLng(ygeo,xgeo);
                var icon_home = '../../images/home.png';
                var myOptions = {
                    zoom: 18,
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
                // adiciona un punto en el cual se obserca al acercarse aparece la un letrero con "Hello World!"
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    icon: icon_home,
                    title:document.formulario.cod_sistema.value
                });
                // al darle click el punto anteriormente adicionado realizar un zoom de 6 sobre este
                attachSecretMessage(marker);
                // Para adicioar el boton Aguazul en la parte superior
                var homeControlDiv = document.createElement('DIV');
                var homeControl = new HomeControl(homeControlDiv, map);
                homeControlDiv.index = 1;
                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
                //para que adiciones puntos donde pique el uaurario
                //google.maps.event.addListener(map, 'click', function(event)	{addMarker(event.latLng);});


            }
            // funcion que agrega el globo de la informaicion de la direccion geocodificada
            function attachSecretMessage(marker) {
                var coordenadas="CODIGO: "+document.formulario.cod_sistema.value+"<br> CLIENTE: "+document.formulario.id_cliente.value+"<br> DIRECCI&Oacute;N: "+document.formulario.direccion.value;
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
        <style type="text/css">
            <!--
            body {
                /*background-image: url(./images/agua.jpg);
                background-repeat: no-repeat;
                background-position:top;*/
            }
            -->
        </style><head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>ACEA - GOOGLE</title>
        </head>

    <body>
    <form action="acea_google.php"  name="formulario" method="post" >
        <div align="center">
            <p>
                <input type="hidden" name="proceso" >
                <input type="hidden" name="y"  value="<? echo $y; ?>" >
                <input type="hidden" name="x"  value="<? echo $x; ?>" >
                <input type="hidden" name="cod_sistema"  value="<? echo $cod_sistema; ?>">
                <input type="hidden" name="id_cliente"  value="<? echo $id_cliente; ?>" >
                <input type="hidden" name="direccion"  value="<? echo $direccion; ?>" >
            </p>
        </div>
        <?
        if (($x!=NULL && $x!='(sin_datos)') || ($y!=NULL && $y!='(sin_datos)')){
            ?>
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
            <script>;
                initialize("<?php echo $x ?>","<?php echo $y ?>");
            </script>
        <?php
        }
        else{?>
            <script>alert("No fue posible localizar el inmueble, no registra coordenadas"); </script>
        <?php }?>
    </form>
    <script>
        function buscar(){
//alert("entro ");
            document.formulario.proceso.value=1;
            document.formulario.submit();
            document.forms[0].submit();
        }

        function validar(e){

            tecla=(document.all) ? e.keyCode : e.which;
            if(tecla==13){
//alert("entro ");
                buscar();
            };
        }

    </script>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

