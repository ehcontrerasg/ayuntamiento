<?php
include_once "class.conexion.php";


class Cliente extends ConexionClass
{


    private $mesrror;
    private $coderror;


    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getGruposCli (){

        $sql="SELECT
                G.COD_GRUPO CODIGO,
                G.DESC_GRUPO DESCRIPCION
              FROM
                SGC_TP_GRUPOS G
            ";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
    public function getTiposCli (){

        $sql="SELECT
                G.ID_TIPO_CLIENTE CODIGO,
                G.DESC_TIPO_CLIENTE DESCRIPCION
              FROM
                SGC_TP_TIPO_CLIENTE G
            ";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
    public function getDatCliByCed($codCli){
        // $codCli = addslashes($codCli);
        $sql     = "SELECT C.DOCUMENTO, C.NOMBRE_CLI, C.TELEFONO, C.EMAIL, C.CODIGO_CLI, C.TIPO_DOC
        FROM SGC_TT_CLIENTES C
        WHERE C.DOCUMENTO = '$codCli'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }


    }
    public function getDatosCliente($codigo_inmueble){

        $sql = "SELECT INM.CODIGO_INM INMUEBLE, CON.CODIGO_CLI CODIGO_CLIENTE,NVL(CON.EMAIL,CLI.EMAIL) EMAIL,CLI.EMAIL EMAIL2,CLI.NOMBRE_CLI NOMBRE_CLIENTE,PRO.SIGLA_PROYECTO,
                REPLACE(NVL(INM.TELEFONO, CLI.TELEFONO),'-','') TELEFONO, REPLACE(INM.CELULAR,'-','') CELULAR, CLI.DOCUMENTO, inm.ID_PROYECTO
                FROM SGC_TT_CLIENTES CLI, SGC_TT_CONTRATOS CON,SGC_TT_INMUEBLES INM,
                      SGC_TP_PROYECTOS PRO
                WHERE CON.CODIGO_CLI  = CLI.CODIGO_CLI
                AND   INM.CODIGO_INM  = CON.CODIGO_INM
                AND   INM.ID_PROYECTO = PRO.ID_PROYECTO
                AND   CON.CODIGO_INM  = '$codigo_inmueble'
                AND   CON.FECHA_FIN IS NULL";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);

        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDatosClientePorIdContrato($idContrato){
        $sql = "SELECT INM.CODIGO_INM INMUEBLE,
       CON.CODIGO_CLI CODIGO_CLIENTE,
       NVL(CON.EMAIL, CLI.EMAIL) EMAIL,
       CLI.EMAIL EMAIL2,
       CLI.NOMBRE_CLI NOMBRE_CLIENTE,
       PRO.SIGLA_PROYECTO,
       REPLACE(NVL(INM.TELEFONO, CLI.TELEFONO), '-', '') TELEFONO,
       REPLACE(INM.CELULAR, '-', '') CELULAR,
       CLI.DOCUMENTO,
       inm.ID_PROYECTO,
       INM.DIRECCION,
       CON.ALIAS,
       CLI.TIPO_DOC TIPO_DOCUMENTO,
       CLI.CONTRIBUYENTE_DGI CONTRIBUYENTE_DGII,
       NVL(CON.GRUPO,CLI.COD_GRUPO) GRUPO,
       CLI.CORRESPONDENCIA,
       CLI.DIR_CORRESPONDENCIA
       FROM SGC_TT_CLIENTES  CLI,
             SGC_TT_CONTRATOS CON,
             SGC_TT_INMUEBLES INM,
             SGC_TP_PROYECTOS PRO
       WHERE CON.CODIGO_CLI = CLI.CODIGO_CLI
         AND INM.CODIGO_INM = CON.CODIGO_INM
         AND INM.ID_PROYECTO = PRO.ID_PROYECTO
         AND CON.Id_Contrato = '$idContrato'
         AND CON.FECHA_FIN IS NULL";

        $statement = oci_parse($this->_db, $sql);
        oci_close($this->_db);
        return (oci_execute($statement)) ? $statement : false;
    }

    public function getDatosClientePorDocumento($documento, $codigoInmueble){
        if($documento == '9999999'){
            $alias = 'CLI.NOMBRE_CLI ALIAS,';
        }
        else{
            $alias = 'CON.ALIAS,';
        }
        $sql = "SELECT *
        FROM (SELECT INM.CODIGO_INM INMUEBLE,
                    CON.CODIGO_CLI CODIGO_CLIENTE,
                    NVL(CON.EMAIL, CLI.EMAIL) EMAIL,
                    CLI.EMAIL EMAIL2,
                    CLI.NOMBRE_CLI NOMBRE_CLIENTE,
                    PRO.SIGLA_PROYECTO,
                    REPLACE(NVL(INM.TELEFONO, CLI.TELEFONO), '-', '') TELEFONO,
                    REPLACE(INM.CELULAR, '-', '') CELULAR,
                    CLI.DOCUMENTO,
                    inm.ID_PROYECTO,
                    (SELECT INM.DIRECCION FROM SGC_TT_INMUEBLES INM WHERE INM.CODIGO_INM = $codigoInmueble )  DIRECCION,
                    --CLI.NOMBRE_CLI ALIAS,
                     $alias
                    CLI.TIPO_DOC TIPO_DOCUMENTO,
                    CLI.CONTRIBUYENTE_DGI CONTRIBUYENTE_DGII,
                    CON.GRUPO,
                    CLI.CORRESPONDENCIA,
                    CLI.DIR_CORRESPONDENCIA
               FROM SGC_TT_CLIENTES  CLI,
                    SGC_TT_CONTRATOS CON,
                    SGC_TT_INMUEBLES INM,
                    SGC_TP_PROYECTOS PRO
              WHERE CON.CODIGO_CLI = CLI.CODIGO_CLI
                AND INM.CODIGO_INM = CON.CODIGO_INM
                AND INM.ID_PROYECTO = PRO.ID_PROYECTO
                AND CLI.DOCUMENTO = '$documento'
              ORDER BY CLI.FECHA_CREACION DESC)
      WHERE ROWNUM = 1
     ";

        $statement = oci_parse($this->_db, $sql);
        oci_close($this->_db);
        return (oci_execute($statement)) ? $statement : false;
    }

}