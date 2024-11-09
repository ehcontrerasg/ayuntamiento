var appSolicitudesTI = angular.module("appSolicitudesTI", ["btford.socket-io", "ngRoute", "ngCookies", "LocalStorageModule", "angular-toArrayFilter"]);
appSolicitudesTI.config(function($routeProvider, localStorageServiceProvider) {
    $routeProvider.when("/solicitarTI", {
        controller: "ctrlSolicitarTI",
        templateUrl: "solicitante.php"
    }).when("/solicitudes", {
        controller: "ctrlSolicitudesTI",
        templateUrl: "solicitudes.html"
    }).when("/solicitudes/calidad", {
        controller: "ctrlSolicitudesTI",
        templateUrl: "solicitudes.html"
    }).when("/solicitudes/reportes", {
        //controller: "ctrlSolicitudesTI",
        templateUrl: "reportes/reporte.solicitudTI.php"
    }).when("/", {
        controller: "ctrlMainTI"
    });
    /*.otherwise({
        redirectTo: 'index.php'
    });*/
    localStorageServiceProvider.setPrefix('appSolicitudesTI').setStorageType('sessionStorage').setNotify(true, true);
    /*.when("/logout", {
            controller : "ctrlSolicitudesTI",
            templateUrl : "../index.php"
        });/*
        .otherwise({
            redirectTo: 'solicitante.php'
        });
    /*.when("/", {
        controller : "ctrlSolicitudesTI",
        templateUrl : "templates/home.html"
    })*/
});
/*myApp.config(function (localStorageServiceProvider) {
  localStorageServiceProvider
    .setPrefix('myApp')
    .setStorageType('sessionStorage')
    .setNotify(true, true)
});*/
appSolicitudesTI.factory('socket', function(socketFactory) {
    var myIoSocket = io.connect('http://172.16.1.214:3000');
    mySocket = socketFactory({
        ioSocket: myIoSocket
    });
    return mySocket;
});
appSolicitudesTI.factory("auth", function($cookies, $cookieStore, $location, $http, socket, localStorageService) {
    return {
        login: function(dataUser) {
            //console.log(username + ' : ' +password);
            //$location.path("/solicitarTI");
            if (typeof(dataUser) !== "undefined") {
                //creamos la cookie con el nombre que nos han pasado
                $cookies.userData = {
                    USERNAME: dataUser.LOGIN,
                    PASSWORD: dataUser.PASS,
                    ID_USER: dataUser.ID_USUARIO,
                    ID_AREA: dataUser.ID_AREA,
                    ID_CARGO: dataUser.ID_CARGO,
                    ACCESO: dataUser.SOLICITUDES
                }
                localStorageService.set('dataUser', $cookies.userData);
                //$cookies.password = password;
                //$cookies.idUser = id;
                //mandamos a la home
                //console.log(username + ' : ' +password);
            }
            /*else{
                             $('#myModal').modal('show');
                             console.log('hola mundo');
                        }*/
        },
        redireccionr: function() {
            $location.path("/solicitarTI");
        },
        logout: function() {
            localStorageService.remove('area', 'acceso', 'idSession');
            document.location.href = "../logout.php";
            //al hacer logout eliminamos la cookie con $cookieStore.remove
            /* $cookieStore.remove(userData);
             $window.location.href = "../index.php";
             //mandamos al login
             //$location.path("/");*/
        },
        goIndex: function() {
            // localStorageService.remove('area', 'acceso', 'idSession');
            document.location.href = "../";
        },
        checkStatus: function(i) {
            $http.get('webServices/ws.getSession.php').then(function(respuesta) {
                if (i != 1) {
                    var resp = respuesta.data;
                    //console.log(resp);
                    if (typeof(resp.usuario) === "undefined") {
                        $cookies.userData = undefined;
                        $('#myModal').modal('show', function() {
                            $('#main').html(' ');
                        });
                    }
                }
            });
            /*console.log(i+" "+typeof($cookies.userData));
                if (typeof($cookies.userData)==="undefined") {
                    console.log("es un indefinid "+ typeof($cookies.userData));
                }
                //creamos un array con las rutas que queremos controlar
                var rutasPrivadas = ["/solicitarTI", "/solicitudes", "/solicitudes/calidad"];
                if(this.in_array($location.path(),rutasPrivadas) && typeof($cookies.userData) === "undefined")
                {
                    //$location.path("/login");
                   //$('#myModal').modal('show');
                   //console.log('hola mundo');
                }
                //en el caso de que intente acceder al login y ya haya iniciado sesión lo mandamos a la home
                if(this.in_array("/",rutasPrivadas) && typeof($cookies.userData) != "undefined")
                {
                    //$location.path("/solicitarTI");
                    console.log('hola');

                }    */
        },
        in_array: function(needle, haystack) {
            var key = '';
            for (key in haystack) {
                if (haystack[key] == needle) {
                    return true;
                }
            }
            return false;
        }
    }
});
//mientras corre la aplicación, comprobamos si el usuario tiene acceso a la ruta a la que está accediendo
appSolicitudesTI.run(function($rootScope, auth) {
    // auth.checkStatus();
    //al cambiar de rutas
    var i = 0;
    $rootScope.$on('$routeChangeStart', function() {
        //llamamos a checkStatus, el cual lo hemos definido en la factoria auth
        //la cuál hemos inyectado en la acción run de la aplicación
        auth.checkStatus(i++);
    });
});
appSolicitudesTI.filter('titleCase', function() {
    return function(input) {
        input = input || '';
        return input.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    };
});

function titleCase(input) {
    input = input || '';
    return input.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};