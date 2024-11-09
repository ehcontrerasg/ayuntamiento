<?php
session_start();

include '../../destruye_sesion.php';

$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$inmueble=$_GET['cod_inmueble'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <script type="text/javascript" src="../../js/facturaspendientes.js"></script>

    <script language="javascript">

        function rel(id1) { // Traer la fila seleccionada
            popup("vista.facturarel.php?factura="+id1,600,400,'yes');
        }

        $(document).ready(function() {

            $(".botonExcel").click(function(event) {
                $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                $("#FormularioExportacion").submit();
            });

//            $('tbody tr',g.bDiv).hover(function(){
//                alert("a");
//            })


        });


    </script>
    <style type="text/css">
        .titulo1 { color: red }

        iframe{
            border-color: #666666;
            border:solid;
            border-width:0px ;
            display: block;
            float: left;
            width: 315px;
            height: 160px;
        }

        #ifpdf{
            width: 270px;
            height: 320px;

        }

        #ifdif{
            width: 315px;
            height: 90px;

        }
    </style>
</head>
<body>
<form name="FMFactPend" action="vista.facpend.php" method="post" onsubmit="return rend();">
    <div class="flexigrid" style="width:960px">
        <div class="mDiv">
            <div><b>PROCESO DE FACTURACION</b> >> Facturas</div>
            <!--<div style="background-color:rgb(255,255,255)">
            </div>-->
        </div>
</form>
<?php
//if($proc == 1){
?>
<form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:960px">
        <div class="mDiv">
            <div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
                <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div>
        </div>
    </div>
</form>
<div style="display: block;float: left">
    <table id="Exportar_a_Excel" style="display:none">
        <tr>
            <th align="center">NUMERO</th>
            <th align="center">FACTURA</th>
            <th align="center">PERIODO</th>
            <th align="center">FECHA</th>
            <th align="center">DEUDA</th>
        </tr>
    </table>
    <table id="flex1" style="display:none">
    </table>
</div>
<iframe id="ifpdf" width="270px" height="320px"  src="vista.pdffact.php?factura" ></iframe>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {

            url: './../datos/datos.facturas.php?inmueble=<?php echo $inmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:15,  align: 'center'},
                {display: 'Consec Factura', name: 'CONSEC_FACTURA', width: 77, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'Periodo', width: 47, sortable: true, align: 'center'},
                {display: 'Fec Lectura', name: 'FEC_LECT', width: 47, sortable: true, align: 'center'},
                {display: 'Consumo Fact', name: 'LECTURA', width: 47, sortable: true, align: 'center'},
                {display: 'Expedicion', name: 'FEC_EXPEDICION', width: 60, sortable: true, align: 'center'},
                {display: 'Nfc', name: 'NCF', width: 127, sortable: true, align: 'center'},
                {display: 'Valor', name: 'TOTAL', width: 39, sortable: true, align: 'center'},
                {display: 'Pagado', name: 'TOTAL_PAGADO', width: 39, sortable: true, align: 'center'},
                {display: 'fac anteriores', name: 'ANTERIORES', width: 39, sortable: true, align: 'center'}

            ],
           /* buttons: [
                {name:'Deuda cero', bclass:'edit', onpress: test},
                {separator: true},
                {name:'Acuerdo de pago', bclass:'edit', onpress: test},
                {separator: true}
            ],*/



            sortname: "PERIODO",
            sortorder: "desc",
            usepager: false,
            title: 'facturas',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 686,
            height: 270
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



    function agrega() { // Traer la fila seleccionada
        popup("vista.agregatarifa.php",770,610,'yes');
    }

    function edita(id) { // Traer la fila seleccionada
        popup("vista.editartarifa.php?id_tarifa="+id,800,600,'yes');
    }

    function test(com,grid)
    {

        if (com=='Deuda cero')
        {
            //alert('Add New Item Action');
            agrega();

        }

        if (com=='Editar')
        {
            if($('.trSelected',grid).length>0){

                var items = $('.trSelected',grid);
                var itemlis ='';
                for(i=0;i<items.length;i++){
                    itemlis+= items[i].id.substr(3)+",";
                }
                itemlis=itemlis.substr(0,itemlis.length-1);
                edita(itemlis);


            } else {
                return false;
            }
        }




    }


    //-->
</script>

<iframe id="ifdetfact" src="vista.det_fact.php?factura" ></iframe>
<iframe id="ifnumfac"  src="vista.det_lectura.php?factura=<?php echo $inmueble;?>"></iframe>
<iframe id="ifestcon"  src="vista.estado_concepto.php?inmueble=<?php echo $inmueble;?>"></iframe>
<iframe id="ifdif"     src="vista.dif_inm.php?inmueble=<?php echo $inmueble;?>"></iframe>
<iframe id="ifdcer"     src="vista.det_deuda_cero.php?inmueble=<?php echo $inmueble;?>"></iframe>

</body>
</html>

