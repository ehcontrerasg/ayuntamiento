<?php $inmueble =  $_GET['inmueble']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de lecturas</title>

    <link rel="stylesheet" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/historicoLecturas.css?<?= time();?>">  
</head>
<body>
    <div class="container-fluid">
        <p id="pTitle">Histórico de lecturas del inmueble <?= $inmueble ?> </p>
        <table class="display" id="tblHistoricoLecturas" style="width: 100%;">
            <thead>
                <th>Número</th>
                <th>Periodo</th>
                <th>Lectura</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Operario</th>
            </thead>
        </table>
    </div>
</body>
<script src="../../js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script>

    //Conseguir histórico de factura.
    const historicoFactura = ()=>{
        let options = {url:"./../datos/datos.histlect.php?inmueble= <?= $inmueble ?>", type:"GET", res: "json"};
        $.ajax(options)
        .done((res)=>loadDatatable(res))
        .fail((error)=>console.error(error));
    };

    const loadDatatable = (json)=>{
        let array = JSON.parse(json);
        let options = {data:array, columns:[
            {data:"NUMERO"},
            {data:"PERIODO"},
            {data:"LECTURA_ACTUAL"},
            {data:"DESCRIPCION"},
            {data:"FECHA"},
            {data:"NOMBRE"}
        ],
        "bLengthChange": false,
        language: { url: "../../js/DataTables-1.10.15/SpanishDatatable.js" }};

        $("#tblHistoricoLecturas").DataTable(options);
    };

    $(document).ready(historicoFactura);
</script>
</html>