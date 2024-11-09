<?php
session_start();
include '../../destruye_sesion.php';
//pasamos variables por post
$coduser = $_SESSION['codigo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Reportes Facturaci&oacute;n</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
    <link href="../../css/Static_Tabstrip.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="../../acordion/style_fac.css" rel="stylesheet" />
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <script src="../../acordion/js/jquery.min.js"></script>
    <script src="../../acordion/js/main.js"></script>
</head>
<body style="margin-top:-25px">
<div id="content">
    <form name="repofac" action="vista.reportes_gerencia.php" method="post">
        <h3 class="panel-heading" style=" background-color:#008B8E; color:#FFFFFF; font-size:18px;" align="center">Reportes Seguimiento Facturación Y Recaudo</h3>
        <div style="text-align:center">
            <table width="100%">
                <tr>
                    <td align="center" width="20%">
                        <button type="submit" name="segconciliacion" id="segconciliacion" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-list fa-lg"></i></p>Conciliación
                        </button>
                    </td>
                    <td align="center" width="20%">
                        <button type="submit" name="segdeudaXcobrar" id="segdeudaXcobrar" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-list fa-lg"></i></p>Deuda Por Cobrar
                        </button>
                    </td>
                    <td align="center" width="20%">
                        <!--button type="submit" name="segfacturacion" id="segfacturacion" class="btn btn btn-INFO"  data-toggle="modal" data-target="#consultar-1"
                                style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#008B8E; color:#008B8E; width:200px; height:100px">
                            <p><i class="fa fa-list fa-lg"></i></p>Facturación
                        </button-->
                    </td>
                    <td align="center" width="20%">
                    </td>
                    <td align="center" width="20%">
                    </td>

                </tr>
            </table>
            <p></p>
        </div>
    </form>

    <div class="modal fade" id="consultar-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="width: 45%; ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-close="1"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center" id="myModalLabel">Reportes Gerenciales</h4>
                </div>
                <div class="modal-body">
                    <iframe  frameborder="0" width="100%" class="modal-consulta-body" name="modal-consulta" style="min-height: 350px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" data-close="1">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.btn-INFO').on('click',function(e){
                //e.stopPropagation();

                $('form[name="repofac"]').on('submit', function(e) {
                    e.preventDefault();
                });

                var btn = e.target,
                    id = $(btn).attr('id'),
                    text = $(btn).text(),
                    form = $('form[name="repofac"]'),
                    url;

                $('#myModalLabel').text($.trim(text));

                switch (id) {
                    case 'segfacturacion':
                        url = 'vista.reporte_segfac.php';
                        break;case 'segrecaudo':
                        url = 'vista.reporte_segrec.php';
                        break;
                    case 'segrecaudo':
                        url = 'vista.reporte_segrec.php';
                        break;
                    case 'segconciliacion':
                        url = 'vista.reporte_concilia.php';
                        break;
                    case 'segdeudaXcobrar':
                        url = 'vista.reporte_deudaXcobrar.php';
                        break;
                }

                $('.modal-consulta-body').attr('src', url);


            })
        })
    </script>

</div>
</body>
</html>
