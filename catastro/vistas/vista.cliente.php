<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();

    include '../../destruye_sesion.php';

    $_SESSION['tiempo']=time();
    $_POST['codigo']=$_SESSION['codigo'];

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script language="javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
    </head>
    <body>
    <form name="inmuebles" action="vista.cliente.php" method="post" onsubmit="return rend();">
        <div class="flexigrid" style="width:1110px">
            <div class="mDiv">
                <div><b>INCORPORACIÓN</b> >> Listado de Clientes</div>
                <!--<div style="background-color:rgb(255,255,255)">
                </div>-->
            </div>
        </div>
    </form>
    <?php
    //if($proc == 1){
    ?>
    <form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        <div class="flexigrid" style="width:1110px">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                    <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
                </div>
            </div>
        </div>
    </form>
    <div>
        <table id="Exportar_a_Excel" style="display:none">
            <tr>
                <th align="center">NUMERO</th>
                <th align="center">ID</th>
                <th align="center">NOMBRE</th>
                <th align="center">DIRECCION</th>
                <th align="center">TELEFONO</th>
                <th align="center">EMAIL</th>
                <th align="center">TIPO DOCUMENTO</th>
                <th align="center">DOCUMENTO</th>
                <th align="center">CODIGO GRUPO</th>
                <th align="center">DIRECCION CORRESPONDENCIA</th>
                <th align="center">CORRESPONDENCIA</th>
            </tr>
        </table>
        <table id="flex1" style="display:none">
        </table>
    </div>
    <script type="text/javascript">
        <!--
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {
                url: './../datos/datos.clientes.php',
                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'rnum', width: 25,  align: 'center'},
                    {display: 'Codigo', name: 'codigo_cli', width: 32, sortable: true, align: 'center'},
                    {display: 'Nombre', name: 'nombre_CLI', width: 130, sortable: true, align: 'center'},
                    {display: 'Direccion', name: 'direccion', width: 100, sortable: true, align: 'center'},
                    {display: 'Telefono', name: 'telefono', width: 70, sortable: true, align: 'center'},
                    {display: 'Email', name: 'email', width: 80, sortable: true, align: 'center'},
                    {display: 'Tipo de documento', name: 'tipo_doc', width: 90, sortable: true, align: 'center'},
                    {display: 'documento', name: 'documento', width: 60, sortable: true, align: 'center'},
                    {display: 'Grupo', name: 'desc_grupo', width: 50, sortable: true, align: 'center'},
                    {display: 'Direccion de correspondencia', name: 'dir_correspondencia', width: 143, sortable: true, align: 'center'},
                    {display: 'Correspondencia', name: 'correspondencia', width: 80, sortable: true, align: 'center'}
                ],

                buttons: [
                    {name:'Agregar', bclass:'add', onpress: test},
                    {separator: true},
                    {name:'Eliminar', bclass:'delete', onpress: test},
                    {separator: true},
                    {name:'Editar', bclass:'edit', onpress: test},
                    {separator: true}
                ],
                searchitems : [
                    {display: 'Codigo', name: 'codigo_cli', isdefault: true},
                    {display: 'Nombre', name: 'nombre_cli'},
                    {display: 'Telefono', name: 'telefono'},
                    {display: 'Email', name: 'email'},
                    {display: 'Tipo Documento', name: 'tipo_doc'},
                    {display: 'Documento', name: 'documento'},
                    {display: 'Grupo', name: 'desc_grupo'}
                ],





                sortname: "codigo_cli",
                sortorder: "asc",
                usepager: true,
                title: 'Clientes',
                useRp: true,
                rp: 30,
                page: 1,
                showTableToggleBtn: true,
                width: 1110,
                height: 350
            }
        );


        //FUNCION PARA ABRIR UN POPUP
        var popped = null;
        function popup(uri, awid, ahei, scrollbar) {
            var params;
            if (uri != "") {
                if (popped && !popped.closed) {
                    popped.location.href = uri;
                    popped.focus();
                }
                else {
                    params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                    popped = window.open(uri, "popup", params);
                }
            }
        }


        //-->
        function edita(id2) { // Traer la fila seleccionada
            popup("vista.actualizarcliente.php?cod_cliente="+id2,800,600,'yes');
        }

        function elimina(id2) { // Traer la fila seleccionada
            popup("vista.eliminarcliente.php?cod_cliente="+id2,400,300,'yes');
        }

        function agrega() { // Traer la fila seleccionada
            popup("vista.agregarcliente.php",770,610,'yes');
        }

        function test(com,grid)
        {
            if (com=='Eliminar')
            {
                if($('.trSelected',grid).length>0){
                    if(confirm('Desea eiminar estos  ' + $('.trSelected',grid).length + ' Usuarios del sistema?')){
                        var items = $('.trSelected',grid);
                        var itemlist ='';
                        for(i=0;i<items.length;i++){
                            itemlist+= items[i].id.substr(3)+",";
                        }
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '../datos/datos.eliminacli.php',
                            data: "items="+itemlist,
                            success: function(data){
                                alert("Has eliminado "+data.total+" registro(s)");
                                $("#flex1").flexReload();
                            }
                        });
                    }
                } else {
                    return false;
                }
            }

            if (com=='Agregar')
            {
                //alert('Add New Item Action');
                agrega();

            }



            if (com=='Editar')
            {
                if($('.trSelected',grid).length>0){
                    if(confirm('Desea actualizar este cliente?')){
                        var items = $('.trSelected',grid);
                        var itemlis ='';
                        itemlis= items[0].id.substr(3);
                        edita(itemlis);

                    }
                } else {
                    return false;
                }
            }
        }


        //-->
    </script>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

