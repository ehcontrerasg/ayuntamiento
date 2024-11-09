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
    <script type="text/javascript" src="../../alertas/dialog_box.js?1"></script>
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?1"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js?1"></script>
    <link href="../../alertas/dialog_box.css?1" rel="stylesheet" type="text/css" />
    <link href="../../font-awesome/css/font-awesome.min.css?1" rel="stylesheet" />
    <link rel="stylesheet" href="../../flexigrid/style.css?1" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?1">
    <link rel="stylesheet" type="text/css" href="../css/datpago.css?1">
    <script type="text/javascript" src="../js/datOtrosRec.js?1"></script>
</head>

<body>
<input type="hidden" id="inmOtrosRec" value="<?php echo $codinmueble;?>">


<div class="bloque3">
    <table id="flexOtroRec" style="display:none">
    </table>
</div>

<div id="divdescPag" ><label>forma de pago</label>
    <label id="DatOtrReclbfrmpago" class="desc"></label>
    <label>entidad</label> <label id="DatOtrReclbentidad" class="desc"></label>
    <label>punto</label> <label id="DatOtrReclbpunto" class="desc"></label>
    <label>caja</label> <label id="DatOtrReclbcaja" class="desc"></label>
</div>


<script type="text/javascript">
    inicioOtrosRec();
    flexyOtrosRec();
</script>
</body>
</html>
