<?php
session_start();

include '../../destruye_sesion.php';

$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
if($_GET['cod_inmueble']){
    $inmueble=$_GET['cod_inmueble'];
}
if($_POST['cod_inmueble']){
    $inmueble=$_POST['cod_inmueble'];
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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

        iframe {
            display: block;
            float: left;
            border-width: 0px;
        }


    </style>

</head>
<body>


<iframe id="ifdetfactpend" width="50%" height="500px"  src="vista.adminareas.php" ></iframe>
<iframe id="ifestcon" width="49%" height="500px"   src="vista.admincargos.php" ></iframe>

</body>
</html>

