<? 

/********************************************************************/
/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
/*  CREADO POR EDWIN HERNAN CONTRERAS								*/
/*  FECHA CREACION 24/8/2014								*/
/********************************************************************/

error_reporting(0);
include_once '../clases/class.concepto_inmueble.php';
$items = rtrim($_POST['items'],",");
$codigo=$_SESSION['codigo'];
$inmueble=$_GET['inmueble'];
$d=new Concepto_inmueble();
$d->setcodinmueble($inmueble);
$d->setusrcreacion($codigo);
$sql=$d->Cambiarestado($items);
$total = count(explode(",",$items)); 
// /*$result = runSQL($sql);
// $total = mysql_affected_rows(); */
// /// Line 18/19 commented for demo purposes. The MySQL query is not executed in this case. When line 18 and 19 are uncommented, the MySQL query will be executed. 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");
 $json = "";
 $json .= "{\n";
 $json .= "query: '"."basura"."',\n";
 $json .= "total: 12,\n";
 $json .= "}\n";
echo $json;
 ?>
