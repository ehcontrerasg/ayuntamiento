$(document).ready(function(){

	$('#frmLogin').submit(function(e){
		e.preventDefault();
		var caso = $(this).attr('caso');
		var parametros = $(this).serialize();
		//console.log(parametros);
		var url =  $(this).attr('action');
		$.ajax({
			url: url,
			data: parametros,
			type: 'POST',
			success: function(resp) {
				//console.log(resp);
					login(resp, caso);
			},
			error: function(jqXHR, estado, error) {
				//console.log(estado+' : '+ error);
			}
		});
	});
	var	login = function(data, caso){
		console.log(data);
		var resp = data.toString();
		switch(caso){
			case '1':
				if (resp=='TRUE') {
					document.location.href = "index2.php/";
				}else{
					$('#msgUser').fadeIn();
				}
				break;
			case '2':
				if (resp=='TRUE') {
					document.location.reload();
				}else{
					$('#msgUser').fadeIn();
				}
				
				break;

		}
		
	}
});
