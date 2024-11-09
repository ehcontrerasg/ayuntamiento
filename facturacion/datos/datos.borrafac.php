<?php
session_start();
require'../clases/classAperturaZona.php';
include('../../destruye_sesion.php');

$zona=$_POST['zona'];
$periodo=$_POST['periodo'];
$acueducto=$_POST['proyecto'];

//$zona='14A';
//$periodo=201509;
//$acueducto='SD';



$i= new AperturaZona();
$bandera=$i->BorraPeriodo($zona,$periodo,$acueducto);
echo $bandera;
