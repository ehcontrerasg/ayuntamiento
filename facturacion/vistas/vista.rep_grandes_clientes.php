<?php 
	require_once '../clases/class.fn_oracle.php';
  	
  	$fn_oracle = new fn_oracle();
	$orcl_data = $fn_oracle->execQuery($fn_oracle->getQuery(4));
	oci_fetch_all($orcl_data, $data);
	//var_dump($data);
	$row = count($data['CODIGO_INM']);

	//$orcl_data = $fn_oracle->execQuery($fn_oracle->getQuery(5));
	//var_dump($data);
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reporte de Tarifas</title>
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
</head>
<body>
	<header>
		<center><h2></h2></center>
	</header>
	<div class="container">
		<div class="row">
		<table class="table table-striped table-hover table-condensed table-bordered" style="background-color: #0b1a6d;color: #ffffff;font-weight: bold;">
			<thead>
				<tr>
					<th>Cod_Sistema</th>	
					<th>id_zona</th>
					<th>Proyecto</th>
					<th>id_factura</th>
					<th>concepto</th>
					<th>valor_metro</th>
					<th>valor</th>
				</tr>
			</thead>			
		<tbody>
			<?php 
				for ($i=0; $i < 3; $i++) { 
					$fn_oracle->factura = $data['CONSEC_FACTURA'][$i];
					$datos_factura = $fn_oracle->execQuery($fn_oracle->getQuery(5));
					oci_fetch_all($datos_factura, $data2);
					$row2 = count($data2['CONCEPTO']);
					for ($x=0; $x < $row2; $x++) { 
						echo "<tr>
								<td>".$data['CODIGO_INM'][$i]."<td>
								<td>".$data['ID_ZONA'][$i]."<td>
								<td>".$data['CONCEPTO'][$i]."<td>
								<td>".$data2['UNIDADES'][$x]."<td>
								<td>".$data2['VALOR_METRO'][$x]."<td>
								<td>".$data2['VALOR'][$x]."<td>
								<td>".($data2['VALOR_METRO'][$x]*$data2['UNIDADES'][$x])."<td>
							</tr>";
					}
				}			
			 ?>
		</tbody>
		</table>
		</div>
	</div>
	<script src="../../js/jquery-3.2.1.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
</body>
</html>

<?php

  	
	
	
		
	/*echo "<br>";
		echo "<br>";
		/*var_dump($dataR['DESC_USO']);
		echo "<br>";
		echo "<br>";
		var_dump($dataR['VALOR_TARIFA']);
		echo "<br>";
		echo "<br>";
		var_dump($dataR['DESC_CALIBRE']);*/
	
		// $row2 = count($dataR['COD_CALIBRE']) . '<br>';

		/*

echo "<tr>";
					echo "<td>".$i."</td>";
					echo "<td>".$dataR['DESC_USO'][$i]."</td>";
					echo "<td>".$dataR['VALOR_TARIFA'][$i]."</td>";
					echo "<td>".$dataR['DESC_CALIBRE'][$i]."</td>";
					//echo "<td>".$dataR[''][$i]."</td>";
				echo "</tr>";

		*/
?>