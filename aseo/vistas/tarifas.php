
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">

        <style type="text/css">
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            .th{
                /*background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;*/
                height:24px;
                border:1px solid #ccc;
                border-left:0px solid #ccc;
                border-bottom:0px solid #ccc;
                border-top:0px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            .tda{
                height:24px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;

            }
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .select{
                border:0px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .btn-info{
                color:#fff;
                background-color:#5bc0de;
                border-color:#46b8da;
            }
            .btn{
                display:inline-block;
                padding:6px 12px;
                margin-bottom:0;
                font-size:14px;
                font-weight:400;
                line-height:1.42857143;
                text-align:center;
                white-space:nowrap;
                vertical-align:middle;
                cursor:pointer;
                -webkit-user-select:none;
                -moz-user-select:none;
                -ms-user-select:none;
                user-select:none;
                background-image:none;
                border:1px solid transparent;
                border-radius:4px
            }
        </style>
    </head>
    <body style="margin-top:-25px" >
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Tarifas de Aseo</h3>
        <div style="text-align:center; width:1120px;">
            <form name="FMTarifas" action="vista.adminconceptos.php" method="post" onsubmit="return rend();">
                <div class="flexigrid" style="width:100%">
                    <div class="mDiv">
                        <div align="left"><b>Administraci&oacute;n de Tarifas</b></div>
                    </div>
                </div>
            </form>
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">Exportar&nbsp;&nbsp;
                        <a href="vista.reporte_excel_tarifareco.php">
                            <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
                        <a href="vista.reporte_word_tarifareco.php">
                            <img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
                        <a href="vista.reporte_pdf_tarifareco.php">
                            <img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
                    </div>
                </div>
            </div>

            <form id="ficha" method="post">
                <div>
                    <table id="flex1" style="display:block;">
                        <thead>
                        <tr>
                            <th align="center" class="th" style="width:24px">N&deg;</th>
                            <th align="center" class="th" style="width:71px">Acueducto</th>
                            <th align="center" class="th" style="width:71px">Uso</th>
                            <th align="center" class="th" style="width:71px">Calibre</th>
                            <th align="center" class="th" style="width:71px">Diametro</th>
                            <th align="center" class="th" style="width:71px">Medidor</th>
                            <th align="center" class="th" style="width:71px">Valor</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <td class="tda" style="width:24px; text-align:center"><b></b></td>
                            <td class="tda" style="width:71px; text-align:center"></td>
                            <td class="tda" style="width:71px; text-align:center"></td>
                            <td class="tda" style="width:71px; text-align:center"></td>
                            <td class="tda" style="width:71px; text-align:center"></td>
                            <td class="tda" style="width:71px; text-align:center"></td>
                            <td class="tda" style="width:71px; text-align:center"></td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </form ></div></div>

    </body>
    </html>