<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
$coduser = $_SESSION['codigo'];
include_once 'class.parametros.php';
//$periodo = ($_GET['periodo']);
//$operario = ($_GET['operario']);
//$ruta = ($_GET['ruta']);
//$periodo = $_POST['periodo'];

//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
$a=new parametros();
$codcargo=$a->ObtenerCargo($_SESSION['codigo']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--<meta http-equiv="refresh" content="5; URL=rendimiento.php?periodo=<?php //echo $periodo1;?>&ruta=<?php //echo $ruta1;?>&proc=1" />-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--JQUERY -->
    <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
    <link rel="stylesheet" href="../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../flexigrid/flexigrid.js"></script>
    <!--script language="javascript">
        $(document).ready(function() {
            $(".botonExcel").click(function(event) {

                $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                //$("#FormularioExportacion").submit();
                descargarExcel(event);
            });
        });

        //Función para descargar en formato Excel
        function descargarExcel(e,empresa){

            //creating a temporary HTML link element (they support setting file names)
            var a = document.createElement('a');
            //getting data from our div that contains the HTML table
            var data_type = 'data:application/vnd.ms-excel';
            var table_div = document.getElementById('datos_a_enviar');
            var table_html = table_div.outerHTML.replace(/ /g, '%20');
            a.href = data_type + ', ' + table_div;
            //setting the file name
            a.download = 'Inmuebles.xls';
            //triggering the function
            a.click();
            //just in case, prevent default behaviour
            e.preventDefault();

        }
    </script-->
</head>
<body>

<form name="inmuebles" action="inmuebles.php" method="post" onsubmit="return rend();">
    <h3 class="panel-heading" style=" background-color:#a77b25; color:#FFFFFF; font-size:18px; width:1120px; height: 40px; text-align-all: center" align="center">Listado de Inmuebles</h3>
</form>

<form action="../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:1110px">
        <div class="mDiv">

            <!--div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
                <img src="../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                <img src="../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div-->
        </div>
    </div>
</form>
<div>
    <table id="Exportar_a_Excel" style="display:none">
        <tr>
            <th align="center">No</th>
            <th align="center">CODIGO</th>
            <th align="center">ZONA</th>
            <th align="center">URBANIZACION</th>
            <th align="center">DIRECCIÓN</th>
            <!--th align="center">ESTADO</th-->
            <th align="center">TIPO CLIENTE</th>
            <th align="center">DOCUMENTO</th>
            <th align="center">CLIENTE</th>
            <th align="center">CATASTRO</th>
            <th align="center">ID PROCESO</th>
            <th align="center">USO</th>
            <th align="center">ACTIVIDAD</th>
            <th align="center">TOTAL UNIDADES</th>
            <th align="center">UNIDADES HABITADAS</th>
            <th align="center">UNIDADES DESHABITADAS</th>
        </tr>
    </table>
    <table id="flex1" style="display:none">
    </table>
</div>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid (
        {
            url: 'datos_inmuebles.php',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'numero', width: 20, sortable: false, align: 'center'},
                {display: 'Codigo', name: 'i.codigo_inm', width: 38, sortable: true, align: 'center'},
                {display: 'Zona', name: 'id_zona', width: 23, sortable: true, align: 'center'},
                {display: 'Urbanización', name: 'desc_urbanizacion', width: 130, sortable: true, align: 'center'},
                {display: 'Dirección', name: 'direccion', width: 185, sortable: true, align: 'center'},
                //{display: 'Estado', name: 'i.id_estado', width: 30, sortable: true, align: 'center'},
                {display: 'Tipo Cliente', name: 'id_tipo_cliente', width: 52, sortable: true, align: 'center'},
                {display: 'Documento', name: 'docuemnto', width: 52, sortable: true, align: 'center'},
                {display: 'Cliente', name: 'cliente', width: 150, sortable: true, align: 'center'},
                {display: 'Id Proceso', name: 'id_proceso', width: 62, sortable: true, align: 'center'},
                {display: 'Id Catastro', name: 'catastro', width: 106, sortable: true, align: 'center'},
                {display: 'Uso', name: 'id_uso', width: 19, sortable: true, align: 'center'},
                {display: 'Actividad', name: 'desc_actividad', width: 170, sortable: true, align: 'center'},
                {display: 'Unidades', name: 'total_unidades', width: 45, sortable: true, align: 'center'},
                {display: 'U.Hab', name: 'unidades_hab', width: 31, sortable: true, align: 'center'},
                {display: 'U.No Hab', name: 'unidades_des', width: 47, sortable: true, align: 'center'}
            ],
            <?php
            if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5){
            ?>
            buttons : [
                {name: 'Agregar', bclass: 'add', onpress : test},
                {separator: true},

                {name: 'Editar', bclass: 'edit', onpress : test},
                {separator: true},
                {name: 'Cambio Ubicacion', bclass: 'map', onpress : test},
                {separator: true}
            ],
            <?php
            }
            ?>

            searchitems : [
                {display: 'Codigo Sistema', name: 'i.codigo_inm',isdefault: true},
                {display: 'Zona', name: 'id_zona'},
                //{display: 'Estado', name: 'i.id_estado'},
                {display: 'Urbanización', name: 'desc_urbanizacion'},
                {display: 'Dirección', name: 'direccion'},
                {display: 'Tipo Cliente', name: 'id_tipo_cliente'},
                {display: 'Id Proceso', name: 'id_proceso'},
                {display: 'Id Catastro', name: 'catastro'},
                {display: 'Uso', name: 'id_uso'},
                {display: 'Actividad', name: 'desc_actividad'}
            ],
            sortname: "i.codigo_inm",
            sortorder: "DESC",
            usepager: true,
            title: 'Listado Inmuebles',
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
    function popup(uri, awid, ahei, scrollbar)
    {
        var params;
        if (uri != "")
        {
            if (popped && !popped.closed)
            {
                popped.location.href = uri;
                popped.focus();
            }
            else
            {
                params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                popped = window.open(uri, "popupinm", params);
            }
        }
    }
    function agregar()
    { // Traer la fila seleccionada
        popup("vistas/vista.agregainmueble.php",1100,800,'yes');
    }
    function edita(id2)
    { // Traer la fila seleccionada
        popup("vistas/vista.actualizarinmueble.php?cod_sistema="+id2,1100,800,'yes');
    }
    function activar(id2)
    { // Traer la fila seleccionada
        popup("activa_inmueble.php?cod_sistema="+id2,1100,800,'yes');
    }

    function detalle(id3)
    { // Traer la fila seleccionada
        popup("vistas/vista.detalle.php?cod_inmueble="+id3,1100,800,'yes');
    }
    function cambioub(id4)
    { // Traer la fila seleccionada
        popup("vistas/vista.cambiarubicacion.php?cod_inmueble="+id4,1100,800,'yes');
    }

    function test(com,grid)
    {
        if (com=='Eliminar')
        {
            if($('.trSelected',grid).length>0)
            {
                if(confirm('Desea eiminar estos  ' + $('.trSelected',grid).length + ' Inmuebles del sistema?'))
                {
                    var items = $('.trSelected',grid);
                    var itemlist ='';
                    for(i=0;i<items.length;i++)
                    {
                        itemlist+= items[i].id.substr(3)+",";
                    }
                    $.ajax
                    (
                        {
                            type: "POST",
                            dataType: "json",
                            url: './datos/datos.eliminainm.php',
                            data: "items="+itemlist,
                            success: function(data)
                            {
                                alert("Has eliminado "+data.total+" registro(s)");
                                $("#flex1").flexReload();
                            }
                        }
                    );
                }
            }
            else
            {
                return false;
            }
        }
        else if (com=='Agregar')
        {
            //alert('Add New Item Action');
            agregar();
        }
        else if (com=='Editar')
        {
            if($('.trSelected',grid).length>0)
            {

                var items = $('.trSelected',grid);
                var itemlis ='';
                itemlis= items[0].id.substr(3);
                edita(itemlis);

            }
            else
            {
                return false;
            }
        }
        else if (com=='Cambio Ubicacion')
        {
            if($('.trSelected',grid).length>0 && $('.trSelected',grid).length<3)
            {
                if(confirm('Desea Cambiar la ubicacion de estos  ' + $('.trSelected',grid).length + ' Inmuebles?'))
                {
                    var items = $('.trSelected',grid);
                    var itemlist ='';
                    for(i=0;i<items.length;i++)
                    {
                        itemlist+= items[i].id.substr(3)+",";
                    }
                    itemlist=itemlist.substring(0, itemlist.length-1);
                    cambioub(itemlist);
                }
            }
            else
            {
                return false;
            }
        }
    }
    //-->
</script>
<?php
//}
?>
</body>
</html>
<script type="text/javascript" language="javascript">


    function rend(){
        if (document.rendimiento.periodo.value == "") {
            document.rendimiento.proc.value=0;
            return false;
        }
        else {
            document.rendimiento.proc.value = 1;
            return true;
        }
    }

    function recarga() {
        document.inmuebles.ruta.selectedIndex = 0;
        document.rendimiento.submit();
    }

</script>