<?php
$tipo = $_POST['tip'];
$clase = $_POST['clase'];
session_start();
switch ($tipo) {
    case 'IngDocArc':
        include_once'../clases/class.archivo.php';
        $Documento = new Documento();

        $claDoc=$_POST['cladocumento'];
        settype($claDoc, integer);

        $tipDoc=$_POST['tipdocumento'];
        settype($tipDoc, integer);

        $codDoc=$_POST['ingCodDoc'];
        settype($codDoc, number);

        $codSeg=$_POST['codSeg'];
        settype($codSeg, string);

        $fecDoc=$_POST['fechaDoc'];
        settype($fecDoc, string);

        $ben=$_POST['beneficiario'];
        settype($ben, string);

        $ban=$_POST['banco'];
        settype($ban, integer);

        $emp=$_POST['empresa'];
        settype($emp, integer);

        $fRep=$_POST['fechaRep'];
        settype($fRep, string);

        $asu= $_POST['asunto'];
        settype($asu, string);

        $per= $_POST['periodo'];
        settype($per, number);

        $codFac= $_POST['codFac'];
        settype($codFac, string);

        $cuenta= $_POST['cuenta'];
        settype($cuenta, string);

        $aproba= $_POST['aprobacion'];
        settype($aproba, string);

        $arc=$_FILES['archivo_fls'];
        settype($arc, string);

        $usr= $_SESSION['codigo'];
        settype($usr, string);


        /*CODIGO PARA SUBIR ARCHIVO*/
        //$nombreDirectorio = "../pdf/". $cod;
        if ($claDoc==2) $dir = "INT";
        else $dir = "EXT";


        if ($tipDoc==13) $dir2 = "CHEQUES";
        elseif ($tipDoc==14 || $tipDoc==22) $dir2 = "FACTURAS";
        elseif ($tipDoc==15 || $tipDoc==23) $dir2 = "COMUNICACIONES";
        elseif ($tipDoc==16) $dir2 = "ED";
        elseif ($tipDoc==17) $dir2 = "CONTRATOS";
        elseif ($tipDoc==18) $dir2 = "NC";
        elseif ($tipDoc==19) $dir2 = "ND";
        elseif ($tipDoc==20) $dir2 = "PAGOS";
        elseif ($tipDoc==21) $dir2 = "DEPOSITOS";
        $nombreDirectorio = "../pdf/CONT/". $dir . "/" . $dir2 ."/";
        if (!file_exists($nombreDirectorio))
        {
            //$oldmask = umask(0);
            //exec("sudo mkdir pdf/$nombreDirectorio 0777 true");
            mkdir($nombreDirectorio, 0777, true);
            //umask($oldmask);
        }

        if (is_uploaded_file($_FILES['archivo_fls']['tmp_name']))
        {
            $pro = strtoupper($pro);

            $nombreFichero = $_FILES['archivo_fls']['name'];


            switch ($tipDoc) {
                case '13':
                    $nombreFichero = $codDoc . "(CHEQUE)" . ".pdf";
                    break;
                case '14':
                    $nombreFichero = $codDoc . "(FACTURA)" . ".pdf";
                    break;
                case '15':
                    $nombreFichero = $codDoc . "(COMUNICACION)" . ".pdf";
                    break;
                case '16':
                    $nombreFichero = $codDoc . "(ENT_DIARIO)" . ".pdf";
                    break;
                case '17':
                    $nombreFichero = $codDoc . "(CONTRATO)" . ".pdf";
                    break;
                case '18':
                    $nombreFichero = $codDoc . "(N_CREDITO)" . ".pdf";
                    break;
                case '19':
                    $nombreFichero = $codDoc . "(N_DEBITO)" . ".pdf";
                    break;
                case '20':
                    $nombreFichero = $codDoc . "(PAGOS)" . ".pdf";
                    break;
                case '21':
                    $nombreFichero = $codDoc . "(DEPOSITO)" . ".pdf";
                    break;
                case '22':
                    $nombreFichero = $codDoc . "(FACTURA)" . ".pdf";
                    break;
                case '23':
                    $nombreFichero = $codDoc . "(COMUNICACION)" . ".pdf";
                    break;
            }
            move_uploaded_file($_FILES['archivo_fls']['tmp_name'],($nombreDirectorio.$nombreFichero));
        }
        $arc = $nombreDirectorio.$nombreFichero;
        $result = $Documento->setDocCont($claDoc,$tipDoc,$codDoc,$codSeg,$fecDoc,$ben,$ban,$emp,$fRep,$asu,$per,$codFac,$cuenta,$aproba,$arc, $usr);
       // $result = $Documento->setDoc($cod,$codArc,$dep,$doc,$pro,$fDoc,$usr,$arc,$obs);

        if($result){
            echo 'true';
            //echo  $miArray=array("error"=>$Documento->getMesrror(), "cod"=>$Documento->getCoderror(),"res"=>"true");
        }else{
            //$miArray=array("error"=>$Documento->getMesrror(), "cod"=>$Documento->getCoderror(),"res"=>"false");
            echo $result;
        }
        break;
    case 'selCla':
        include_once '../../clases/class.areas.php';
        $Clase = new Area();
        $datos = $Clase->getClase();
        $i=0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $con[$i]=$row;
            $i++;
        }
        echo json_encode($con);
        break;
    case 'selDoc':
        include_once '../clases/class.archivo.php';
        $Documento = new Documento();
        $datos = $Documento->getDocumentoCont($clase);
        $i=0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $con[$i]=$row;
            $i++;
        }
        echo json_encode($con);
        break;
    case 'selBan':
        include_once '../clases/class.archivo.php';
        $Documento = new Documento();
        $datos = $Documento->getBancos();
        $i=0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $con[$i]=$row;
            $i++;
        }
        echo json_encode($con);
        break;
    case 'selEmp':
        include_once '../clases/class.archivo.php';
        $Documento = new Documento();
        $datos = $Documento->getEmpresas();
        $i=0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $con[$i]=$row;
            $i++;
        }
        echo json_encode($con);
        break;

}

?>

