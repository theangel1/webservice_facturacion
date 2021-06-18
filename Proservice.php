<?php

/*
git add . 
git commit -m "mensaje"
git push origin master

Metal
Angelito1#

Extension php
extension=php_sqlsrv_7_ts_x86.dll
*/

ob_start();
date_default_timezone_set('America/Santiago');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once("SendXml.php");
require_once("procesos/lib/xmlseclibs/XmlseclibsAdapter.php");
require_once("procesos/lib/ObjectAndXML.php");
require_once("procesos/lib/SII.php");
require_once("procesos/lib/DTE/DTE.php");

//Recibiendo el JSON

$json = file_get_contents("php://input");
$jsoncsharp = json_decode($json);
//$jsoncsharp = json_decode("{\"Dte\":{\"Id\":1032,\"EmpresaId\":1,\"ClienteId\":21,\"TipoDocumento\":61,\"Folio\":9,\"FechaEmision\":\"2019-07-05T11:37:17.6930264\",\"IndicadorServicio\":0,\"FormaPago\":1,\"TpoMovGlobal\":\"D\",\"TpoValorGlobal\":\"%\",\"ValorDRGlobal\":5.0,\"MontoNeto\":2069251.0,\"MontoExento\":0.0,\"MontoIva\":393158.0,\"MontoTotal\":2462409.0,\"NombreXml\":\"T61_F9-2019JulFri113723.xml\",\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035},\"Cliente\":{\"Id\":21,\"Rut\":\"17558736-5\",\"RazonSocial\":\"Angeles Enterprises\",\"Direccion\":\"Vicuña Mackena 7801\",\"Giro\":\"SERVICIOS INFORMATICOS\",\"Comuna\":\"LA FLORIDA\",\"Ciudad\":\"SANTIAGO\",\"Telefono\":\"992861178\",\"Email\":\"angel.pinilla@outlook.es\",\"EmpresaId\":1,\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035}}},\"Detalle\":[{\"Id\":1049,\"DteId\":1032,\"IsExento\":false,\"Codigo\":\"3\",\"Descripcion\":\"Sofa Agatha Tela-Gris / Bariloche\",\"Cantidad\":3,\"UnidadMedida\":null,\"Precio\":141170.0,\"DescuentoPct\":10.0,\"DescuentoMonto\":42351.0,\"RecargoPct\":null,\"RecargoMonto\":null,\"TotalItem\":381159.0,\"Dte\":{\"Id\":1032,\"EmpresaId\":1,\"ClienteId\":21,\"TipoDocumento\":61,\"Folio\":9,\"FechaEmision\":\"2019-07-05T11:37:17.6930264\",\"IndicadorServicio\":0,\"FormaPago\":1,\"TpoMovGlobal\":\"D\",\"TpoValorGlobal\":\"%\",\"ValorDRGlobal\":5.0,\"MontoNeto\":2069251.0,\"MontoExento\":0.0,\"MontoIva\":393158.0,\"MontoTotal\":2462409.0,\"NombreXml\":\"T61_F9-2019JulFri113723.xml\",\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035},\"Cliente\":{\"Id\":21,\"Rut\":\"17558736-5\",\"RazonSocial\":\"Angeles Enterprises\",\"Direccion\":\"Vicuña Mackena 7801\",\"Giro\":\"SERVICIOS INFORMATICOS\",\"Comuna\":\"LA FLORIDA\",\"Ciudad\":\"SANTIAGO\",\"Telefono\":\"992861178\",\"Email\":\"angel.pinilla@outlook.es\",\"EmpresaId\":1,\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035}}}},{\"Id\":1050,\"DteId\":1032,\"IsExento\":false,\"Codigo\":null,\"Descripcion\":\"Guitarra Solar\",\"Cantidad\":3,\"UnidadMedida\":null,\"Precio\":599000.0,\"DescuentoPct\":null,\"DescuentoMonto\":null,\"RecargoPct\":null,\"RecargoMonto\":null,\"TotalItem\":1797000.0,\"Dte\":{\"Id\":1032,\"EmpresaId\":1,\"ClienteId\":21,\"TipoDocumento\":61,\"Folio\":9,\"FechaEmision\":\"2019-07-05T11:37:17.6930264\",\"IndicadorServicio\":0,\"FormaPago\":1,\"TpoMovGlobal\":\"D\",\"TpoValorGlobal\":\"%\",\"ValorDRGlobal\":5.0,\"MontoNeto\":2069251.0,\"MontoExento\":0.0,\"MontoIva\":393158.0,\"MontoTotal\":2462409.0,\"NombreXml\":\"T61_F9-2019JulFri113723.xml\",\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035},\"Cliente\":{\"Id\":21,\"Rut\":\"17558736-5\",\"RazonSocial\":\"Angeles Enterprises\",\"Direccion\":\"Vicuña Mackena 7801\",\"Giro\":\"SERVICIOS INFORMATICOS\",\"Comuna\":\"LA FLORIDA\",\"Ciudad\":\"SANTIAGO\",\"Telefono\":\"992861178\",\"Email\":\"angel.pinilla@outlook.es\",\"EmpresaId\":1,\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035}}}}],\"Referencia\":[{\"Id\":1012,\"DteId\":1032,\"TipoDocumento\":33,\"FolioReferencia\":3,\"FechaReferencia\":\"2019-07-03T00:00:00\",\"CodigoReferencia\":2,\"RazonReferencia\":\"corr\",\"Dte\":{\"Id\":1032,\"EmpresaId\":1,\"ClienteId\":21,\"TipoDocumento\":61,\"Folio\":9,\"FechaEmision\":\"2019-07-05T11:37:17.6930264\",\"IndicadorServicio\":0,\"FormaPago\":1,\"TpoMovGlobal\":\"D\",\"TpoValorGlobal\":\"%\",\"ValorDRGlobal\":5.0,\"MontoNeto\":2069251.0,\"MontoExento\":0.0,\"MontoIva\":393158.0,\"MontoTotal\":2462409.0,\"NombreXml\":\"T61_F9-2019JulFri113723.xml\",\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035},\"Cliente\":{\"Id\":21,\"Rut\":\"17558736-5\",\"RazonSocial\":\"Angeles Enterprises\",\"Direccion\":\"Vicuña Mackena 7801\",\"Giro\":\"SERVICIOS INFORMATICOS\",\"Comuna\":\"LA FLORIDA\",\"Ciudad\":\"SANTIAGO\",\"Telefono\":\"992861178\",\"Email\":\"angel.pinilla@outlook.es\",\"EmpresaId\":1,\"Empresa\":{\"Id\":1,\"Rut\":\"76929437-6\",\"RazonSocial\":\"FABRICA DE MUEBLES INNOVITA SPA\",\"NombreFantasia\":\"INNOVITA\",\"Giro\":\"FABRICA DE MUEBLES\",\"Direccion\":\"ROSA ESTER 03361\",\"Telefono\":\"967595902\",\"Email\":\"CAMILO.PEREZ@INNOVAMOBEL.CL\",\"Ciudad\":\"SANTIAGO\",\"Comuna\":\"LA PINTANA\",\"NombreCertificado\":\"sergiomendoza.pfx\",\"ClaveCertificado\":\"mendoza2019\",\"Acteco\":464901,\"RepresentanteLegal\":\"SERGIO FELIZ MENDOZA SCHLENKERT\",\"RutRepresentanteLegal\":\"16125758-3\",\"FechaResolucion\":\"2014-08-22\",\"NumeroResolucion\":0,\"UnidadSII\":\"LA PINTANA\",\"SisContribuyenteId\":1035}}}}]}");

$status = '';

$rutRepresentanteLegal = $jsoncsharp->Dte->Empresa->RutRepresentanteLegal;
$fechaResolucion = $jsoncsharp->Dte->Empresa->FechaResolucion;
$numeroResolucion = $jsoncsharp->Dte->Empresa->NumeroResolucion;
#Declarando variables
$linea = 0;
$nroLinRef = 0;
$lineaDr = 0;
$marcaFOLIO = 0;
$timezone = new DateTimeZone('America/Santiago');
$date = new DateTime('', $timezone);
$fechaTimbre = $date->format('Y-m-d\TH:i:s');

$idUnico = $jsoncsharp->Dte->Id;
#Ojo que tambien tengo el SisContribuyenteId ....
$certificadoNombre = $jsoncsharp->Dte->Empresa->NombreCertificado;
$certificadoClave = $jsoncsharp->Dte->Empresa->ClaveCertificado;;

$tipoDte = $jsoncsharp->Dte->TipoDocumento;
$folio = $jsoncsharp->Dte->Folio;
$fechaAuxiliar = date_create($jsoncsharp->Dte->FechaEmision);
$fechaEmision = date_format($fechaAuxiliar, 'Y-m-d');
$formaPago =  $jsoncsharp->Dte->FormaPago;

#Variables de Emisor
$rutEmisor = $jsoncsharp->Dte->Empresa->Rut;
$razonSocial = $jsoncsharp->Dte->Empresa->RazonSocial;
$acteco = $jsoncsharp->Dte->Empresa->Acteco;
$giro = $jsoncsharp->Dte->Empresa->Giro;
$direccion = $jsoncsharp->Dte->Empresa->Direccion;
$comuna = $jsoncsharp->Dte->Empresa->Comuna;
$ciudad = $jsoncsharp->Dte->Empresa->Ciudad;
$telefono = $jsoncsharp->Dte->Empresa->Telefono;
$email = $jsoncsharp->Dte->Empresa->Email;

#Variables de receptor
$rutReceptor = $jsoncsharp->Dte->Cliente->Rut;
$razonReceptor = $jsoncsharp->Dte->Cliente->RazonSocial;
$giroReceptor = $jsoncsharp->Dte->Cliente->Giro;
$comunaReceptor = $jsoncsharp->Dte->Cliente->Comuna;
$ciudadReceptor = $jsoncsharp->Dte->Cliente->Ciudad;
$direccionReceptor = $jsoncsharp->Dte->Cliente->Direccion;
$emailReceptor = $jsoncsharp->Dte->Cliente->Email;

//instanciando clases
$DTE = new DTE();
$Documento = new Documento();
$totalesEncabezado = new Totales();

$Documento->setEncabezado();
$Documento->Encabezado->setIdDoc();
$Documento->Encabezado->IdDoc->setTipoDTE((string) $tipoDte);
$Documento->Encabezado->IdDoc->setFolio((string) $folio);
$Documento->Encabezado->IdDoc->setFchEmis($fechaEmision);
$Documento->Encabezado->IdDoc->setFmaPago((string) $formaPago);

$Documento->Encabezado->setEmisor();
$Documento->Encabezado->Emisor->setRUTEmisor($rutEmisor);
$Documento->Encabezado->Emisor->setRznSoc($razonSocial);
$Documento->Encabezado->Emisor->setActeco((string) $acteco);
$Documento->Encabezado->Emisor->setGiroEmis($giro);
$Documento->Encabezado->Emisor->setTelefono($telefono);
$Documento->Encabezado->Emisor->setCorreoEmisor($email);
$Documento->Encabezado->Emisor->setDirOrigen($direccion);
$Documento->Encabezado->Emisor->setCmnaOrigen($comuna);
$Documento->Encabezado->Emisor->setCiudadOrigen($ciudad);

$Documento->Encabezado->setReceptor();
$Documento->Encabezado->Receptor->setRUTRecep($rutReceptor);
$Documento->Encabezado->Receptor->setRznSocRecep($razonReceptor);
$Documento->Encabezado->Receptor->setGiroRecep($giroReceptor);
$Documento->Encabezado->Receptor->setCmnaRecep($comunaReceptor);
$Documento->Encabezado->Receptor->setCiudadRecep($ciudadReceptor);
$Documento->Encabezado->Receptor->setDirRecep($direccionReceptor);

#Totales

$totalesEncabezado->setMntNeto((string) $jsoncsharp->Dte->MontoNeto);

if ($jsoncsharp->Dte->MontoExento > 0)
    $totalesEncabezado->setMntExe((string) $jsoncsharp->Dte->MontoExento);
$totalesEncabezado->setTasaIVA("19");
$totalesEncabezado->setIVA((string) $jsoncsharp->Dte->MontoIva);
$totalesEncabezado->setMntTotal((string) $jsoncsharp->Dte->MontoTotal);
$Documento->Encabezado->setTotales($totalesEncabezado);


#Detalle

for ($x = 0; $x <= count($jsoncsharp->Detalle) - 1; $x++) {
    $linea++;
    $Detalle = new Detalle;
    $Detalle->setNroLinDet((string) $linea);

    if ($jsoncsharp->Detalle[$x]->IsExento == 1)
        $Detalle->setIndExe((string) $jsoncsharp->Detalle[$x]->IsExento);

    $Detalle->setNmbItem(utf8_decode($jsoncsharp->Detalle[$x]->Descripcion));

    if (trim($jsoncsharp->Detalle[$x]->Cantidad) != "")
        $Detalle->setQtyItem((string) $jsoncsharp->Detalle[$x]->Cantidad);

    if (trim($jsoncsharp->Detalle[$x]->UnidadMedida) != "")
        $Detalle->setUnmdItem($jsoncsharp->Detalle[$x]->UnidadMedida);

    if (intval($jsoncsharp->Detalle[$x]->Precio) > 0)
        $Detalle->setPrcItem((string) $jsoncsharp->Detalle[$x]->Precio);

    if ($jsoncsharp->Detalle[$x]->DescuentoPct != "" && intval($jsoncsharp->Detalle[$x]->DescuentoPct) > 0)
        $Detalle->setDescuentoPct((string) $jsoncsharp->Detalle[$x]->DescuentoPct);

    if ($jsoncsharp->Detalle[$x]->DescuentoMonto > 0)
        $Detalle->setDescuentoMonto((string) $jsoncsharp->Detalle[$x]->DescuentoMonto);

    if (trim($jsoncsharp->Detalle[$x]->RecargoPct) != "" && intval($jsoncsharp->Detalle[$x]->RecargoPct) > 0)
        $Detalle->setRecargoPct((string) $jsoncsharp->Detalle[$x]->RecargoPct);

    if ($jsoncsharp->Detalle[$x]->RecargoMonto > 0)
        $Detalle->setRecargoMonto((string) $jsoncsharp->Detalle[$x]->RecargoMonto);

    $Detalle->setMontoItem((string) $jsoncsharp->Detalle[$x]->TotalItem);
    $Documento->setDetalle($Detalle);
}

//Descuento o Recargo 
#                                   **********************Refactoring****************************

if ($jsoncsharp->Dte->TpoMovGlobal == "D" and $jsoncsharp->Dte->TpoMovGlobal != null) {
    $lineaDr++;
    $DescuentoGlobal = new DscRcgGlobal();
    $DescuentoGlobal->setNroLinDR((string) $lineaDr);
    $DescuentoGlobal->setTpoMov($jsoncsharp->Dte->TpoMovGlobal);
    $DescuentoGlobal->setTpoValor($jsoncsharp->Dte->TpoValorGlobal);
    $DescuentoGlobal->setValorDR((string) $jsoncsharp->Dte->ValorDRGlobal);
    $Documento->setDscRcgGlobal($DescuentoGlobal);
}

#end descuento o recargo

#Seteando Referencias
for ($y = 0; $y <= count($jsoncsharp->Referencia) - 1; $y++) {

    if ($jsoncsharp->Referencia[$y]->TipoDocumento != "") {
        $nroLinRef++;
        $referencia = new Referencia();
        $referencia->setNroLinRef((string) $nroLinRef);
        $referencia->setTpoDocRef((string) $jsoncsharp->Referencia[$y]->TipoDocumento);
        $referencia->setFolioRef((string) $jsoncsharp->Referencia[$y]->FolioReferencia);

        $fechaAuxiliarRef = date_create($jsoncsharp->Referencia[$y]->FechaReferencia);

        $fechaRef = date_format($fechaAuxiliarRef, 'Y-m-d');

        $referencia->setFchRef($fechaRef);

        if ($jsoncsharp->Referencia[$y]->CodigoReferencia > 0)
            $referencia->setCodRef((string) $jsoncsharp->Referencia[$y]->CodigoReferencia);

        if ($jsoncsharp->Referencia[$y]->RazonReferencia != "")
            $referencia->setRazonRef($jsoncsharp->Referencia[$y]->RazonReferencia);

        $Documento->setReferencia($referencia);
    }
}
#end referencias


#Core creacion XML

$pRutEmpresa   = substr($Documento->Encabezado->Emisor->getRUTEmisor(), 0, -2);

$idDte = "T" . $Documento->Encabezado->IdDoc->getTipoDte() . "_F" . $Documento->Encabezado->IdDoc->getFolio() . "-" . date("YMDHis");


$obj = new ObjectAndXML($idDte, $pRutEmpresa);
$obj->setStartElement("DTE");
$obj->setId($idDte);
$Documento->setTmstFirma($fechaTimbre);
$Documento->setTED();
$Documento->TED->setDD();
$Documento->TED->DD->setRE($Documento->Encabezado->Emisor->getRUTEmisor());
$Documento->TED->DD->setTD($Documento->Encabezado->IdDoc->getTipoDte());
$Documento->TED->DD->setF($Documento->Encabezado->IdDoc->getFolio());
$Documento->TED->DD->setFE($Documento->Encabezado->IdDoc->getFchEmis());
$Documento->TED->DD->setRR($Documento->Encabezado->Receptor->getRUTRecep());
$Documento->TED->DD->setRSR(utf8_decode(replaceSii(substr($Documento->Encabezado->Receptor->getRznSocRecep(), 0, 40))));
$Documento->TED->DD->setMNT($Documento->Encabezado->Totales->getMntTotal());
$Documento->TED->DD->setIT1(utf8_decode(replaceSii(substr($Documento->Detalle[0]->getNmbItem(), 0, 40))));
$Documento->TED->DD->setTSTED($fechaTimbre);

$DTE->setDocumento($Documento);
utf8_encode_deep($DTE);
$recordsXML = $obj->objToXML($DTE);

$LCAFImport = new DOMDocument();
$LCAFImport->formatOutput = TRUE;
$LCAFImport->preserveWhiteSpace = TRUE;

/********** OBTENER CAF, LLAVES PRIVADA Y PUBLICA DEL CAF **********/
$archivoFolio = validaFolio($folio, substr($rutEmisor, 0, -2), $tipoDte);

if ($archivoFolio != "ERROR" and $archivoFolio != "") {
    if (!$LCAFImport->load("procesos/folios/" . substr($rutEmisor, 0, -2) . "/" . $archivoFolio)) {
        $XMLFOLIO = utf8_encode(file_get_contents("procesos/folios/" . substr($rutEmisor, 0, -2) . "/" . $archivoFolio));
        if ($LCAFImport->loadXML($XMLFOLIO)) {
            $marcaFOLIO = 1;
        } else {
            $status .= "Folio validado";
        }
    }
} else {
    exit("No se pudo encontrar un CAF para el folio " . $folio);
}


$CAF = $LCAFImport->getElementsByTagName("CAF")->item(0);
$nodecaf = $LCAFImport->getElementsByTagName("CAF")->item(0);
$priv_key = $LCAFImport->getElementsByTagName("RSASK")->item(0)->nodeValue;
$CAF = $LCAFImport->saveXML($CAF);

if ($marcaFOLIO == 1)
    $CAF = utf8_decode($CAF);

/**********FIN CAF **********/

$DTE_TIMBRE = new DOMDocument();
$DTE_TIMBRE->formatOutput = FALSE;
$DTE_TIMBRE->preserveWhiteSpace = TRUE;

if (is_file("procesos/xml_emitidos/" . substr($rutEmisor, 0, -2) . "/" . $obj->getId() . ".xml")) {
    $DTE_TIMBRE->load("procesos/xml_emitidos/" . substr($rutEmisor, 0, -2) . "/" . $obj->getId() . ".xml");
    $DTE_TIMBRE->encoding = "ISO-8859-1";
} else {
    exit("No existe archivo XML original. Cerrando proceso.");
}

$import = $DTE_TIMBRE->importNode($nodecaf, true);
$TSTED = $DTE_TIMBRE->getElementsByTagName("TSTED")->item(0);
$TSTED->parentNode->insertBefore($import, $TSTED);

$DD2 = "<DD><RE>" . $Documento->Encabezado->Emisor->getRUTEmisor() . "</RE><TD>" . $tipoDte . "</TD><F>" .
    $folio . "</F><FE>" . $fechaEmision . "</FE><RR>" . $rutReceptor . "</RR><RSR>" .
    $Documento->TED->DD->getRSR() . "</RSR><MNT>" . $jsoncsharp->Dte->MontoTotal . "</MNT><IT1>" .
    $Documento->TED->DD->getIT1() . "</IT1>" . replaceCaf($CAF) . "<TSTED>" . $fechaTimbre . "</TSTED></DD>";

$FRMT = buildSign($DD2, $priv_key);
$fragment = $DTE_TIMBRE->createDocumentFragment();
$fragment->appendXML("<FRMT algoritmo=\"SHA1withRSA\">$FRMT</FRMT>\n");
$TED = $DTE_TIMBRE->getElementsByTagName("TED")->item(0);
$TED->appendChild($fragment);

$code = trim(str_replace("> <", "><", str_replace(">  <", "><", str_replace(">   <", "><", str_replace(">    <", "><", str_replace("\n", "", str_replace("\t", "", utf8_decode($DTE_TIMBRE->saveXML($TED)))))))));

$xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

if (!file_exists("procesos/certificados/" . substr($rutEmisor, 0, -2) . "/" . $certificadoNombre)) {
    exit("No se encontro certificado digital en nuestro servidor");
}

$pfx = file_get_contents("procesos/certificados/" . substr($rutEmisor, 0, -2) . "/" . $certificadoNombre);
openssl_pkcs12_read($pfx, $key, $certificadoClave);

if (empty($key["pkey"]))
    exit("La clave del certificado esta vacía o es incorrecta.");

$xmlTool->setPrivateKey($key["pkey"]);
$xmlTool->setpublickey($key["cert"]);
$xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
$xmlTool->sign($DTE_TIMBRE, "DTE");

$DTE_TIMBRE->save("procesos/xml_emitidos/" . substr($rutEmisor, 0, -2) . "/" . $obj->getId() . ".xml");


UpdateDteSqlServer($idUnico, $obj->getId() . ".xml");

#Seccion de envio automatico

if (enviaDocumento($obj->getId() . ".xml", $rutEmisor, $rutRepresentanteLegal, $fechaResolucion, $numeroResolucion, $certificadoNombre, $certificadoClave, $emailReceptor)) {
    $status .= "\nGenerando xml de envio...";
} else {
    exit("[] **ERROR**||Se produjo un error al procesar el sobre\nProceso Finalizado " . date("H:i:s"));
}

echo $status;

function ConnectSqlServer()
{

    $serverName = "(localdb)\MSSQLLocalDB";
    $connectionInfo = array("Database" => "aspnet-Innova-09A47401-7E86-4A72-A231-D502CE863D4A", "UID" => "", "PWD" => "");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    return $conn;
}

function UpdateDteSqlServer($id, $nombreXml)
{
    $conn = ConnectSqlServer();

    if ($conn) {
        echo "<h6 style='color:green'>Conexión establecida con servidor de Base de datos.</h6>";
        $sql = "update Dte set NombreXml =(?) where Id=(?)";
        $params = array($nombreXml, $id);

        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false)
            die(print_r(sqlsrv_errors(), true));

        sqlsrv_free_stmt($stmt);
    } else {
        exit("Conexión no se pudo establecer con servidor de base de datos.");
        die(print_r(sqlsrv_errors(), true));
    }
}



ob_end_flush();




##################### Funciones #######################
function replaceCaf($subject)
{
    $return = str_replace(PHP_EOL, "", $subject);
    $return = str_replace("> <", "", $return);
    return $return;
}


function libxml_display_error($error)
{
    $return = "\n";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "\t\t\tWarning $error->code: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "\t\t\tError $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "\t\t\tFatal Error $error->code: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " en el archivo $error->file";
    }
    $return .= " on la liena $error->line\n";

    return $return;
}

function libxml_display_errors()
{

    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        error_log(libxml_display_error($error), 0);
    }
    libxml_clear_errors();
}

function validaFolio($folio, $rutEmpresa, $tipoDTE)
{
    $fuente = "procesos/folios/$rutEmpresa/";
    $directorio = opendir($fuente);
    while ($archivo = readdir($directorio)) {
        if (is_file($fuente . $archivo)) {
            $XMLFOLIO = utf8_encode(file_get_contents("procesos/folios/$rutEmpresa/" . $archivo));
            try {
                $Documento = new DOMDocument();
                $Documento->formatOutput = FALSE;
                $Documento->preserveWhiteSpace = TRUE;
                $Documento->loadXML($XMLFOLIO);
                $Documento->encoding = "ISO-8859-1";
            } catch (Exception $e) {
                exit($e);
            }

            $tipoDTEin = intval($Documento->getElementsByTagName("TD")->item(0)->nodeValue);

            if ($tipoDTEin == intval($tipoDTE)) {
                $folioIni = intval($Documento->getElementsByTagName("D")->item(0)->nodeValue);
                $folioFin = intval($Documento->getElementsByTagName("H")->item(0)->nodeValue);

                if ($folio >= $folioIni and $folio <= $folioFin) {
                    //echo 'get in ini and fin';
                    $rt = $archivo;
                    return $rt;
                } else {
                    //error_log("\t$folio>=$folioIni and $folio<=$folioFin");
                    //echo 'error';
                    $rt = "ERROR";
                }
            } else {
                //    echo "\tTipo de DTE Distintos".$tipoDTEin."==".intval($tipoDTE);                    
                $rt = "ERROR";
            }
        }
    }
    //echo $rt;
    return $rt;
}



function utf8_encode_deep(&$input)
{
    if (is_string($input)) {
        $input = utf8_encode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
    }
}

function decimales($n)
{
    $aux = (string) $n;
    if (strpos($aux, ".") > 0) {
        $decimal = substr($aux, strpos($aux, ".") + 1, 2);
        $ret = substr($aux, 0, strpos($aux, "."));
        return $ret;
    } else
        return $aux;
}

function setInterval($f, $milliseconds)
{
    $seconds = (int) $milliseconds / 1000;
    while (true) {
        $f();
        sleep($seconds);
    }
}

function replaceSii($subject)
{
    return str_replace(array("&", "<", ">", "\"", "'"), array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $subject);
}
function TildesHtml($cadena)
{
    return str_replace(array("á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ", "°", "\"", "'"), array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;", "&deg;", "&quot;", "&apos;"), $cadena);
}
