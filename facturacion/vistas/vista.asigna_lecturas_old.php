<!DOCTYPE html>
<html lang="es">
<head xmlns="http://www.w3.org/1999/html">
    <meta  charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Asignación de Lecturas</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <!--script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script-->
    <link href="../../css/css.css " rel="stylesheet" type="text/css" />
    <!-- Bootstrap -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../../datatables/css/dataTables.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../datatables/js/dataTables.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/asigna_lecturas_old.js"></script>
    <link href="../../css/general_old.css?105" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <header class="cabeceraTit fondGeneral ">
        <label>Asignaci&oacute;n y Reasignaci&oacute;n de Lecturas</label>
    </header>
</div>
<section class="contenido">
    <article>
        <form onsubmit="return false" id="asiLecForm" class="form-horizontal" >
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="asiLecPro" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">Proyecto</label>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <select required name="proyecto" id="asiLecPro" class="form-control">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="asiLecPer" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">Periodo</label>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <select required name="periodo" id="asiLecPer" class="form-control"></select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="asiLecZon" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">Zona</label>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <select required name="zona" id="asiLecZon" class="form-control"></select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="asiLecFecPla" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">Fecha Planificaci&oacute;n</label>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <input type="date"  id="asiLecFecPla" class="form-control" required />
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <button type="submit" class="btn btn-success btn-lg" id="asiLecButBus">Buscar</button>
                    <button type="reset" class="btn btn-danger btn-lg" id="asiLecButCan">Cancelar</button>
                </div>
            </div>
        </form>
        <div>

        </div>

        <form onsubmit="return false" id="asiLecRutForm" class="form-horizontal" >

        </form>
    </article>
</section>

<footer>
</footer>

</body>
</html>