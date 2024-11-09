<?php
require_once '../clases/class.observaciones.php';

$o = new Observacion();

$datos = $o->seltiposObs();

$i = 0;
while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $tipoObs[$i] = $row;
    $i++;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Nueva Observacion</title>

	<style>
	.msg-box{
		height: 60px;
	}
	</style>
</head>
<body>

	<div class="container">

	<div class="msg-box">

	</div>

	<form id="form-observaciones">
		<div class="col-sm-12">
			<h2>Ingresar nuevo reporte</h2>
		</div>
		<div class="form-group col-sm-6">
			<label for="asunto">Asunto <span style="color: red;">*</span> </label>
			<input type="text" name="asunto" id="asunto" class="form-control">
		</div>
		<div class="form-group col-sm-3">
			<label for="codigo">Codigo</label>
			<select name="codigo" id="codigo" class="form-control">

				<?php
foreach ($tipoObs as $key) {
    echo '<option value=' . $key['CODIGO'] . '>' . $key['DESCRIPCION'] . '</option>';
}
?>
			</select>
		</div>

		<div class="form-group col-sm-9">
			<label for="descripcion"></label>
			<textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
		</div>

		<div class="form-group col-sm-3">
			<button type="submit" class="btn btn-info center-block">Guardar</button>
		</div>
	</form>

	</div>

	<script>


		$('#form-observaciones').submit(function(e){
			e.preventDefault();

			var asunto = document.getElementById('asunto').value,
				msgBox = $('.msg-box'),
				msg = '';

			if (asunto.length < 1) {
				msg = '<div class="alert alert-danger" role="alert">';
				msg += '<b>Error:</b> El campo de Asunto es obligatorio.';
				msg += '</div>';

				msgBox.html(msg);
				return false;
			} else {
				setObsrv(this);
			}
		})

		function setObsrv(form) {
			var msgBox = $('.msg-box'), msg = '';

			var string = $(form).serialize();
			string += "&codUser='<?php echo $coduser; ?>'" + "&codInm=<?php echo $codinmueble; ?>";

			$.post('../datos/datos.observaciones.php', string, function(data){

				if (data == 'true') {
					msg = '<div class="alert alert-info" role="alert">';
					msg += '<b>Registro exitoso:</b> Su nueva observación fué registrada.';
					msg += '</div>';

				$('#form-observaciones')[0].reset();

				setTimeout(function(){
					msgBox.html('');
				},3000);

				} else {
					msg = '<div class="alert alert-danger" role="alert">';
					msg += '<b>Registro fallido:</b> Su nueva observación no pudo ser registrada.';
					msg += '</div>';
				}

				msgBox.html(msg);
			})
		}

	</script>
</body>
</html>
