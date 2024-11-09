<?php
session_start();
include_once ('../../include.php');
include_once ('../clases/class.usuarios.php');
$coduser = $_SESSION['codigo'];
$cod_operario = $_GET['cod_operario'];

$temporal=explode(" ",$cod_operario);
$cod_operario=$temporal[0];
$proyecto=$temporal[1];
$periodo=$temporal[2];
$cod_ruta=$temporal[3];
$fecini=$temporal[4];
$fecfin=$temporal[5];

$tempini=explode("-",$fecini);
$anno=$tempini[0];
$mese=$tempini[1];
$giorno=$tempini[2];
$fecini=$anno.$mese.$giorno;

$tempfin=explode("-",$fecfin);
$anno=$tempfin[0];
$mese=$tempfin[1];
$giorno=$tempfin[2];
$fecfin=$anno.$mese.$giorno;
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
<body style="margin-top:-11px; margin-left:-20px">
<?php
if($cod_operario != ''){
//echo $cod_operario;
?>
<div id="content">
<table id="flex1" style="display:none">
</table>

<?php
$i=new Usuario();
$nombre=$i->obtenernomoperario($operario);

/*$fechainicio=substr_replace(substr_replace(substr_replace(substr_replace(substr_replace($fechaini, '/', 2, 0),'/',5,0),' ',10,0),':',13,0),':',16,0) ;;
$fechafinal=substr_replace(substr_replace(substr_replace(substr_replace(substr_replace($fechafin, '/', 2, 0),'/',5,0),' ',10,0),':',13,0),':',16,0) ;;
*/
?>
<script type="text/javascript">
<!--
  $('.flexme1').flexigrid();
  $('.flexme2').flexigrid({height:'auto',striped:false});
  $("#flex1").flexigrid (
    {
            url: '../datos/datos_detalle_ruta_facturas.php?cod_operario=<?php echo $cod_operario;?>&periodo=<?php echo $periodo;?>&cod_ruta=<?php echo $cod_ruta;?>&fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'numero', width: 30, sortable: true, align: 'center'},
                {display: 'Cod Sistema', name: 'codigo_inm', width: 60, sortable: true, align: 'center'},
                {display: 'Id Proceso', name: 'id_proceso', width: 70, sortable: true, align: 'center'},
                {display: 'Direcci&oacute;n', name: 'direccion', width: 150, sortable: true, align: 'center'},
				{display: 'Fecha Entrega', name: 'fecha_mant', width: 100, sortable: true, align: 'center'},
                {display: 'Observaci&oacute;n', name: 'obs', width: 65, sortable: true, align: 'center'},
                {display: 'Fotos', name: 'foto', width: 40, sortable: true, align: 'center'},
                {display: 'Ubicaci&oacute;n', name: 'ubicacion', width: 50, sortable: true, align: 'center'}
                ],  

            buttons: [
                          {name:'Mapa', bclass:'map', onpress: test} 
                ],
          /*  searchitems : [ 
                {display: 'Cod Sistema', name: 'codigo_inm',isdefault: true},
                {display: 'Id Proceso', name: 'id_proceso'}
                ],
			*/              
            sortname: "L.FECHA_EJECUCION",
            sortorder: "asc",
            usepager: true,
            title: 'Detalle Ruta <?php echo $cod_ruta;?> - Periodo <?php echo $periodo;?> - Lector <?php echo $cod_operario;?> ',
            useRp: true,
            rp: 10000,
            page: 1,            
           // showTableToggleBtn: true,
            width: 559,
            height: 277
            }
            );
  //FUNCION PARA ABRIR UN POPUP
  var popped = null;
  function popup(uri, awid, ahei, scrollbar,npopup) {
      var params;
      if (uri != "") {
          if (popped && !popped.closed) {
              popped.location.href = uri;
              popped.focus();
          } 
          else {
              params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";                               
              popped = window.open(uri,npopup, params);
          }
      }
  }
              
  function fotosperiodo(id2,periodo) { // Traer la fila seleccionada
      popup("vista.fotos_lec.php?cod_sistema="+id2+"&periodo="+periodo,1100,800,'yes','pop1');
  }    
  
  function fotosfecha(id2,fecini,fecfin) { // Traer la fila seleccionada
      popup("vista.fotos_lec.php?cod_sistema="+id2+"&fecini="+fecini+"&fecfin="+fecfin,1100,800,'yes','pop1');
  }         
        
  function ubicacion(id2,id3) { // Traer la fila seleccionada
      popup("vista.detlec_google.php?latitud="+id2+"&longitud="+id3,1100,800,'yes','pop2');
  }

  function hislec(inm,per) { // Traer la fila seleccionada
      popup("vista.hist_lec.php?inmueble="+inm,500,310,'yes','pop3');
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
//-->    
  function agrega(codigos_inm) { // Traer la fila seleccionada
	  window.open("vista.ruta_especificafac.php?inmueble="+codigos_inm+"&fecini=<?php echo $fecini ?>"+"&fecfin=<?php echo $fecfin?>"+"&cod_operario=<?php echo $cod_operario?>"+"&periodo=<?php echo $periodo?>" , "ventana1" , "width=1100,height=600,scrollbars=yes") 
  }
   
</script>

<?php
}
?>
</div>
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