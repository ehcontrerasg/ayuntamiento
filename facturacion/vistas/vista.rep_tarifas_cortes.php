<?php 
	require_once '../clases/class.fn_oracle.php';
  	
  	$fn_oracle = new fn_oracle();
	$orcl_data = $fn_oracle->execQuery($fn_oracle->getQuery(3));
	oci_fetch_all($orcl_data, $data);
	//var_dump($data);
	$row = count($data['ID_USO']);

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
		<center><h2>Tarifas de Cortes Actuales (PRODUCCION)</h2></center>
	</header>
	<div class="container">
		<div class="row">	
		<?php 
			//var_dump($dataR);
			for ($i=0; $i < $row; $i++) {
				$fn_oracle = $GLOBALS['fn_oracle'];
				$data = $GLOBALS['data'];

				$fn_oracle->id_uso = $data['ID_USO'][$i];
				$orcl_data2 = $fn_oracle->execQuery($fn_oracle->getQuery(1));
				oci_fetch_all($orcl_data2, $dataR);
				if (!empty($dataR['DESC_USO']) AND !empty($dataR['VALOR_TARIFA']) AND !empty($dataR['DESC_CALIBRE'])) {
					echo '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
							<div class="table-responsive">
								<table id="tblArticulo" class="display table table-striped table-hover table-condensed table-bordered" cellspacing="0">
								<thead style="background-color: #0b1a6d;color: #ffffff;font-weight: bold;">
									<tr>
										<th>COD</th>
										<th>USO</th>
										<th>TARIFA</th>
										<th>CALIBRE</th>
										<th>MEDIDOR</th>
									</tr>
								</thead>
								<tbody>';
				 			
								$row2 = count($dataR['DESC_USO']);
								for ($x=0; $x < $row2; $x++) { 
									echo "<tr>";
										echo '<td>'.$x.'</td>';
										echo "<td>".$dataR['DESC_USO'][$x]."</td>";
										echo "<td>".$dataR['VALOR_TARIFA'][$x]."</td>";
										echo "<td>".$dataR['DESC_CALIBRE'][$x]."</td>";
										echo "<td>".$dataR['MEDIDOR'][$x]."</td>";
										//echo "<td>".$dataR[''][$i]."</td>";
									echo "</tr>";
								}
									
					echo "			</tbody>
							 	</table>
							</div>
						</div>";			
		
				}
			}
		 ?>
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