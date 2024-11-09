<? 
error_reporting(0);
include_once '../clases/class.concepto_inmueble.php';
include_once '../clases/class.observaciones.php';

session_start();
$_SESSION['tiempo']=time();
$coduser = $_SESSION['codigo'];
$inmueble=$_GET['inmueble'];
$items = rtrim($_POST['items'],",");
$d=new Concepto_inmueble();
$d->setcodconcepto($items);
$sql=$d->Eliminar_Concepto($inmueble);
$total = count(explode(",",$items));


$p=new Observaciones();
$p->NuevaObs($coduser,'Eliminacion De servicio','Se elimino el servicio '.$items,'GRL',$inmueble);

/*$result = runSQL($sql);
$total = mysql_affected_rows(); */
/// Line 18/19 commented for demo purposes. The MySQL query is not executed in this case. When line 18 and 19 are uncommented, the MySQL query will be executed. 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");
$json = "";
$json .= "{\n";
$json .= "query: '".$sql."',\n";
$json .= "total: $total,\n";
$json .= "}\n";
echo $json;
 ?>
