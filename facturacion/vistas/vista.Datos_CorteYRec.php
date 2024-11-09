<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title></title>
    <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <link rel="stylesheet" type="text/css" href="../css/datpago.css">
    <script type="text/javascript" src="../js/datCorteYRec.js?3  "></script>
</head>

<body id="bodydatpagos">
<input type="hidden" id="inm" value="<?php echo $codinmueble;?>">

<div class="bloque">
    <table id="flexCor" style="display:none">
    </table>
</div>

<div class="bloque">
    <table id="flexRec" style="display:none">
    </table>
</div>


<script type="text/javascript">
    inicioCorteyRec();
    flexyCor();
    flexyRec();

</script>
</body>
</html>
