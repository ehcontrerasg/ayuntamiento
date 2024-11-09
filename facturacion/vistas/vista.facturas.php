<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <script type="text/javascript" src="../js/datFacturas.js"></script>
   
</head>
<body>
	<div class="flexigrid" >
		<div class="mDiv">
		</div>
        <div style="display: block;float: left">
			<table id="flexfacturas" style="display:none">
			</table>
		</div>
		<div style="display: block;float: left" >
        <input type="radio" id="rbfactipofac" name="rbfactipofac" onChange="recargaifr();" value="M" checked> Factura
        <input type="radio" id="rbfactipofac" name="rbfactipofac"  onchange="recargaifr();" value="P"> Datos
		<input type="hidden" id="inpdatfaccodfac">
    	<input type="hidden" id="inpdatfacinm" value="<?php echo $codinmueble;?>">
		</div>
		<div style="display: block;float: left">
		&nbsp;&nbsp;&nbsp;<a class="linkRep" id="linkImpEstCuenta" href="datos.RepEstCon.php" target="popupEstCuenta" ><font size="2px">Imprimir estado por cuenta</font></a>
        &nbsp;&nbsp;&nbsp;<a class="linkRep" id="linkImpEstConcepto" target="popupEstCon" ><font size="2px">Imprimir estado por concepto</font></a>
		</div>	
       
		<object id="ifpdf" name="ifpdf" data=""></object>
		
		 <div style="display: block;float: left">
            <table id="flexdatdetfactura" style="display:none">
            </table>
        </div>
		 <div style="display: block;float: left" >
            <table id="flexdatdetlectura" style="display:none">
            </table>
        </div>
		
		<div style="display: block;float: left" >
            <table id="flexdatestconcepto" style="display:none">
            </table>
        </div>
		
		<div style="display: block;float: left" >
            <table id="flexdatdiferidos" style="display:none">
            </table>
        </div>
		
		<div style="display: block;float: left" >
            <table id="flexdatpdc" style="display:none">
            </table>
        </div>
		
        <div id="diddatestcuenta" >
            <table id="flexdatfacestcuen" style="display:none">
            </table>
        </div>

        
	</div>
   
<script type="text/javascript">
    inicioFacturas();
    flexyfacturas();
	flexydiferidos();
	flexypdc();
    flexyestcuenta();
</script>
</body>

</html>

