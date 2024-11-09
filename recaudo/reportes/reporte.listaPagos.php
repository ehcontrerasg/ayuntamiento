<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 3/7/2018
 * Time: 13:58
 */
    include_once("../../clases/class.listaPagos.php");

 $fechaDesde = $_POST["fechaDesde"];
 $fechaHasta = $_POST["fechaHasta"];

 $listaPagos = new ListaPagos();

 $listaPagos-> getListaPagos($fechaDesde,$fechaHasta);
