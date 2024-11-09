
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
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:1px solid #ccc;
                border-left:0px solid #ccc;
                border-bottom:0px solid #ccc;
                border-top:0px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
                color: #0b0c0b;
            }
            .tda{
                height:24px;
                border:1px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
                color: #8A2908;

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
        <h3 class="panel-heading" style=" background-color:#a77b25; color:#FFFFFF; font-size:18px; width:1120px" align="center">Tarifas de Aseo</h3>
        <div style="text-align:center; width:1120px;">



            <form id="ficha" method="post">
                <div>
                    <table id="flex1" style="display:block;">
                        <thead>
                        <tr>
                            <th align="center" class="th" style="width:24px">N&deg;</th>
                            <th align="center" class="th" style="width:300px">Uso</th>
                            <th align="center" class="th" style="width:100px">Nomenclatura</th>
                            <th align="center" class="th" style="width:100px">Tarifa</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>1</b></td>
                            <td class="tda" style="width:71px; text-align:center">Residencial - Nivel Socioeconomico Bajo</td>
                            <td class="tda" style="width:71px; text-align:center">R1</td>
                            <td class="tda" style="width:71px; text-align:center">100</td>
                        </tr>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>2</b></td>
                            <td class="tda" style="width:71px; text-align:center">Residencial - Nivel Socioeconomico Medio Bajo</td>
                            <td class="tda" style="width:71px; text-align:center">R2</td>
                            <td class="tda" style="width:71px; text-align:center">150</td>
                        </tr>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>3</b></td>
                            <td class="tda" style="width:71px; text-align:center">Residencial - Nivel Socioeconomico Medio</td>
                            <td class="tda" style="width:71px; text-align:center">R3</td>
                            <td class="tda" style="width:71px; text-align:center">300</td>
                        </tr>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>4</b></td>
                            <td class="tda" style="width:71px; text-align:center">Residencial - Nivel Socioeconomico Medio Alto</td>
                            <td class="tda" style="width:71px; text-align:center">R4</td>
                            <td class="tda" style="width:71px; text-align:center">350</td>
                        </tr>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>5</b></td>
                            <td class="tda" style="width:71px; text-align:center">Residencial - Nivel Socioeconomico Alto</td>
                            <td class="tda" style="width:71px; text-align:center">R5</td>
                            <td class="tda" style="width:71px; text-align:center">600</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>6</b></td>
                            <td class="tda" style="width:71px; text-align:center">Comercial Baja</td>
                            <td class="tda" style="width:71px; text-align:center">C</td>
                            <td class="tda" style="width:71px; text-align:center">1000</td>
                        </tr>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>7</b></td>
                            <td class="tda" style="width:71px; text-align:center">Comercial Media</td>
                            <td class="tda" style="width:71px; text-align:center">C</td>
                            <td class="tda" style="width:71px; text-align:center">3000</td>
                        </tr>
                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>8</b></td>
                            <td class="tda" style="width:71px; text-align:center">Comercial Alta</td>
                            <td class="tda" style="width:71px; text-align:center">C</td>
                            <td class="tda" style="width:71px; text-align:center">5000</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>9</b></td>
                            <td class="tda" style="width:71px; text-align:center">Industrial Fija</td>
                            <td class="tda" style="width:71px; text-align:center">I</td>
                            <td class="tda" style="width:71px; text-align:center">6500</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>10</b></td>
                            <td class="tda" style="width:71px; text-align:center">Inst. Sin Fines de Lucro</td>
                            <td class="tda" style="width:71px; text-align:center">I</td>
                            <td class="tda" style="width:71px; text-align:center">1000</td>
                        </tr>


                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>11</b></td>
                            <td class="tda" style="width:71px; text-align:center">Usuarios Mixtos Baja</td>
                            <td class="tda" style="width:71px; text-align:center">M</td>
                            <td class="tda" style="width:71px; text-align:center">200</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>12</b></td>
                            <td class="tda" style="width:71px; text-align:center">Usuarios Mixtos Media</td>
                            <td class="tda" style="width:71px; text-align:center">M</td>
                            <td class="tda" style="width:71px; text-align:center">468</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>13</b></td>
                            <td class="tda" style="width:71px; text-align:center">Usuarios Mixtos Alta</td>
                            <td class="tda" style="width:71px; text-align:center">M</td>
                            <td class="tda" style="width:71px; text-align:center">840</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>14</b></td>
                            <td class="tda" style="width:71px; text-align:center">Oficial Baja</td>
                            <td class="tda" style="width:71px; text-align:center">O</td>
                            <td class="tda" style="width:71px; text-align:center">1000</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>15</b></td>
                            <td class="tda" style="width:71px; text-align:center">Oficial Media</td>
                            <td class="tda" style="width:71px; text-align:center">O</td>
                            <td class="tda" style="width:71px; text-align:center">5000</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>16</b></td>
                            <td class="tda" style="width:71px; text-align:center">Oficial Alta</td>
                            <td class="tda" style="width:71px; text-align:center">O</td>
                            <td class="tda" style="width:71px; text-align:center">15000</td>
                        </tr>

                        <tr>
                            <td class="tda" style="width:24px; text-align:center"><b>17</b></td>
                            <td class="tda" style="width:71px; text-align:center">Proyecto Construcción</td>
                            <td class="tda" style="width:71px; text-align:center">PC</td>
                            <td class="tda" style="width:71px; text-align:center">300</td>
                        </tr>





                        </tbody>
                    </table>

                </div>
            </form ></div></div>

    </body>
    </html>