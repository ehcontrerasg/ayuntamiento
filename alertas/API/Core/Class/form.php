<html>
	<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript">
function validacion(campo)
if (campo==='contrato'){
                contrato = document.getElementById(campo).value;
                if( contrato == null || contrato.length == 0 || /^\s+$/.test(contrato) ) {

                    $("#glypcn"+campo).remove();
                    $('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
                    $('#'+campo).parent().children('span').text("Ingrese algo").show();
                    $('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
                    return false;

                }
            }

		function enviar(){
			var contrato= $("input#contrato").val();
			$.ajax({
				url: 'http://172.16.1.211:8081/acea/API/via/getData/[contrato]',
				type: 'POST',
				dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
				data: {contrato: 'contrato'},
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		}
	</script>
		</head>
		<body>
			<form name="token" id="token" role="form" class="form-horizontal">
                    <div class="control-group form-group col-lg-12">
						<div class="control-group form-group col-lg-4"></div>
                        <div class="col-lg-4">
                            <input id="contrato" name="contrato" type="text" class="form-control" placeholder="Escriba su Numero de contrato" onkeyup="validacion('contrato');">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="button" class="btn btn-primary" onclick='enviar();'>Enviar</button>
                </form>
		</body>
</html>
