<?php

include_once "../../clases/class.medidor.php";

//Se reciben los datos desde la vista.
$proyecto       = $_POST["proyecto"];
$proIni         = $_POST["proIni"];
$proFin         = $_POST["proFin"];
$codSis         = $_POST["codSis"];
$manIni         = $_POST["manIni"];
$manFin         = $_POST["manFin"];
$usr_asignado   = $_POST["operario"];
$periodo        = $_POST["periodo"];

//Se instancia la clase de medidor y se obtienen los datos del mantenimient ocorrectivo.
$m                    = new Medidor();
$datosMantCorrectivos = $m->getMantCorByProyProManzOperExcel($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$usr_asignado,$periodo);

//Formar las filas de datos.
$tr = '';
while($fila = oci_fetch_assoc($datosMantCorrectivos)){
    $codigoInmueble   = $fila["CODIGO_INM"];
    $urbanizacion     = $fila["URBANIZACION"];
    $direccion        = $fila["DIRECCION"];
    $nombre           = $fila["NOMBRE"];
    $sector           = $fila["ID_SECTOR"];
    $ruta             = $fila["ID_RUTA"];
    $proceso          = $fila["ID_PROCESO"];
    $catastro         = $fila["CATASTRO"];
    $marcaMedidor     = $fila["DESC_MEDIDOR"];
    $serial           = $fila["SERIAL"];
    $calibre          = $fila["DESC_CALIBRE"];
    $estado           = $fila["ID_ESTADO"];
    $uso              = $fila["USO"];
    $lectura          = $fila["LECTURA"];
    $fechaInstalacion = date("d/m/Y",strtotime($fila["FECHA_INSTALACION"]));

    $tr.=  "<tr>
                 <td style='border: 2px solid black'>".$codigoInmueble  ."</td>
                 <td style='border: 2px solid black'>".$urbanizacion    ."</td>
                 <td style='border: 2px solid black'>".$direccion       ."</td>
                 <td style='border: 2px solid black'>".$nombre          ."</td>
                 <td style='border: 2px solid black'>".$sector          ."</td>
                 <td style='border: 2px solid black'>".$ruta            ."</td>
                 <td style='border: 2px solid black'>".$proceso         ."</td>
                 <td style='border: 2px solid black'>".$catastro        ."</td>
                 <td style='border: 2px solid black'>".$marcaMedidor    ."</td>
                 <td style='border: 2px solid black'>".$serial          ."</td>
                 <td style='border: 2px solid black'>".$calibre         ."</td>
                 <td style='border: 2px solid black'>".$estado          ."</td>
                 <td style='border: 2px solid black'>".$uso             ."</td>
                 <td style='border: 2px solid black'>".$lectura         ."</td>
                 <td style='border: 2px solid black'>".$fechaInstalacion."</td>
            </tr>";
}

//Se forma la tabla HTML que contiene la información del excel.
$html = "
            <!doctype html>
            <html>
                <head>        
                    <meta charset='UTF-8' content='text/html'/>
                </head>
                <body>
                    <table id='tblManCorr'>
                        <thead>
                            <tr>
                                <th style='border: 2px solid black'>"."CODIGO"              ."</th>
                                <th style='border: 2px solid black'>"."URBANIZACION"        ."</th>
                                <th style='border: 2px solid black'>"."DIRECCION"           ."</th>
                                <th style='border: 2px solid black'>"."NOMBRE"              ."</th>
                                <th style='border: 2px solid black'>"."SECTOR"              ."</th>
                                <th style='border: 2px solid black'>"."RUTA"                ."</th>
                                <th style='border: 2px solid black'>"."PROCESO"             ."</th>
                                <th style='border: 2px solid black'>"."CATASTRO"            ."</th>
                                <th style='border: 2px solid black'>"."MARCA DE MEDIDOR"    ."</th>
                                <th style='border: 2px solid black'>"."SERIAL"              ."</th>
                                <th style='border: 2px solid black'>"."CALIBRE"             ."</th>
                                <th style='border: 2px solid black'>"."ESTADO"              ."</th>
                                <th style='border: 2px solid black'>"."USO"                 ."</th>
                                <th style='border: 2px solid black'>"."LECTURA"             ."</th>
                                <th style='border: 2px solid black'>"."FECHA DE INSTALACION"."</th>
                            </tr>
                        </thead>
                        <tbody>"
                            .$tr.
/*$html.= */            "</tbody>
                    </table>
                </body>
            </html>
        ";

//Se imprime el html.
    echo $html;