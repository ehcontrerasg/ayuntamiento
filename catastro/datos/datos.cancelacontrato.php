<? 
error_reporting(0);
session_start();
include_once '../clases/class.contrato.php';
$items = rtrim($_POST['items'],",");
$d=new Contrato();
$d->setid_contrato($items);
$d->setusuario_mod($_SESSION['codigo']);
$sql=$d->Cancelar_Contrato();

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
$json .= "query: '".$d->getMsError()."',\n";
$json .= "codigo: '".$d->getCodRes()."',\n";
$json .= "}\n";
echo $json;
 ?>