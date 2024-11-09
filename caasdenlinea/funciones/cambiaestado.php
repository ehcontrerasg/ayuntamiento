 <?php
 include './../Clases/Usuarios.php';
 include './../Clases/correo.php';
$contrato=$_GET['contratof'];
$estado=$_GET['tipocambio'];
$correo=$_GET['correo'];
   
 if($estado=="Activo"){
$l=new Usuarios();	 
$l->setcontrato($contrato);
$l->setestado("0");
$l->cambiarestado();
$c=new correo();
$c->enviarcorreo($correo, $contrato, "Inactiva");
 }
 if($estado=="Inactivo"){

 	$l=new Usuarios();
 	$l->setcontrato($contrato);
 	$l->setestado("1");
 	$l->cambiarestado();
 	$c=new correo();
 	$c->enviarcorreo($correo, $contrato, "Activada");
 }
 header('Location: ./../Vistas/Usuarios.php');


 
      
  
  ?>
