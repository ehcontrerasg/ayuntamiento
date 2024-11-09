<? 
error_reporting(0);
include_once '../clases/classPagos.php';
$items = rtrim($_POST['items'],",");
$d=new Pagos();
$d->setfecha($items);

$total = count(explode(",",$items));
$sql=$d->EliminaDiaFeriado();
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