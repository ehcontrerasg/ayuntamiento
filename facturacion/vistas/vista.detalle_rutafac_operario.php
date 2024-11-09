<?php
session_start();
include_once ('../../include.php');
include_once ('../clases/class.usuarios.php');
$coduser = $_SESSION['codigo'];
$cod_ruta = ($_GET['cod_ruta']);
$fechaini = $_GET['fecini'];
$fechafin = $_GET['fecfin'];
      


$operario =($_GET['usuario']);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../../flexigrid/style.css" />
<link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
<script language="javascript">
    $(document).ready(function() {
        $(".botonExcel").click(function(event) {
            $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
            $("#FormularioExportacion").submit();
    });
    });
</script>
</head>
<body>
<form name="rendimiento" action="vista.detalle_rutafac_operario.php" method="post" onsubmit="return rend();">
<h3 style="font-family:Tahoma, Geneva, sans-serif; color:rgb(163,73,163); font-size:11px; font-weight:100">
    <b>FACTURACION</b> >> Detalle Rendimiento Operario Por Ruta 
</h3>
</form>
<?php
//if($proc == 1){
?>
<form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
                <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="Excel"/>
                <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div>      
        </div>
    </div>      
</form>
<div>
<table id="Exportar_a_Excel" style="display:none">
    <tr>
        <th align="center">No</th>
        <th align="center">COD SISTEMA</th>
        <th align="center">ID PROCESO</th>
        <th align="center">DIRECCI&Oacute;N</th>
        <th align="center">FECHA ENTREGA</th>
        <th align="center">FOTO</th>
        <th align="center">UBICACI&Oacute;N</th>
    </tr>
</table>
<table id="flex1" style="display:none">
</table>
</div>
<?php
$i=new Usuario();
$nombre=$i->obtenernomoperario($operario);

$fechainicio=substr_replace(substr_replace(substr_replace(substr_replace(substr_replace($fechaini, '/', 2, 0),'/',5,0),' ',10,0),':',13,0),':',16,0) ;;
$fechafinal=substr_replace(substr_replace(substr_replace(substr_replace(substr_replace($fechafin, '/', 2, 0),'/',5,0),' ',10,0),':',13,0),':',16,0) ;;

?>
<script type="text/javascript">
<!--
  $('.flexme1').flexigrid();
  $('.flexme2').flexigrid({height:'auto',striped:false});
  $("#flex1").flexigrid (
    {
            url: '../datos/datos_detalle_rutafac.php?fecini=<?php echo $fechaini;?>&fecfin=<?php echo $fechafin;?>&operario=<?php echo $operario;?>&ruta=<?php echo $cod_ruta;?> ',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'numero', width: 30, sortable: true, align: 'center'},
                {display: 'Cod Sistema', name: 'codigo_inm', width: 80, sortable: true, align: 'center'},
                {display: 'Id Proceso', name: 'id_proceso', width: 200, sortable: true, align: 'center'},
                {display: 'Direcci&oacute;n', name: 'direccion', width: 250, sortable: true, align: 'center'},
                {display: 'Fecha Mantenimiento', name: 'fecha_mant', width: 150, sortable: true, align: 'center'},
                {display: 'Fotos', name: 'foto', width: 80, sortable: true, align: 'center'},
                {display: 'Ubicaci&oacute;n', name: 'ubicacion', width: 80, sortable: true, align: 'center'}
                ],  

                buttons: [
                          {name:'Mapa', bclass:'map', onpress: test} 
                          ],
            searchitems : [ 
                {display: 'Cod Sistema', name: 'codigo_inm',isdefault: true},
                {display: 'Id Proceso', name: 'id_proceso'}
                ],


               
                          
            sortname: "EF.FECHA_EJECUCION",
            sortorder: "asc",
            usepager: true,
            title: 'Detalle Ruta <?php echo $cod_ruta;?> - Operario <?php echo $nombre;?> - Fecha <?php echo $fechainicio." - ".$fechafinal;?>',
            useRp: true,
            rp: 10000,
            page: 1,            
            showTableToggleBtn: true,
            width: 960,
            height: 358
            }
            );
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
                        
            function fotos(id2,id3) { // Traer la fila seleccionada
                popup("fotos_mantenimiento.php?cod_sistema="+id2+"&periodo="+id3,1100,800,'yes');
            }           
//-->       
            function ubicacion(id2,id3) { // Traer la fila seleccionada
                popup("vista.detfac_google.php?latitud="+id2+"&longitud="+id3,1100,800,'yes');
            } 

            function test(com,grid)
            {
	          	if (com=='Mapa')
	           	{
	            	
	         		if($('.trSelected',grid).length>0)
	             	{
	        			var items = $('.trSelected',grid);
	              	    var itemlist ='';
	              	 	for(i=0;i<items.length;i++)
	                  	{	
		                  	if(i<items.length-1 ){
	              			itemlist+= items[i].id.substr(3)+",";
		                  	}else{
		                  		itemlist+= items[i].id.substr(3);
		                  		}
	              		}
	              		
	              	 	agrega(itemlist);
	           		} 
	        	}  
              
            } 

            function agrega(codigos_inm) { // Traer la fila seleccionada
          	  window.open("/acea/facturacion/vistas/vista.ruta_especifica.php?inmueble="+codigos_inm+"&fecini=<?php echo $fechaini ?>"+"&fecfin=<?php echo $fechafin?>"+"&operario=<?php echo $operario?>" , "ventana1" , "width=770,height=6600,scrollbars=yes") 
            }
             
</script>
<?php
//}
?>
</body>
</html>
<script type="text/javascript" language="javascript">  




function rend(){
    if (document.rendimiento.periodo.value == "") {
        document.rendimiento.proc.value=0;
        return false;   
    }
    else { 
        document.rendimiento.proc.value = 1; 
        return true; 
    }
}

function recarga() {
    document.rendimiento.ruta.selectedIndex = 0;
    document.rendimiento.submit();
  }

</script>