appSolicitudesTI.controller("ctrlMainTI", function($scope, $http, $cookies, $location,socket, auth, localStorageService) {
    socket.on('idSession', function(data){
        //console.log(data);
        localStorageService.set('idSession', data);
        //console.log(localStorageService.get('idSession'));
        $scope.idSession = data;
    });
   
    //$('#modal').hide();
    $scope.showOptAproCalidad = false;
    $scope.showOptAsignacion = false;
    $scope.showPtlRealSolTI = false;
	//socket.emit('getSession');
    //$scope.showOptAproCalidad = false;

 	$http.get('webServices/ws.getSession.php').then(function(respuesta){
        var resp = respuesta.data;
        //console.log(resp);
        socket.emit('userData', resp); 
        //console.log(resp);
    });
    socket.on('userDataResp', function(data){
    	//console.log(data[0].SOLICITUDES);
        //localStorageService.get('area');
        if (typeof(data[0])!== "undefined") {
           // console.log('hola ');
            auth.login(data[0]);
            $scope.setPermisosUsr(data[0].SOLICITUDES, data[0].ID_AREA);
            localStorageService.set('area', data[0].ID_AREA);
            localStorageService.set('acceso', data[0].SOLICITUDES);
            localStorageService.set('usuario', data[0].ID_USUARIO);
        }else{
            $scope.setPermisosUsr('logstate');
            $('#myModal').modal('show');
            //console.log('hola mundo');
        }
    });
    $('#myModal').on('hidden.bs.modal', function (e) {
        if (typeof($cookies.userData) === 'undefined') {
            $('#myModal').modal('show');
        }
    });
    socket.on('menu', function(data){
       // console.log(data);
        $scope.optMenuNavBar = data;
    });
    $scope.logout = function(){
        auth.logout();
    }
    $scope.goIndex = function(){
        auth.goIndex();
    }
    $scope.setPermisosUsr = function (acceso, area) {
        if(acceso == 'S'){
            switch(area){
                case 13: // CALIDAD
                    // console.log('hola');
                    $scope.showOptAproCalidad = true; //
                    $scope.showPtlRealSolTI = false;
                    $scope.showSolicitudesTI = true;
                    $scope.showOptAsignacion = false;
                    $scope.showLstProg = false;
                    $location.path("/solicitudes/calidad");
                    break;
                case 11: //INFORMATICA
                    //console.log($cookies.userData);
                    //$scope.alrtRechazo = "ocultar";
                    $scope.showOptAproCalidad = false;
                    $scope.showPtlRealSolTI = true;
                    $scope.showSolicitudesTI = true;
                    if ($cookies.userData.ID_CARGO == 111) {
                        $scope.showOptAsignacion = true; //Asignacion de solicitudes TI
                        $scope.showLstProg = true;
                        socket.emit('lstProgrammer', 'Hi mom!');
                        socket.on('Programmers', function(data){
                            $cookies.programmers = data;
                        });
                        $scope.btnIniciar = false;
                    }else{
                        $scope.btnIniciar = true;
                    }
                    $location.path("/solicitarTI");
                    break;
                default:
                    $scope.showOptAproCalidad = false;
                    $scope.showOptAsignacion = false;
                    $scope.showPtlRealSolTI = true;
                    $scope.showSolicitudesTI = true;
                    $scope.showLstProg = false;
                    auth.redireccionr();
            }
        }else if (acceso == 'logstate'){
            //console.log('logastate arega');
            $scope.showOptAproCalidad = false;
            $scope.showOptAsignacion = false;
            $scope.showPtlRealSolTI = false;
            $scope.showSolicitudesTI = false;
            $scope.showLstProg = false;
            $scope.userData = undefined;
        }else{
            //console.log('hola sino');
            //document.location.href = "../";
        }
    }
    $scope.validaSolTI = function(area, solicitud, cargo){
        //console.log(solicitud);
       // var resp = {visible:false, adden:false};
        var idEncargado = 111;
        switch(area){
            case 13:
                if (solicitud.VALIDA_CALIDAD == null) {
                    solicitud.visible = true;
                    solicitud.adden = true;
                }else{
                    //solicitud.visible = false;
                    solicitud.adden = false;
                }
                break;
            case 11:
                if (solicitud.VALIDA_CALIDAD == 'S') {
                    solicitud.adden = true;
                    if (cargo!=idEncargado) {
                        
                        if (solicitud.ID_DESARROLLADOR == localStorageService.get('usuario')){
                            
                            solicitud.visible = true;
                            solicitud.adden = true;
                        }else{
                            //solicitud.visible = false;
                            solicitud.adden = false;
                        }
                    }else if (solicitud.ID_DESARROLLADOR == null){
                        //console.log('encargado');
                        solicitud.adden = true;
                        solicitud.visible = true;
                    }else{
                        solicitud.visible = false;
                    }
                }else{
                    solicitud.adden = false;
                }
                break;
            default:
                if (solicitud.ID_SOLICITADOR == localStorageService.get('usuario')) {
                    solicitud.visible = true;
                    solicitud.adden = true;
                }else{
                    solicitud.adden = false;
                }
                
        }
        return solicitud;
    }
	/*$('#sltMenu').change(function(){
		console.log($(this).val());
		//socket.emit('lstpantallas', {"hola" : "mundo"});
	});*/
});
/*
*	13 - Calidad
*	11 - Informatica

    1  -  CATASTRO    S
    2  -  FACTURACION S
    3  -  CORTE Y RECONEXION  S
    4  -  RECAUDO S
    6  -  GERENCIAL   N
    7  -  CAASD NORTE S
    8  -  CAASD ESTE  S
    9  -  SERVICIO AL CLIENTE S
    10 -  MEDIDORES   S
    11 -  INFORMATICA S
    13 -  CALIDAD S
    14 -  GRANDES CLIENTES    S
    15 -  ARCHIVO S
    16 -  COMPRA  S
*	
*/


