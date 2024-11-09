<?
  $datos_solicitud = [
                        "ID_SOLICITUD"      => '120',
                        "SOLICITADOR"       => 'Jean Carlos Holguín Berihuete',
                        "FECHA_SOLICITUD"   => '14/01/2020',
                        "FECHA_COMPROMISO"  => '14/02/2020',
                        "DESCRIPCION"       => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum',
                        "PANTALLA"          => 'Pantalla1/Pantallla2'
                     ];
?>

<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
    <style>
        section{
            background-color: #fafafa;
            padding: 30px;
        }

        table{
            margin: 0px auto;
            background-color: white;
            width: 40%;
            box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        }

        table thead td {
            padding: 12px;
            background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
            color: white;
            font-size: 20px;
            text-align: center;
        }

        table tbody td{
            color: #0e0d0e;
            font-size: 19px;
            padding: 21px;
        }
    </style>
</head>
    <body>
        <section>
            <table style="" class="table-striped">
                <thead>
                    <tr>
                        <td colspan="2">Datos de la solicitud</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <strong>Número de solicitud:</strong></td>
                        <td><?= $datos_solicitud["ID_SOLICITUD"]?></td>
                    </tr>
                    <tr>
                        <td><strong>Solicitador:</strong></td>
                        <td><?= $datos_solicitud["SOLICITADOR"]?></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha de solicitud:</strong></td>
                        <td><?= $datos_solicitud["FECHA_SOLICITUD"]?></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha de compromiso:</strong></td>
                        <td><?= $datos_solicitud["FECHA_COMPROMISO"]?></td>
                    </tr>
                    <tr>
                        <td><strong>Pantalla:</strong></td>
                        <td><?= $datos_solicitud["PANTALLA"]?></td>
                    </tr>
                    <tr>
                        <td><strong>Descripción:</strong></td>
                        <td><?= $datos_solicitud["DESCRIPCION"]?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </body>
</html>