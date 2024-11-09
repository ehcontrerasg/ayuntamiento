appSolicitudesTI.controller("ctrlSolicitarTI", function($scope, socket) {
	//console.log('hola mundo');
	socket.removeListener('lstModulos');
	socket.removeListener('lstpantallas');
	socket.removeListener('readySolTI');

	$scope.solicitudes = null;
	$scope.fondoStatus = "bg-warning";
	
	socket.emit('llenarLstSolTI', 'hello mom!');
  	socket.on('lstModulos', function (data) {
  		//console.log( angular.fromJson(data));
  		//moduloHTML(angular.fromJson(data).sort());
    	$("#sltMenu").html(moduloHTML(angular.fromJson(data)));
    	$('#sltMenu').change(function(){
			//console.log('hola MUndo');
			var idMenu = $(this).val();
			socket.emit('idMenu', idMenu);
		});
		$('#genSolicitudTI').submit(function(e){
			e.preventDefault();
			console.log('argare');
			var data = $(this).serializeArray();
			//$(this).reset();
			// JSON.stringify(
			//console.log(data);
			socket.emit('genSolicitudTI', data);
		});
    	//console.log('hola');
   	});
   	socket.on('lstpantallas', function(data){
		//console.log(data);
		$("#sltPantalla").html(moduloHTML(angular.fromJson(data)));
	});
	socket.on('readySolTI', function(data){
		//console.log(data);
		if (data==200) {
			$scope.msgSuccess = true;
			$scope.msgDanger = false;
			document.getElementById("genSolicitudTI").reset();
			//document.getElementById('#genSolicitudTI').reset();
		}else{
			$scope.msgErrSOLTI = data;
			$scope.msgSuccess = false;
			$scope.msgDanger = true;
		}
	});
	function moduloHTML (modulos) {
		var row = modulos.length;
		var html = '<option ng-selected=></option>';
		for (var i = 0; i < row; i++) {
			html += '<option value="'+modulos[i]['ID_MENU']+'">'+titleCase(modulos[i]['DESC_MENU'])+'</option>';
		}
		return html;
	}
	/*$('#sltMenu').change(function(){
		console.log($(this).val());
		//socket.emit('lstpantallas', {"hola" : "mundo"});
	});*/
});