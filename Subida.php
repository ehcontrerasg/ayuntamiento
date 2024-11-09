<?php
/*
$db="//localhost/xe";
$user= "admin";
$pass="123";

$db="172.16.1.192/ACEASOFT";
$user= "ACEASOFT";
$pass="acea";

$conn = oci_connect($user, $pass, $db);
 
if (!$conn) {

   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   //print "Usuarios\n\n";
}
// Close the Oracle connection
//oci_close($conn);
*/

include "clases/class.conexion.php";



class Subida extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }


    public function insertDatos()
    {
        $fileData = function () {
            $file = fopen('AppFacturacion.txt', 'r');

            if (!$file) die('file does not exist or cannot be opened');

            while (($line = fgets($file)) !== false) {
                /// yield $line;
                yield $line;


            }
            fclose($file);
        };
        $i = 0;
        foreach ($fileData() as $line) {
            $i++;
            $arregloDeLinea = explode('\n', $line);
            $arregloDeLinea2 = explode('|', $arregloDeLinea[0]);
//echo "$arregloDeLinea2[0] $arregloDeLinea2[1] ";

//echo $sql="insert into usuario values ($arregloDeLinea2[1],'$arregloDeLinea2[0]')";
            if ($i > 1)
                $sql = "insert into PRASA values ('$arregloDeLinea2[0]','$arregloDeLinea2[1]','$arregloDeLinea2[2]','$arregloDeLinea2[3]','$arregloDeLinea2[4]','$arregloDeLinea2[5]','$arregloDeLinea2[6]','$arregloDeLinea2[7]','$arregloDeLinea2[8]','$arregloDeLinea2[9]','$arregloDeLinea2[10]','$arregloDeLinea2[11]','$arregloDeLinea2[12]','$arregloDeLinea2[13]','$arregloDeLinea2[14]','$arregloDeLinea2[15]','$arregloDeLinea2[16]','$arregloDeLinea2[17]','$arregloDeLinea2[18]','$arregloDeLinea2[19]','$arregloDeLinea2[20]','$arregloDeLinea2[21]','$arregloDeLinea2[22]')";
            $sql =rtrim($sql,",");
             $insert=oci_parse($this->_db, $sql);
             oci_execute($insert);
            // $line contains current line
        }
        oci_close($this->_db);
    }

/*
$file_lines = file('mytext.txt');
foreach ($file_lines as $line) {
  $line = fgets($file);
   $arregloDeLinea=explode('\n', $line);
   $arregloDeLinea2=explode('|', $arregloDeLinea[0]);
echo "$arregloDeLinea2[0] $arregloDeLinea2[1] ";

$sql="insert into usuario values ('$arregloDeLinea2[1]','$arregloDeLinea2[0]')";
$insert=oci_parse($conn, $sql);
@oci_execute($insert);

}*/

/*

$sql2="select codigo, nombre from usuario";

$consulta=oci_parse($conn,$sql2);
@oci_execute($consulta);


  // echo "Usuarios\n\n";

while ($fila=oci_fetch_row ($consulta))  {

echo $fila[0]." ".$fila[1];	



}
*/

}
$s=new Subida();
$s->insertDatos();
?>