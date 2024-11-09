<?php
session_start();
require '../clases/classPagos.php';
require '../frames/excel/reader.php';
include('../../destruye_sesion.php');
//pasamos variables de sesion
$coduser = $_SESSION['codigo'];
$fecha_emi = $_POST['fecha_emi'];
$medio = $_POST['medio'];
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('CP1251');
if (isset($_REQUEST["procesar"])){
	$tamano = $_FILES["archivo"]['size'];
 	$tipo = $_FILES["archivo"]['type'];
	$archivo = $_FILES["archivo"]['name'];
	$ruta = $_FILES['archivo']['tmp_name'];
	//sleep (10);
	// echo "[Tamaño]-->$tamano&nbsp;bytes----[Tipo]-->$tipo----[Archivo]--->$archivo---[Ruta]-->$ruta";
	$c = new Pagos();
	$resultado = $c->seleccionaDatosArchivoGc($archivo);
	while (oci_fetch($resultado)) {
		$existe = oci_result($resultado, 'CANTIDAD') ;	
	}oci_free_statement($resultado);
	if ($existe > 0) { 
		?>
		<script type="text/javascript" language="javascript">
		<!--
		  //top.frames[2].document.getElementById("btncargue").disabled = true;   
		  top.frames[2].document.getElementById("barra").style.display = "none";
		  top.frames[2].document.getElementById("mensaje").innerHTML = "<font color='red'>El archivo <? echo $archivo; ?> ya fue cargado ...</font>";
		//-->
		</script>
		<?php       
	} 
	else {		
		$posicion1 = strrpos($archivo, ".XLS");
	    $posicion2 = strrpos($archivo, ".xls");
		//$posicion3 = strrpos($archivo, ".XLSX");
		//$posicion4 = strrpos($archivo, ".xlsx");
	    if ($posicion1 > 0 || $posicion2 > 0) {
			$path = "../archivos/";    
			?>						
			<table border="0" align="center">
			<tr class="titulo">
			<td><small><b>NOMBRE ARCHIVO: </b><? echo $archivo; ?><br>
				<b>TAMA&Ntilde;O: </b><? echo ceil($tamano/1024); ?>&nbsp;kb<br></small>
			</td>
			</tr>
			</table>
			<?php 			
			ob_start();    	
			$cont = 0;
			$error = 0;
			$data->read($ruta);
			?>
			<div id="progress" style="position:relative;padding:0px;width:650px;height:960px;left:25px;">
			<?php	            
			$fp_log = fopen($path."log_$archivo","w");
			$y = 0; $canterror=0; $cont=0;

            do {
                $fecha=$data->sheets[$y]["cells"][2][2];
				for ($i = 4; $i <= 1000; $i++) {
                 $medioPago = strtoupper($data->sheets[$y]["cells"][$i][1]);
                    $noCheque = $data->sheets[$y]["cells"][$i][2];
                    $inmueble = $data->sheets[$y]["cells"][$i][3];
                    $valor = $data->sheets[$y]["cells"][$i][4];

                    if($medioPago<>''){
                        $d = new Pagos();
                        $resultado = $d->IngresaPagoGc($inmueble,$fecha,$medioPago,$noCheque, $valor,$archivo,$coduser);
                        if($resultado == false){
                            $error=$d->getmsgresult();
                            $coderror=$d->getcodresult();
                            $linea = "Hoja: ".($y)." - Fila: ".($i)." - Columna: ".($j)." ERROR:  ".($error)."]\n$sql"."IngresaPagoGc($inmueble,$fecha,$medioPago,$noCheque,$valor,$archivo,$coduser);";
                            fputs($fp_log,$linea."\n");
                            $canterror ++;
                        }
                        else{
                            $cont++;
                        }
                        echo "<div style='float:left;margin:5px 0px 0px 1px;width:2px;height:12px;background:red;color:red;'> </div>";
                        flush();
                        ob_flush();
                        //$cont++;
                        //}
                    }



				}
				$y++;
            }while($data->sheets[$y]['numRows']);

			//echo $datos_1;

		}//else echo "Error !!"; // if	 			
		?></div>		
		<?php

		if ($canterror == 0) {
        $data->read($ruta);

            $y = 0; $canterror=0; $cont=0;
            $fecha=$data->sheets[$y]["cells"][2][2];
            $proyecto = $data->sheets[$y]["cells"][1][2];


                for ($i = 4; $i <= 1000; $i++){
                    $medioPago  = strtoupper($data->sheets[$y]["cells"][$i][1]);
                    $noCheque = $data->sheets[$y]["cells"][$i][2];
                    $inmueble = $data->sheets[$y]["cells"][$i][3];
                    $valor = $data->sheets[$y]["cells"][$i][4];
                    if(trim($medioPago)=='EFECTIVO' OR $medioPago=='TRANSF' ){
                        $med=1;
                    }elseif(trim($medioPago)=='CHEQUE'){
                        $med=2;
                    }
                    if($medioPago<>''){
                        $q = new Pagos();
                        $resultado = $q->IngresaPago($valor,'Carga masiva pagos grandes clientes',576,$inmueble,'W',$coduser,$valor,$proyecto,0,$fecha,0,$med,0,0,$noCheque,$noCheque,'','','','Pago grandes clientes');
                        if($resultado == false){
                            $error=$q->getmsgresult();
                            $coderror=$q->getcodresult();
                            $linea = "Hoja: ".($y)." - Fila: ".($i)." ".($error)."]\n$sql"."PROCEDURE IngresaPaG($valor,'Carga masiva pagos grandes clientes',576,$inmueble,'W',$coduser,$valor,$proyecto,0,$fecha,0,$med,0,0,'',$noCheque,'','','','Pago grandes clientes');";
                            fputs($fp_log,$linea."\n");
                            $canterror ++;
                        }
                         else
                         {
                             $cont++;
                         }
                    flush();
                    ob_flush();
                    }
                    if(trim($inmueble)==''){
                        $i=1000;
                    }
                }


			?>
			<script type="text/javascript" language="javascript">
			<!--
			  top.frames[2].document.getElementById("mensaje").innerHTML = "<font color='red'>Se han procesado <? echo $cont; ?> registros, del archivo <? echo $archivo; ?> ERRORES <? echo $canterror; ?>  !!</font>";
			  top.frames[2].document.getElementById("barra").style.display = "none";
			  //top.frames[2].document.getElementById("btncargue").disabled = true;   
			//-->
			</script>
			<?php
		} 
		else { 
			$bn = new Pagos();
			$resultado = $bn->BorraPagoGC($archivo);
			?>		  
			<script type="text/javascript" language="javascript">
			<!--
			   top.frames[2].document.getElementById("mensaje").innerHTML = "<font color='red'>Se han hallado <? echo $canterror; ?> errores al cargar el archivo <? echo $archivo; ?> !!</font>"; 
			   top.frames[2].document.getElementById("barra").style.display = "none";      
			   top.frames[2].document.getElementById("errores").innerHTML = "<font color='red'>Resum&eacute;n de errores: <a href='../archivos/log_<? echo $archivo; ?>' style='background-color:#FF0000'>&nbsp;Aqui&nbsp;</a></font>";    
			//-->
			</script> 
			<?php	     
		} // else
    	flush(); 
	}
    fclose($fp_log);


} // if proc
?>
<script type="text/javascript" language="javascript">
    <!--

    top.frames[2].document.getElementById("errores").innerHTML = "<font color='red'>Resum&eacute;n de errores: <a href='../archivos/log_<? echo $archivo; ?>' style='background-color:#FF0000'>&nbsp;Aqui&nbsp;</a></font>";
    //-->
</script>
