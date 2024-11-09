<?php
require_once '../clases/class.lecturas.php';

$inmueble = $_GET["inmueble"];

$l=new Lectuas();
echo $l->TodosLecHis($inmueble);