<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/14/2016
 * Time: 11:05 AM
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title></title>
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script language="JavaScript" type="text/javascript" src="../../js/tabber.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/datInm.js?7 "></script>
	<script language="JavaScript" type="text/javascript" src="../js/datpagos.js "></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <link rel="stylesheet" type="text/css" href="../css/datpago.css ">
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="bodydatpagos">
<input type="hidden" id="inmPago" value="<?php echo $codinmueble;?>">
<div class="bloque">
    <table id="flexpag" style="display:none">
    </table>
</div>

<div class="bloque2">
    <table id="flexfacap" style="display:none">
    </table>
</div>

<div class="bloque2">
    <table id="flexdifap" style="display:none">
    </table>
</div>

<div id="divdescPag" ><label>forma de pago</label>
    <label id="lbfrmpagoPag" class="desc"></label>
    <label>entidad</label> <label id="lbentidadPag" class="desc"></label>
    <label>punto</label> <label id="lbpuntoPag" class="desc"></label>
    <label>caja</label> <label id="lbcajaPag" class="desc"></label>
</div>

<script type="text/javascript">
    flexyPagos();
    inicioPagos();
</script>
</body>
</html>
