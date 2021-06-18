<?php
require_once("procesos/libreriaSII/xmlseclibs/XmlseclibsAdapter.php");
require_once("procesos/libreriaSII/SII.php");
require_once("Config/Conexion.php");


date_default_timezone_set('America/Santiago');

#error_reporting(E_ALL | E_STRICT);
#ini_set('display_errors', 0);
$conn = connectDB();

$json = file_get_contents("php://input");

//$json = '{"RutCompania":"76301941-1","sis_contribuyente_id":19,"clave":"evoofoods2018","certificado":"lautaroarturo2018.pfx","razonContribuyente":"EVOOFOODS AGROINDUSTRIAL LIMITADA","direccionContribuyente":"CARRETERA GRAL SAN MARTIN 9340 G U6","emailContribuyente":"contabilidad@evoofoods.cl","nombreRLContribuyente":"LAUTARO ARTURO LEIVA DIMTER","RutConsultante":"9748881-9","tipoDoc":"33","foliodoc":"3980","rutFactoring":"99562370-6","nombreContactoFactoring":"CONTEMPORA FACTORING SA","direccionFactoring":"AV EL BOSQUE NORTE 0177 OF 70 PISO 7","mailFactoring":"LASMUSSEN@CONTEMPORA.COM","monto":"4034100","fechaVencimiento":"2019-11-08","FechaEmision":"09-09-2019","rutReceptor":"76073937-5","razonReceptor":"BIDFOOD  S.A","Usuario":"4d22144d-d9c9-4857-b5b8-4d46f67a5693","function":"Cesion","trackid":"0"}';
$jsoncsharp = json_decode($json);


#Variables globales de uso
$model["RutConsultante"] = $jsoncsharp->RutConsultante;
$model["RutEmisor"] = $jsoncsharp->RutCompania;

if ($jsoncsharp->trackid != "")
    $model["trackid"] = $jsoncsharp->trackid;

$model["contribuyente"] = $jsoncsharp->sis_contribuyente_id;
$model["clave"] = $jsoncsharp->clave;
$model["certificado"] = $jsoncsharp->certificado;


switch ($jsoncsharp->function) {
    case "VerificarCesion":

        $model["tipodoc"] = "33";
        $model["foliodoc"] = $jsoncsharp->foliodoc;
        $respuesta = QueryEstCesion($model);

        if ($respuesta != "") {
            $sql = "update sisgenchile_sisgenfe.sis_dte_cesion set estado_sii_glosa='" . $respuesta . "' where trackid='" . $model["trackid"] . "'";
            $conn->query($sql);
            echo $respuesta;
        } else
            echo $respuesta;
        break;

    case "VerificarDte":

        $respuesta = explode("|", QueryEstUp($model));
        if ($respuesta[0] == 0) {
            $sql = "update sis_bitacora set estado_sii='" . $respuesta[3] . "' where dte_track_id='" . $$model["trackid"] . "'";
            $conn->query($sql);
            echo $respuesta[3];
        } else
            echo  $respuesta[1];
        break;

    case "QueryWebService":

        $rangos = explode(",", $jsoncsharp->folios);

        $respuesta = "<table class='table'>";


        foreach ($rangos as $folio) {
            if (strpos($folio, "-") > 0) {
                $dteRango = explode("-", $folio);

                for ($i = $dteRango[0]; $i <= $dteRango[1]; $i++) {
                    $sql = "select dte_receptor_rut,DATE_FORMAT(dte_fecha_emision,'%d/%m/%Y'),
                    dte_monto_total from sis_bitacora where sis_contribuyente_id=" . $jsoncsharp->sis_contribuyente_id . " and "
                        . "dte_tipo=" . $jsoncsharp->tipoDte . " and dte_folio=$i";

                    $query = $conn->query($sql);
                    $data = $query->fetch_assoc();

                    $model["RutCompania"]     = $jsoncsharp->RutCompania;
                    $model["Rutreceptor"]     = $data["dte_receptor_rut"];

                    $model["TipoDte"] = $jsoncsharp->tipoDte;
                    $model["FolioDte"] = $i;
                    $model["FechaEmisionDte"] = date("dmY", $data["dte_fecha_emision"]);
                    $model["MontoDte"] = $data["dte_monto_total"];

                    $respuesta .= QueryEstDte($model);
                }
            } else {
                $sql = "select dte_receptor_rut,DATE_FORMAT(dte_fecha_emision,'%d/%m/%Y') as dte_fecha_emision,
                 dte_monto_total from sisgenchile_sisgenfe.sis_bitacora where sis_contribuyente_id="
                    . $jsoncsharp->sis_contribuyente_id . " and dte_tipo=" . $jsoncsharp->tipoDte . " and dte_folio=$folio";

                $query = $conn->query($sql);
                $data = $query->fetch_assoc();

                $model["RutCompania"]     = $jsoncsharp->RutCompania;
                $model["Rutreceptor"]     = $data["dte_receptor_rut"];

                $model["TipoDte"] = $jsoncsharp->tipoDte;
                $model["FolioDte"] = $folio;
                $model["FechaEmisionDte"] = $data["dte_fecha_emision"];

                $model["MontoDte"] = $data["dte_monto_total"];


                $respuesta .= QueryEstDte($model);
            }
        }
        echo $respuesta . "</table>";
        break;

    case "ReenvioCorreo":
        $respuesta = wsDTECorreo($model);
        echo $respuesta;
        break;



    case "Cesion":

        //cargar los parametros del json antes de ejecutar funcion        

        $pMonto = str_replace(array("$", ",", ".-"), "", $jsoncsharp->monto);
        $pFecha = $jsoncsharp->fechaVencimiento;;
        $pTipo = $jsoncsharp->tipoDoc;
        $pFolio = $jsoncsharp->foliodoc;
        $pRut = $jsoncsharp->rutFactoring;
        $pNombre = $jsoncsharp->nombreContactoFactoring;
        $pDireccion = $jsoncsharp->direccionFactoring;
        $pMail = $jsoncsharp->mailFactoring;
        $pFechaEmi = $jsoncsharp->FechaEmision;
        $pRutReceptor = $jsoncsharp->rutReceptor;
        $pReceptor = $jsoncsharp->razonReceptor;


        if (procAec($conn, $jsoncsharp, $pTipo, $pFolio, $pRut, $pNombre, $pDireccion, $pMail, $pMonto, $pFecha, $pFechaEmi, $pRutReceptor, $pReceptor) === true) {
            $respuesta = '[{"ERROR":"0","MENSAJE":"' . $msgError . '"}]';
        } else {
            $respuesta = '[{"ERROR":"1","MENSAJE":"' . $msgError . '"}]';
        }

        return $respuesta;

        break;


    default:
        echo "Sin ninguna accion";
        break;
}

function procAec($conn, $jsoncsharp, $pTipo, $pFolio, $pRut, $pNombre, $pDireccion, $pMail, $pMonto, $pFecha, $pFechaEmi, $pRutReceptor, $pReceptor)
{
    $pFono = "";
    $rut_emisor = $jsoncsharp->RutCompania;

    $rut_cesionario =  str_replace(".", "", $pRut);
    #$FchResol = $_SESSION["FECRESOL"];
    #$NumResol = $_SESSION["NUMRESOL"];
    $timezone = new DateTimeZone('America/Santiago');
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $idDoc = "R" . substr($rut_emisor, 0, -2) . "T" . $pTipo . "F" . $pFolio . "_AEC";
    $idDocCedido = "T" . $pTipo . "F" . $pFolio . "_Cedido";
    $idDocCesion = "T" . $pTipo . "F" . $pFolio . "_Cesion";

    /*PROCESO GENERADOR DEL DOCUMENTO*/
    $xml = fopen("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDoc . ".xml", "w+");

    $caratulaXML = "<Caratula version=\"1.0\">\n"
        . "<RutCedente>" . $rut_emisor . "</RutCedente>\n"
        . "<RutCesionario>" . $rut_cesionario . "</RutCesionario>\n"
        . "<NmbContacto>" . $pNombre . "</NmbContacto>\n"
        . "<FonoContacto>" . $pFono . "</FonoContacto>\n"
        . "<MailContacto>" . $pMail . "</MailContacto>\n"
        . "<TmstFirmaEnvio>" . $TmstFirma . "</TmstFirmaEnvio>\n"
        . "</Caratula>\n"
        . "<Cesiones>";
    //Generamos Nodos
    $retVal = procDteCedido($conn, $jsoncsharp, $idDocCedido, substr($rut_emisor, 0, -2), $pTipo, $pFolio, $jsoncsharp->certificado, $jsoncsharp->clave);

    if ($retVal) {
        $retVal = procCesion($jsoncsharp, $idDocCesion, substr($rut_emisor, 0, -2), $pTipo, $pFolio, $rut_cesionario, $pNombre, $pDireccion, $pMail, $pMonto, $pFecha, $pFechaEmi, $pRutReceptor, $pReceptor, $jsoncsharp->certificado, $jsoncsharp->clave);
    } else {
        return false;
    }
    //Leemos Nodos
    $dteCedido = fopen("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDocCedido . ".xml", "r");

    $dteCediodXml = fread($dteCedido, filesize("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDocCedido . ".xml"));

    $caratulaXML .= str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n", "", $dteCediodXml);

    $dteCesion = fopen("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDocCesion . ".xml", "r");

    $dteCesionXml = fread($dteCesion, filesize("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDocCesion . ".xml"));

    $caratulaXML .= str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n", "", $dteCesionXml);

    $caratulaXML .= "</Cesiones>\n";

    $DocAec = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><AEC version=\"1.0\" xmlns=\"http://www.sii.cl/SiiDte\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sii.cl/SiiDte AEC_v10.xsd\">\n"
        . "<DocumentoAEC ID=\"" . $idDoc . "\">\n" . $caratulaXML . "</DocumentoAEC>\n</AEC>";

    fwrite($xml, $DocAec);

    fclose($xml);


    unlink("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDocCedido . ".xml");
    unlink("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDocCesion . ".xml");

    $DTE = new DOMDocument();
    $DTE->formatOutput = TRUE;
    $DTE->preserveWhiteSpace = TRUE;
    $DTE->encoding = "ISO-8859-1";
    $DTE->load("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDoc . ".xml");

    $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

    $pfx = file_get_contents("../www/certificados/" . substr($rut_emisor, 0, -2) . "/" . $jsoncsharp->certificado);

    openssl_pkcs12_read($pfx, $key, $jsoncsharp->clave);

    $xmlTool->setPrivateKey($key["pkey"]);
    $xmlTool->setpublickey($key["cert"]);
    $xmlTool->sign($DTE, "AEC");

    $caratulaXMLFinal = $DTE->saveXml();

    $xmlFinal = fopen("../www/documentos/cesiones/" . substr($rut_emisor, 0, -2) . "/" . $idDoc . ".xml", "w");

    fwrite($xmlFinal, $caratulaXMLFinal);
    fclose($xmlFinal);

    if ($retVal) {

        //Proceso de Envío del archivo
        $resultado = procesaEnvio($jsoncsharp, $idDoc, $pTipo, $pFolio);
        $res = explode("|", $resultado);

        if (intval($res[0]) > 0 and intval($res[1]) == 0) {
            $sql = "select max(sis_dte_ces_id)+1 as id from sis_dte_cesion where sis_contribuyente_id=" . $jsoncsharp->sis_contribuyente_id;
            $query = $conn->query($sql);
            $data = $query->fetch_assoc();
            $pSesionId = $data["id"];

            if (intval($pSesionId) <= 0) {
                $pSesionId = 1;
            }
            $sql = "insert into  sis_dte_cesion values(" . $jsoncsharp->sis_contribuyente_id . ",$pTipo,$pFolio,$pSesionId,'$rut_cesionario','$pNombre','$pDireccion','$pMail',$pMonto,'$pFecha',current_timestamp,'" . $jsoncsharp->Usuario . "','" . $res[0] . "','" . $res[1] . "','')";

            $query = $conn->query($sql);
            $num = $conn->affected_rows;

            if ($num > 0) {
                $msgError = "Se realizo el envio del archivo de cesión, el SII enviará un correo electronico indicando el estado de la operación";
                return true;
            } else {
                $msgError = "No se pudo grabar el resultado de la cesion. Dte Core";
                return false;
            }
        } else {
            $msgError = "ERROR: Problema de comunicaci&oacute;n con los servidores del S.I.I.<br>Intente nuevamente y si el problema persiste comuníquese con soporte.";
            return false;
        }
    }
    return $retVal;
}

function procDteCedido($conn, $jsoncsharp, $idDocCedido, $pRutEmpresa, $pTipo, $pFolio, $certificado, $clave)
{
    global $conn, $msgError;
    $timezone = new DateTimeZone('America/Santiago');
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $sql = "SELECT dte_archivo_xml FROM sisgenchile_sisgenfe.sis_bitacora "
        . "where sis_contribuyente_id=" . $jsoncsharp->sis_contribuyente_id . " and dte_tipo=$pTipo and dte_folio=$pFolio  "
        . "and (dte_estado_sii='DTE ACEPTADO' or dte_estado_sii='DTE Aceptado con Reparos Leves' or dte_estado_sii='DTE Aceptado con Reparos')";

    $query = $conn->query($sql);
    $data = $query->fetch_assoc();
    $arcOrigen = $data["dte_archivo_xml"];
    if (empty($arcOrigen)) {

        $FileDteXml = "../www/procesos/xml_emitidos/$pRutEmpresa/null.xml";
    } else {
        $FileDteXml = "../www/procesos/xml_emitidos/$pRutEmpresa/" . $arcOrigen;
    }


    if (!file_exists($FileDteXml)) {
        $msgError = "No se pudo encontrar el documento de origen intente publicarlo nuevamente o comuniquese con soporte<br>$arcOrigen";
        return false;
    }

    if (!$dte = fopen($FileDteXml, "r")) {
        $msgError = "No se pudo leer el documento de origen $arcOrigen";
        return false;
    } else {
        $dteXml = fread($dte, filesize($FileDteXml));

        if (!$xmlCedido = fopen("../www/documentos/cesiones/" . $pRutEmpresa . "/" . $idDocCedido . ".xml", "w+")) {
            $msgError = "No se pudo crear el xml del documento cedido";
            return false;
        }
        $DteCedido = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<DTECedido version=\"1.0\">\n"
            . "<DocumentoDTECedido ID=\"" . $idDocCedido . "\">" . str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n", "", $dteXml);

        $DteCedido .= "<TmstFirma>" . $TmstFirma . "</TmstFirma>\n</DocumentoDTECedido>\n</DTECedido>";
        fwrite($xmlCedido, $DteCedido);
        fclose($xmlCedido);

        $DTECEDIDO = new DOMDocument();
        $DTECEDIDO->formatOutput = FALSE;
        $DTECEDIDO->preserveWhiteSpace = TRUE;
        $DTECEDIDO->encoding = "ISO-8859-1";
        $DTECEDIDO->load("../www/documentos/cesiones/" . $pRutEmpresa . "/" . $idDocCedido . ".xml");
        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

        $pfx = file_get_contents("../www/certificados/" . $pRutEmpresa . "/" . $certificado);

        openssl_pkcs12_read($pfx, $key, $clave);

        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->sign($DTECEDIDO, "DTECedido");
        $XMLFinal = $DTECEDIDO->saveXml();

        $xmlCedidoFinal = fopen("../www/documentos/cesiones/" . $pRutEmpresa . "/" . $idDocCedido . ".xml", "w");

        if (!fwrite($xmlCedidoFinal, $XMLFinal)) {
            $msgError = "No se pudo grabar el xml del DTE Cedido (Line 326 DteCore Service)";
            return false;
        }
        fclose($xmlCedidoFinal);
        return true;
    }
}

function procCesion($jsoncsharp, $idDocCesion, $pRutEmpresa, $pTipo, $pFolio, $pRut, $pNombre, $pDireccion, $pMail, $pMonto, $pFecha, $pFechaEmi, $pRutReceptor, $pReceptor, $certificado, $clave)
{
    $timezone = new DateTimeZone('America/Santiago');
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $arcCesion = fopen("../www/documentos/cesiones/" . $pRutEmpresa . "/" . $idDocCesion . ".xml", "w+");

    $XmlCesion = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<Cesion version=\"1.0\">\n"
        . "<DocumentoCesion ID=\"" . $idDocCesion . "\">\n"
        . "<SeqCesion>1</SeqCesion>\n"
        . "<IdDTE>\n"
        . "<TipoDTE>" . $pTipo . "</TipoDTE>\n"
        . "<RUTEmisor>" . $jsoncsharp->RutCompania . "</RUTEmisor>\n"
        . "<RUTReceptor>" . $pRutReceptor . "</RUTReceptor>\n"
        . "<Folio>" . $pFolio . "</Folio>\n"
        . "<FchEmis>" . date("Y-m-d", strtotime($pFechaEmi)) . "</FchEmis>\n"
        . "<MntTotal>" . $pMonto . "</MntTotal>\n"
        . "</IdDTE>\n"
        . "<Cedente>\n"
        . "<RUT>" . $jsoncsharp->RutCompania . "</RUT>\n"
        . "<RazonSocial>" . $jsoncsharp->razonContribuyente . "</RazonSocial>\n"
        . "<Direccion>" . $jsoncsharp->direccionContribuyente . "</Direccion>\n"
        . "<eMail>" . $jsoncsharp->emailContribuyente . "</eMail>\n"
        . "<RUTAutorizado>\n"
        . "<RUT>" . $jsoncsharp->RutConsultante . "</RUT>\n"
        . "<Nombre>" . $jsoncsharp->nombreRLContribuyente . "</Nombre>\n"
        . "</RUTAutorizado>\n";

    $XmlCesion .= "<DeclaracionJurada>Se declara bajo juramento que " . $jsoncsharp->nombreRLContribuyente . ", RUT " . $jsoncsharp->RutConsultante
        . " ha puesto a disposicion del cesionario " . $pNombre . ", RUT " . $pRut . ", el o los documentos donde"
        . " constan los recibos de las mercaderias entregadas o sevicios prestados, entregados por parte del deudor de la factura "
        . $pReceptor . ", RUT " . $pRutReceptor . ", de acuerdo a lo establecido en la Ley N 19.983</DeclaracionJurada>\n"
        . "</Cedente>\n"
        . "<Cesionario>\n"
        . "<RUT>" . $pRut . "</RUT>\n"
        . "<RazonSocial>" . $pNombre . "</RazonSocial>\n"
        . "<Direccion>" . $pDireccion . "</Direccion>\n"
        . "<eMail>" . $pMail . "</eMail>\n"
        . "</Cesionario>\n"
        . "<MontoCesion>" . $pMonto . "</MontoCesion>\n"
        . "<UltimoVencimiento>" . $pFecha . "</UltimoVencimiento>\n"
        . "<TmstCesion>" . $TmstFirma . "</TmstCesion>\n"
        . "</DocumentoCesion>\n"
        . "</Cesion>";

    fwrite($arcCesion, $XmlCesion);
    fclose($arcCesion);

    $DTECESION = new DOMDocument();
    $DTECESION->formatOutput = FALSE;
    $DTECESION->preserveWhiteSpace = TRUE;
    $DTECESION->encoding = "ISO-8859-1";
    $DTECESION->load("../www/documentos/cesiones/" . $pRutEmpresa . "/" . $idDocCesion . ".xml");
    $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

    $pfx = file_get_contents("../www/certificados/" . $pRutEmpresa . "/" . $certificado);
    openssl_pkcs12_read($pfx, $key, $clave);

    $xmlTool->setPrivateKey($key["pkey"]);
    $xmlTool->setpublickey($key["cert"]);
    $xmlTool->sign($DTECESION, "Cesion");
    $XMLFinal = $DTECESION->saveXml();

    $xmlCesionFinal = fopen("../www/documentos/cesiones/" . $pRutEmpresa . "/" . $idDocCesion . ".xml", "w");
    fwrite($xmlCesionFinal, $XMLFinal);
    fclose($xmlCesionFinal);
    return true;
}

function procesaEnvio($jsoncsharp, $idDoc, $pTipo, $pFolio)
{
    global $msgError;

    $model["RutEnvia"]      = $jsoncsharp->RutConsultante;
    $model["RutEmisor"]     = $jsoncsharp->RutCompania;
    $model["SetDTE_ID"]     = $idDoc;
    $model["pemailNotif"]   = $jsoncsharp->emailContribuyente;
    $model["dteTipo"]       = $pTipo;
    $model["dteFolio"]      = $pFolio;
    $model["clave"] = $jsoncsharp->clave;
    $model["certificado"] = $jsoncsharp->certificado;
    $resultado = enviarAEC($model);
    return $resultado;
}
