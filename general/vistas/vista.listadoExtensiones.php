
<html>
    <head>
        <link href='../../css/general.css?<?echo time();?>' rel='stylesheet' type='text/css'/>
        <script src='../../js/jquery-1.11.2.min.js'></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!-- Bootstrap -->
        <link rel='stylesheet' href='../../css/bootstrap.min.css'>
        <script type='text/javascript' src='../../js/bootstrap.min.js'></script>
        <!--datatable-->
        <link rel='stylesheet' href='../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css'>
        <script src='../../js/DataTables-1.10.15/media/js/jquery.dataTables.js'></script>
    </head>
    <body>
        <input type='button' id='btnListadoExtensiones' style='display: none'>
<div class='modal fade' id='listadoExtensiones' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
     <div class='modal-dialog' role='document'>
         <div class='modal-content'>
             <div class='modal-header'>
                 <button type='button' class='close' data-dismiss='modal' aria-label='Close' data-close='1'><span aria-hidden='true'>&times;</span></button>
                 <button type='button' class='close' data-dismiss='modal' aria-label='Close' id='minimize-buttom'>&minus;</button>
                 <h4 class='modal-title' id='myModalLabel'>Listado de extensiones</h4>
             </div>
             <div class='modal-body'>
                 <table id='dtListadoExtensiones'>

                 </table>
             <div class='modal-footer'>
                 <button type='button' class='btn btn-default' data-dismiss='modal' data-close='1'>Cerrar</button>
             </div>
         </div>
     </div>
 </div>

    </body>
    <script>
        var btnListadoExtensiones = $('#btnListadoExtensiones');
        btnListadoExtensiones.click(function(){  $('#listadoExtensiones').modal('show');
                                                 compSession(cargarListado);
        });

        btnListadoExtensiones.click();

        function cargarListado(){
            $.ajax({
                type:'POST',
                url: '../datos/datos.consulta.php',
                data: {tip:'get_extensiones'},
                success:function(res){
                    if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                        $('#dataTable').DataTable().destroy();
                    }
                    $('#dtListadoExtensiones').DataTable( {
                        data: JSON.parse(res),
                        dom: 'Bfrtip',
                        columns: [
                            { title: 'EXTENSION' },
                            { title: 'DEPARTAMENTO' },
                            { title: 'USUARIO' },
                            { title: 'DESCRIPCION'}
                        ],
                        'info':     false,
                        'order': [[ 2, 'desc' ]],
                        'paging' : true,
                        "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}
                    });
                    $('#dataTable').show();

                },
                error:function(xhr,error){
                    console.log(xhr+' '+error);
                }
            });
        }

        function compSession(callback)
        {
            $.ajax
            ({
                url : '../../configuraciones/session.php',
                type : 'POST',
                data:{tip : 'sess'},
                dataType : 'json',
                success : function(json) {
                    if(json==true){
                        if(callback){
                            callback();
                        }
                    }else if(json==false){

                        $('#listadoExtensiones').modal('toggle');
                        swal({
                                title: "Mensaje ",
                                html: true,
                                type: "input",
                                text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                                    "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password' tabindex='4' placeholder='Password' class='estilo-inp' required id='pass'>",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                closeOnCancel: true,
                                inputPlaceholder: "Usuario",
                                animation: "slide-from-top"

                            },

                            function(inputValue){
                                if (inputValue === false)
                                    return false;
                                if ($("#usr").val() == "") {
                                    swal.showInputError("El usuario no puede ser vacio !");
                                    return false
                                }else  if ($("#pass").val() == "") {
                                    swal.showInputError("La contraseña no puede ser vacia !");
                                    return false
                                }

                                else{
                                    iniSes();
                                    btnListadoExtensiones.click();
                                }
                            }
                        );


                        return false;
                    }
                },
                error : function(xhr, status) {
                    swal
                    (
                        {
                            title: "Mensaje!",
                            text: "Su sesion ha finalizado.",
                            showConfirmButton: true
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                top.location.replace("../../index.php")
                            }
                        }
                    );
                    return false;
                }
            });
        }

        function iniSes(){
            $.ajax
            ({
                url : '../../configuraciones/session.php',
                type : 'POST',
                data : { tip : 'iniSes',pas:$("#pass").val(),usu:$("#usr").val()},
                dataType : 'text',
                success : function(json) {
                    if (json=="true"){
                        swal("Loggin Exitoso!")
                    }else if(json=="false"){
                        swal({
                                title: "Mensaje ",
                                html: true,
                                type: "input",
                                inputPlaceholder: "Write something",
                                text: "Usuario o Contraseña  incorrecta.<br>" +
                                    " <input type='text' class='estilo-inp' placeholder='Usuario' id='usr'><br> <input  placeholder='Password' tabindex='4' class='estilo-inp' type='password' id='pass'>",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                inputPlaceholder: "Usuario",
                                animation: "slide-from-top"

                            },
                            function(i){
                                if (i === false)
                                    return false;
                                if ($("#usr").val() == "") {
                                    swal.showInputError("El usuario no puede ser vacio !");
                                    return false
                                }else  if ($("#pass").val() == "") {
                                    swal.showInputError("La contraseña no puede ser vacia !");
                                    return false
                                }

                                else{
                                    iniSes();
                                }
                            });
                    }
                },
                error : function(xhr, status) {
                    alert("error");
                    return false;
                }
            });

        }

    </script>
</html>