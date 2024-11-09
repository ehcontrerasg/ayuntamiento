<!DOCTYPE html>
<head>
    <htmllang="es">
    <meta  charset="UTF-8" />
    <title>Entrada de documentos de Archivo</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/ingresa_doc_arc.js?<?php echo time();?>"></script>
    <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap/css/bootstrap.css " rel="stylesheet" type="text/css" />
    <style type="text/css">
    	#msgExist {
    		padding: 1%;
    		font-weight: bold;
			display: none;
    	}
    	#msgExist div{
    		padding: 1%;
    		border-radius: 5px 5px 5px 5px;
			-moz-border-radius: 5px 5px 5px 5px;
			-webkit-border-radius: 5px 5px 5px 5px;
			border: 0px solid #000000;
    	}
    </style>
</head>
<body id="c">
<header class="cabeceraTit fondArc">
    Entrada de documentos de Archivo
</header>

<section class="contenido" id="contenidohtml">
    <article>
        <form id="ingDocArcForm" name="ingDocArcForm" enctype="multipart/form-data" class="form-horizontal">

            <div class="subCabecera fondArc"> Datos del imueble</div>
				<br>
				<div class="form-group">
					  <label for="ingCodDoc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Código de sistema</label>
					  <div class="col-xs-9 col-sm-6 col-md-2 col-lg-2"> 
					  		<input class="form-control" type="number" id="ingCodDoc" name="ingCodDoc" required>
					  </div>
				</div>
				
				<div class="form-group" id="msgExist">
					  <div class="col-md-offset-3 col-xs-9 col-sm-4 col-md-4 col-lg-4 bg-danger" > 
					  		<span class="text-danger">Este Codigo ya esta registrado en el sistema.</span>
					  </div>
				</div>

				<div class="form-group">
					  <label for="codArc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Código del archivo</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"> 
					  		<input class="form-control" type="text" id="codArc" name="codArc" required>
					  </div>
				</div>

				<div class="form-group">
					  <label for="departamento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Departamento</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"> 
							<select class="form-control form-control-lg" id="departamento" name="departamento" required>
							  <option>Departamentos</option>
							</select>
					  </div>
				</div>

				<div class="form-group">
					  <label for="documento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Documento</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"> 
							<select class="form-control form-control-lg" id="documento" name="documento" required>
							  <option>Documentos</option>
							</select>
					  </div>
				</div>

				<div class="form-group">
					  <label for="proyecto" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Proyecto</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"> 
							<select class="form-control form-control-lg" id="proyecto" name="proyecto" required>
							  <option>Proyecto</option>
							</select>
					  </div>
				</div>

				<div class="form-group row">
					  <label for="fechaDoc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Fecha del documento</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"">
					    <input class="form-control" type="date" value="<?php echo date("Y-m-d");?>" id="fechaDoc" name="fechaDoc" required>
					  </div>
				</div>

				<div class="form-group row">
					  <label for="archivo_fls" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Archivo</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"> 
					  		<input  type="file" name="archivo_fls" id="archivo_fls" class="form-control">
					  </div>
				</div>

				<div class="form-group">
					  <label for="observacion" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Obervación</label>
					  <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"> 
					  		<textarea class="form-control" id="obs" rows="3" id="observacion" name="observacion"></textarea>
					  </div>
				</div>

            <span class="datoForm col1">
                <button type="submit" tabindex="5" id="btnGuardar" class="botonFormulario botFormArc" >Guardar</button>
            </span>
        </form>
    </article>
</section>

<footer>
</footer>
<script type="text/javascript" src="../../js/sweetalert.min.js?1"></script>
</body>
</html>