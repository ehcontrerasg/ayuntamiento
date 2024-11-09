appSolicitudesTI.controller("ctrlSolicitudesTI", function($scope, $cookies, socket, auth, localStorageService) {
	socket.removeListener('solicitudes');
	socket.removeListener('respCalidad');
	socket.removeListener('respSetProgramSOLTI');
	socket.removeListener('addDellSolTI');

	//console.log(localStorageService.get('area'), localStorageService.get('acceso'));
	$scope.setPermisosUsr(localStorageService.get('area'), localStorageService.get('acceso'));
	$scope.solicitudes = null;
	if ($cookies.programmers != undefined) {
		if ($cookies.programmers.length > 3) {
	        $scope.programmerClass = "col-xs-12 col-sm-3 col-md-3 col-lg-3";
	    }else{
	        $scope.programmerClass = "col-xs-12 col-sm-4 col-md-4 col-lg-4";
	    }	
	}
	$scope.programmers = $cookies.programmers;
	$scope.btnRechazar = function(id){
		dfnMsgAlert('danger', id);
		$('#cmt'+id).show();
		//$scope.msgAlert = "¿Esta seguro de rechazar esta solicitud?";
		//$scope.tipoAlerta = "alert-danger";
		$("#btns"+id).fadeOut();
		$("#alrt"+id).fadeIn(function(){
			$("#btn-acept"+id).click(function(){
				$("#btn-acept"+id).off('click');
				var comentario = $('#cmt'+id).val().toString();
				if (comentario != '') {
					respCalidad(id, comentario, 'N');
					$('#ctn'+id).slideUp('slow');
					$("#btn-rech"+id).off('click');
				}else{
					$('#cmt'+id).focus();
					$('#cmt'+id).attr('placeholder', 'Debe escribir la razon por la cual esta rechazando esta solicitud');
				}
				
			});
			$("#btn-rech"+id).click(function(){
				//$('#ctn'+id).slideUp('slow');
				$("#alrt"+id).fadeOut(function(){
					$('#btns'+id).fadeIn();
					$("#btn-rech"+id).off('click');
				});
				
				//respCalidad(id, $('#cmt'+id).val().toString(), 'S');
			});
		});
		//
		/*$("#alrt"+id).removeClass('ocultar');
		$("#alrt"+id).addClass('mostrar');*/

		//$scope.alrtRechazo = "mostrar";
	}
	$scope.btnAprobar = function(id){
		dfnMsgAlert('warning', id);
		$('#cmt'+id).hide();
		$("#btns"+id).fadeOut();
		// $scope.msgAlert = "¿Esta seguro que desea aprobar esta solicitud?";
		// $scope.tipoAlerta = "alert-warning";
		$("#alrt"+id).fadeIn(function(){
			$("#btn-acept"+id).click(function(){
				respCalidad(id, '', 'S');
				$('#ctn'+id).slideUp('slow');
				$("#btn-acept"+id).off('click');
			});
			$("#btn-rech"+id).click(function(){
				$("#alrt"+id).fadeOut(function(){
					$('#btns'+id).fadeIn();
				});
				//$('#ctn'+id).slideUp('slow');
				$("#btn-rech"+id).off('click');
			});
		});
		//
		/*$("#alrt"+id).removeClass('ocultar');
		$("#alrt"+id).addClass('mostrar');*/

		//$scope.alrtRechazo = "mostrar";
	}
	$scope.setProgramSolTI = function(id_scms, id_user, prgValida, solicitud){
		//console.log(id_scms+' : '+id_user+' : '+prgValida);
		var id = "#id"+id_scms;
		//console.log(delSolTI(id_scms, prgValida, indice));
		//console.log(prgValida);
		//console.log($scope.solicitudes[indice]);
		//console.log(delSolTI(id_scms, prgValida, solicitud, 'local', localStorageService.get('idSession')));
		if (delSolTI(id_scms, prgValida, solicitud, 'local', localStorageService.get('idSession'))) {
			socket.emit('setProgramSOLTI', {
				idScms 		: id_scms,
				userID 		: id_user,
				valida		: prgValida,
				solicitud	: solicitud
			});	
		}
		/*
		// console.log(resp);
		// console.log(+' delete\n');*/
	}
	$scope.setEstadoSOLTI = function(id_scms, estado){
		socket.emit('setEstadoSOLTI', {
			id_scms : id_scms,
			estado 	: estado
		});
	}
	var delSolTI = function(id_scms, prgValida, solicitud, tipPet, idSession){
		//console.log(socket);
		/*if (prgValida=='A') {
			//console.log('HOLA A');
			$('#ctn'+id_scms).slideUp('slow', function(){
				$(this).remove();
			});
			//	solicitudes.ID_SCMS
		}else{
			//console.log('HOLA B');
			$('#li'+id_scms).slideUp('slow', function(){
				$(this).remove();
			});
		}*/
		var eliminar = function(solicitud){
			var indice = getIndexSOl(solicitud);
			if (indice != undefined) {
				var del = $scope.solicitudes.splice(indice, 1);	
			}else{
				return false;
			}
			if(del.length <= $scope.solicitudes.length) {
				return true;
			}else if ($scope.solicitudes.length == 0) {
				return true;
			}else{
				return false;	
				
			}
		}
		if (tipPet=='local') {
			return eliminar(solicitud);
		}else{
			if (idSession !== $scope.idSession) {
				return eliminar(solicitud);
			}else{
				return false;
			}
		}
	}
	function dfnMsgAlert(type, id){
		switch(type){
			case 'warning':
				var estilo = "alert-warning";
				var msg = "¿Esta seguro que desea aprobar esta solicitud?";
				break;
			case 'danger':
				var estilo = "alert-danger";
				var msg = "¿Esta seguro que desea rechazar esta solicitud?";
				break;
		}
		$('#alrt'+id).removeClass('alert-warning alert-danger');
		$('#alrt'+id).addClass(estilo);
		$('#msg'+id).html(msg);
	}
	function respCalidad(id_scms, comentario, resp){
		socket.emit('respCalidad', 
			{
				id 			: id_scms,
		        comentario 	: comentario,
		        valida		: resp
			});
	}
	$scope.mostOcu =  function(id) {
		//console.log(id);
		var id = '#detalle'+id;
		$(id).toggle('slow', function(){
			/*var clase = $(this).children('span').attr('class');
			console.log(clase)
			if (clase == 'glyphicon glyphicon-menu-down') {
				clase = 'glyphicon glyphicon-menu-up';
			}else{
				clase = 'glyphicon glyphicon-menu-down';
			}
			$(this).children('span').attr('class', clase);*/
		});
	}
	//console.log(localStorageService.get('userData'));
	$scope.userData = localStorageService.get('dataUser');
	if (typeof($scope.userData)!=="undefined") {
		socket.emit('getSolicitudes', {
			ID_USER 	: $scope.userData.ID_USER,
			area 		: $scope.userData.ID_AREA,
			ID_CARGO 	: $scope.userData.ID_CARGO
		});	
	}
	socket.on('solicitudes', function(data){
		//console.log(data);
		if ($scope.solicitudes==null) {
			$scope.solicitudes = [];
			var row =  data.length;
			for (var i = 0; i < row; i++) {
				var solicitud = $scope.validaSolTI(localStorageService.get('area'), data[i], $scope.userData.ID_CARGO);
				if (solicitud.adden) {
					$scope.solicitudes.push(data[i]);
				}
			}
			//console.log($scope.solicitudes);
		}else{
			//$scope.solicitudes.push(data[0])
			//console.log(data);
			var total = data.length;
			var it = 0;
			while(it < total) {
				var row = $scope.solicitudes.length;
				var resp = 'D';
				for (var i = 0; i < row; i++) {
					//console.log( data[it].ID_SCMS+' : '+$scope.solicitudes[i].ID_SCMS);
					if (data[it].ID_SCMS == $scope.solicitudes[i].ID_SCMS) {
						i = row;
						resp = 'E';
					}
				}
				//console.log(resp);
				if(resp === 'D'){
					//console.log('agregado');
					var solicitud = $scope.validaSolTI(localStorageService.get('area'), data[it], $scope.userData.ID_CARGO);
					if (solicitud.adden) {
						$scope.solicitudes.push(solicitud);	
					}
					
				}
				it++;
			}
			//console.log($scope.solicitudes);
			//$scope.solicitudes.push(data[0]);
			//$scope.solicitudes.push(data[0]);
		}
		//console.log($scope.solicitudes);
		//$scope.solicitudes = data;
		//$scope.solicitudes.push(data);
	});
	var getIndexSOl = function(solicitud){
		var total = $scope.solicitudes.length;
		var it = 0;
			var row = $scope.solicitudes.length;
			var resp = 'D';
			for (var i = 0; i < row; i++) {
				//console.log( data[it].ID_SCMS+' : '+$scope.solicitudes[i].ID_SCMS);
				if (solicitud.ID_SCMS == $scope.solicitudes[i].ID_SCMS) {
					return $scope.solicitudes.indexOf($scope.solicitudes[i]);
				}
			}
	}
	socket.on('respCalidad', function(data){
		console.log(data);
	});
	socket.on('respSetProgramSOLTI', function(data){
		//console.log(data);
	});
	socket.on('addDellSolTI', function(data){
		// console.log(data);
		// console.log(getIndexSOl(data.solicitud));
		if (localStorageService.get('area') == 11) {
			delSolTI (data.id_scms, data.prgValida, data.solicitud, 'server', data.idSession);
		}
	});
	allowDrop = function (ev) {
	    ev.preventDefault();
	    //console.log(ev.target);
	}
	drag = function (ev) {
	    ev.dataTransfer.setData("text", ev.target.id);
	}
	drop = function (ev, elemt) {
	    ev.preventDefault();
	    var idProgrammer = elemt.id;
	    var id_scms = ev.dataTransfer.getData("text").replace('id', '');
	    var indice = $('#id'+id_scms + ' div ul li').attr('indice');
	    //console.log(resp);
	    $scope.setProgramSolTI(id_scms, idProgrammer, 'A', indice);
	}
});

/**********
	Codigo 	
	ESP 	Espera
	PRO 	Procesando
	FIN 	Finalizado
	PAU 	Pausa
	ANU 	Anulado
**********/