<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>

        <script language="javascript">

            function rel(id1) { // Traer la fila seleccionada
                popup("vista.facturarel.php?factura="+id1,600,400,'yes');
            }
        </script>
        <style type="text/css">
            .titulo1 { color: red }

            iframe{
                border-color: #666666;
                border:solid;
                border-width:0px ;
                display: block;
                float: left;
                width: 310px;
                height: 230px;
            }

            #ifpdf{
                width: 550px;
                height: 242px;
            }

            #ifdif{
                width: 310px;
                height: 130px;
            }
            #ifdcer{
                width: 310px;
                height: 130px;
            }
        </style>
    </head>
    <body>
    <div class="flexigrid" >
        <div class="mDiv">
        </div>

        <div style="display: block;float: left">
            <table id="flex4" style="display:none">
            </table>
        </div>
        <!--iframe marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="400" style="background-color: #E0E0E0;"-->

        <iframe id="ifpdf" name="ifpdf" src="../clases/classFacturaPdf.php?factura="></iframe>
        <script type="text/javascript">
            <!--
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex4").flexigrid	(
                {
                    url: '../datos/datos.facturas.php?inmueble=<?php echo $codinmueble;?>',

                    dataType: 'json',
                    colModel : [
                        {display: 'No', name: 'rnum', width:10,  align: 'center'},
                        {display: 'Consec<br/> Factura', name: 'CONSEC_FACTURA', width: 43, sortable: true, align: 'center'},
                        {display: 'Periodo', name: 'Periodo', width: 31, sortable: true, align: 'center'},
                        {display: 'Fec<br/> Lectura', name: 'FEC_LECT', width: 48, sortable: true, align: 'center'},
                        {display: 'Consumo<br/> Fact', name: 'LECTURA', width: 40, sortable: true, align: 'center'},
                        {display: 'Expedicion', name: 'FEC_EXPEDICION', width: 48, sortable: true, align: 'center'},
                        {display: 'Nfc', name: 'NCF', width: 112, sortable: true, align: 'center'},
                        {display: 'Valor', name: 'TOTAL', width: 32, sortable: true, align: 'center'},
                        {display: 'Pagado', name: 'TOTAL_PAGADO', width: 31, sortable: true, align: 'center'},
                        {display: 'Fecha <br/> pago', name: 'FECHA_PAGO', width: 52, sortable: true, align: 'center'},
                        {display: 'Dias', name: 'Dias', width: 30, sortable: true, align: 'center'},
                        {display: 'Reliquida', name: 'ANTERIORES', width: 38, sortable: true, align: 'center'}
                    ],

                    sortname: "PERIODO",
                    sortorder: "desc",
                    usepager: false,
                    //title: 'facturas',
                    useRp: false,
                    rp: 1000,
                    page: 1,
                    showTableToggleBtn: false,
                    width: 690,
                    height: 200
                });

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
        </script>

        <iframe id="ifdetfact" src="vista.det_fact.php?factura" ></iframe>
        <iframe id="ifnumfac" src="vista.det_lectura.php?factura=<?php echo $codinmueble;?>"></iframe>
        <iframe id="ifestcon" src="vista.estado_concepto.php?inmueble=<?php echo $codinmueble;?>"></iframe>
        <!--iframe id="ifdif" src="vista.dif_inm.php?inmueble=<?php echo $codinmueble;?>"></iframe>
		<iframe id="ifdcer" src="vista.detdeudacero.php?inmueble=<?php echo $codinmueble;?>"></iframe-->
    </div>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

