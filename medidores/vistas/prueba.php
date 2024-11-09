<?php
/**
 * Created by PhpStorm.
 * User: Edwin Contreras
 * Date: 21/03/2018
 * Time: 10:17
 */
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prueba</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
<h1 style="text-align: center;">Vista de Prueba</h1>
<main class="container-fluid">
    <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <form action="#" id="mainForm" class="row">
                <div style="display: flex; align-items: center;">
                    <div class="col-xs-10">
                        <div class="form-group">
                            <label for="codigo" class="form-label">Codigo:</label>
                            <input id="codigo" placeholder="codigo" name="codigo" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button id="query" type="button" class="btn btn-primary">Consultar</button>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="descripcion" class="form-label">Codigo:</label>
                        <textarea id="descripcion" name="descripcion" placeholder="descripcion" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script src="../js/prueba.js"></script>
</body>
</html>
