<?php
ob_start();
error_reporting(E_ALL);
ini_set('error_reporting', E_ERROR);
ini_set("display_errors", 1);
require_once 'vendorC/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$XMLDTE = new DOMDocument();
$XMLDTE->formatOutput = FALSE;
$XMLDTE->preserveWhiteSpace = TRUE;
$XMLDTE->encoding = "ISO-8859-1";
$content = "<page>";

$unidad = $_GET["unidad"];



//VALIDAR SI VIENE VACIO ALGUNO DE LOS PARAMETROS PARA ENVIARLE ALGO


if ($_GET["tipo"] == "COMPRA")
    $rutaDte = "https://netdte.cl/documentos/recepcionados/" . $_GET["rut"] . "/" . $_GET["filename"] . "";
else {
    $rutaDte = "/procesos/xml_envios/" . $_GET["rut"] . "/" . $_GET["filename"] . "";
}
if (realpath($rutaDte)) {
    exit("<h5 style='color:red'>No se encontró el xml asociado al documento. Intente re-publicar el DTE.</h5>");
}

if ($XMLDTE->load($rutaDte)) {
    $DTE = $XMLDTE->getElementsByTagName("EnvioDTE");
    foreach ($DTE as $Documento) {
        if ($Documento->getElementsByTagName("Folio")->item(0)->nodeValue == $_GET["folio"]) {
            if ($Documento->getElementsByTagName("FchVenc")->item(0)->nodeValue != "") {
                $fechaVenc = date("d-m-Y", strtotime($Documento->getElementsByTagName("FchVenc")->item(0)->nodeValue));
            } else {
                $fechaVenc = "";
            }

            $TED = $Documento->getElementsByTagName("TED")->item(0);
            $timbre = trim(str_replace("> <", "><", str_replace(">  <", "><", str_replace(">   <", "><", str_replace(">    <", "><", str_replace("\n", "", str_replace("\t", "", utf8_decode($XMLDTE->saveXML($TED)))))))));
            $timbre = str_replace("\"", "'", $timbre);
            $timbre = str_replace(array("<", ">"), array("&lt;", "&gt;"), $timbre);

            switch (intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue)) {
                case 33:
                    $TipoDocumento = "FACTURA ELECTRÓNICA";
                    break;
                case 34:
                    $TipoDocumento = "FACTURA NO AFECTA <br>O<br> EXENTA ELECTRÓNICA";
                    break;
                case 43:
                    $TipoDocumento = "LIQUIDACIÓN<br>FACTURA ELECTRÓNICA";
                    break;
                case 61:
                    $TipoDocumento = "NOTA DE CRÉDITO<br>ELECTRÓNICA";
                    break;
                case 52:
                    $TipoDocumento = "GUÍA DE DESPACHO<br>ELECTRÓNICA";
                    break;
                default:
                    $TipoDocumento = $Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }

            switch (intval($Documento->getElementsByTagName("TipoDespacho")->item(0)->nodeValue)) {
                case 1:
                    $TipoDespacho = "Efectivo";
                    break;
                case 2:
                    $TipoDespacho = "Efectivo";
                    break;
                case 3:
                    $TipoDespacho = "Efectivo";
                    break;
            }

            #case forma de pago
            switch (intval($Documento->getElementsByTagName("FmaPago")->item(0)->nodeValue)) {
                case 1:
                    $formaPago = "Contado";
                    break;
                case 2:
                    $formaPago = "Credito";
                    break;
                case 3:
                    $formaPago = "Entrega Gratuita";
                    break;
            }


            $Telefono = $Documento->getElementsByTagName("Telefono")->item(0)->nodeValue;

            $content .= $watermark .

                '<style media="screen" type="text/css">
body{font-family:Arial,Verdana,sans-serif;font-size:12px}#container{margin-left:30px;margin-top:40px;margin-right:30px;height:950px}.emisor{font:inherit;font-size:14px}#emisor{position:relative;left:0;font-weight:700;width:450px;line-height:120%;font-size:10px}#datosFactura{position:absolute;margin-top:60px;width:170px;right:0}#factura{border-width:3px;border-style:solid;border-color:#d64431;padding:0 3px 8px;text-align:center;line-height:90%;width:210px;margin-right:4px}#factura p{margin:5px;font-weight:700}#sii{text-align:center}.receptor{position:relative;width:681px;height:72px;margin-top:35px;font-weight:700;font-size:10px;border:1px solid #929292;border-radius:6px;padding:6px 6px 12px}.receptor table td{padding-right:3px}.tabla1{position:absolute;left:0;margin:6px 8px}.tabla2{position:absolute;margin:6px 8px;width:180px;right:0}.aduana{position:relative;width:681px;height:133px;margin-top:7px;font-weight:700;font-size:10px;border:1px solid #929292;border-radius:6px;padding:6px 6px 3px}.aduana table td{padding-right:3px;word-break:break-all}.aduana1{position:absolute;left:0;margin:6px 8px}.tablaAduana1{border-collapse:separate;border-spacing:0 3px}.aduana2{position:absolute;right:0;margin:6px 8px;width:250px}.tablaAduana2{border-collapse:separate;border-spacing:0 3px}#detalle{position:relative;width:695px;margin-top:7px}#tablaDetalle{position:absolute;border-collapse:collapse;font-size:10px;text-align:center;table-layout:fixed}#tablaDetalle td,#tablaDetalle th{padding:2px 13px}#tablaDetalle th{background-color:#0f7e9a;color:#fff;font-weight:700;border:1px solid #0f7e9a;height:8px}#tablaDetalle td{border-left:1px solid #929292;border-right:1px solid #929292;height:8px;word-wrap:break-word}#detallesInferior td{border:none;background:none}#tablaRef{border-collapse:collapse;margin-top:5px;font-size:10px;width:645px;text-align:center}#tablaRef th{background-color:#0f7e9a;color:#fff;font-weight:700;border:1px solid #0f7e9a;padding:2px 13px}#tablaRef td{border-left:1px solid #929292;border-right:1px solid #929292;word-wrap:break-word}#tablaValores{border-collapse:collapse;margin-left:10px;margin-top:5px}#tablaValores td{font-weight:700;font-size:9px;border:1px solid #929292}#recibo{position:absolute;margin-top:890px;margin-left:305px;width:350px;float:right;padding:5px;font-size:11px;text-align:justify}#datosRecibo td:nth-child(1){font-weight:700}#datosRecibo2 td:nth-child(1){font-weight:700}#acuse{border:1px solid #000;border-radius:3px;padding:5px;font-size:7px}#timbre{position:absolute;margin-left:30px;margin-top:890px;text-align:center;font-size:8px;font-weight:700;color:#d64431}.rojo{color:#d64431}.azul{color:#0f7e9a}.b{font-weight:700}
       </style>
<body>    
        <div id="container">
            <div id="emisor"> 
                <span class="azul emisor"><b>' . strtoupper($Documento->getElementsByTagName("RznSoc")->item(0)->nodeValue) . '</b></span><br>
                <b>' . strtoupper(substr($Documento->getElementsByTagName("GiroEmis")->item(0)->nodeValue, 0, 49)) . '</b><br>
                ' . strtoupper(substr($Documento->getElementsByTagName("DirOrigen")->item(0)->nodeValue, 0, 30)) . '&nbsp;,&nbsp;'
                . strtoupper($Documento->getElementsByTagName("CmnaOrigen")->item(0)->nodeValue) . ',&nbsp;'
                . strtoupper($Documento->getElementsByTagName("CiudadOrigen")->item(0)->nodeValue) . '<br>'
                . 'E-mail: ' . strtoupper($Documento->getElementsByTagName("CorreoEmisor")->item(0)->nodeValue) . '<br>';
            if ($Telefono != "") {
                $content .= 'Teléfono: ' . $Telefono;
            }
            $content .= '<br><br><br>   
            </div>
            <div id="datosFactura">
                <div id="factura" class="rojo">
                    <p><b>R.U.T.: ' . rut_format($Documento->getElementsByTagName("RUTEmisor")->item(0)->nodeValue) . '</b></p>
                    <p><b>' . strtoupper($TipoDocumento) . '</b></p>
                    <p><b>Nº&nbsp;' . str_pad($Documento->getElementsByTagName("Folio")->item(0)->nodeValue, 9, "0", STR_PAD_LEFT) . '</b></p>
                </div>
                <div id="sii" class="rojo"><b>S.I.I. - ' . $unidad . '</b></div>
            </div>   
            <div class="receptor">
                <div class="tabla1">
                    <table>
                        <tr>
                            <td><b>R.U.T.:</b></td><td>:</td>
                            <td style="padding-right: 5px;  width: 330px;">' . $Documento->getElementsByTagName("RUTRecep")->item(0)->nodeValue . '</td>                            
                        </tr>
                        <tr>                         
                            <td><b>Razón Social</b></td><td>:</td>
                            <td style="padding-right: 5px;width: 330px;" >' . strtoupper($Documento->getElementsByTagName("RznSocRecep")->item(0)->nodeValue) . '</td>
                        </tr>
                        <tr>                        
                            <td><b>Dirección</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 330px;">' . strtoupper($Documento->getElementsByTagName("DirRecep")->item(0)->nodeValue) . ', ' . strtoupper($Documento->getElementsByTagName("CmnaRecep")->item(0)->nodeValue) . '</td>
                        </tr>           
                        <tr>
                            <td><b>Giro</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 330px;">' . strtoupper($Documento->getElementsByTagName("GiroRecep")->item(0)->nodeValue) . '</td>
                        </tr>                       
                    </table>
                </div>
                <div class="tabla2">
                    <table>
                        <tr>
                            <td><b>Fecha Emisión: </b></td>
                            <td style="padding-right: 5px; width: 170px;">' .  date("l d-m-Y",  strtotime($Documento->getElementsByTagName("FchEmis")->item(0)->nodeValue)) . '</td>
                        </tr>
                        <tr>   
                            <td><b>Forma de Pago:</b></td>
                            <td style="padding-right: 5px; width: 170px;">' . strtoupper($formaPago) . '</td>
                        </tr>
                        <tr>     
                            
                        </tr>                    
                    </table>
                </div>
            </div>    
  <div id="detalle" >
                <table id="tablaDetalle">
                    <tr>
                        <th style="width: 17px;"><b>Cantidad</b></th>
                        <th style="width: 200px;"><b>Descripción</b></th>
                        <th style="width: 16px;"><b>Unidad</b></th>
                        <th style="width: 11px;"><b>Desc. %</b></th>
                        <th style="width: 9px;"><b>Rec. %</b></th>
                        <th style="width: 28px;"><b>P. Unitario</b></th>
                        <th style="width: 28px;"><b>Total</b></th>
                    </tr>';
            $Detalles = $Documento->getElementsByTagName("Detalle");
            $lin = 0;
            foreach ($Detalles as $Detalle) {
                if (trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue) != "") {
                    if (trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue) != trim($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue)) {
                        $DescItem = trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue);
                    }
                } else {
                    $DescItem = "";
                }
                $content .=
                    '<tr>
                            <td>' . number_format($Detalle->getElementsByTagName("QtyItem")->item(0)->nodeValue, 2, ".", ",") . '</td>
                           <td style="width: 200px;">' . substr($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue, 0, 60) . substr($DescItem, 0, 72) . '</td>                                
                            <td>' . $Detalle->getElementsByTagName("UnmdItem")->item(0)->nodeValue . '</td>
                            <td>' . number_format($Detalle->getElementsByTagName("DescuentoPct")->item(0)->nodeValue, 2, '.', ',') . '</td>
                            <td>' . number_format($Detalle->getElementsByTagName("RecargoPct")->item(0)->nodeValue, 2, '.', ',') . '</td>
                            <td>' . number_format($Detalle->getElementsByTagName("PrcItem")->item(0)->nodeValue, 2, '.', ',') . '</td>                            
                            <td>' . number_format($Detalle->getElementsByTagName("MontoItem")->item(0)->nodeValue, 2, '.', ',') . '</td>
                        </tr>';
                $lin++;
            }
            $hasta = 20 - $lin;
            for ($l = 0; $l <= $hasta; $l++) {
                $content .= '<tr><td>&nbsp;</td>';
                $content .= '<td>&nbsp;</td>';
                $content .= '<td>&nbsp;</td>';
                $content .= '<td>&nbsp;</td>';
                $content .= '<td>&nbsp;</td>';
                $content .= '<td>&nbsp;</td>';
                $content .= '<td>&nbsp;</td></tr>';
            }
            $content .= '<tr><td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
            $content .= '<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
            $content .= '<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
            $content .= '<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
            $content .= '<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
            $content .= '<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
            $content .= '<td style="border-bottom: 1px solid #929292;">&nbsp;</td></tr>
                </table>
                <table id=detallesInferior>
                <tr>
                <td style="width: 510px; font-size:10px; padding-top:5px;">';
            $Referencias = $Documento->getElementsByTagName("Referencia");
            $contador = 1;
            foreach ($Referencias as $Referencia) {

                $content .= '<b>Referencia: </b>' . $contador . ' - ' . $TipoDocumentoRef[$Referencia->getElementsByTagName("TpoDocRef")->item(0)->nodeValue] .
                    ' N° ' . $Referencia->getElementsByTagName("FolioRef")->item(0)->nodeValue . ' del ' . $Referencia->getElementsByTagName("FchRef")->item(0)->nodeValue . ': ' . $Referencia->getElementsByTagName("RazonRef")->item(0)->nodeValue . '<br>';
                $contador++;
            }
            $content .= '</td>
                <td>
                <table id="tablaValores">';
            $content .=
                '<tr>
                            <td style=" padding-right: 10px; width: 50px;">Neto $</td><td style="padding-left: 10px; text-align: right; width: 60px; background-color: #ececec;">' . number_format($Documento->getElementsByTagName("MntNeto")->item(0)->nodeValue, 2, ".", ",") . '</td>
                        </tr>
                        <tr>
                            <td style=" padding-right: 10px; width: 50px">Exento $</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">' . number_format($Documento->getElementsByTagName("MntExe")->item(0)->nodeValue, 2, ".", ",") . '</td>
                        </tr>
                        <tr>
    <td style=" padding-right: 10px; width: 50px">IVA (19%) $</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">' . number_format($Documento->getElementsByTagName("IVA")->item(0)->nodeValue, 2, ".", ",") . '</td>                        
                        </tr>
                        <tr>
                            <td style=" padding-right: 10px; width: 50px">Total $</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">' . number_format($Documento->getElementsByTagName("MntTotal")->item(0)->nodeValue, 2, ".", ",") . '</td>
                        </tr>
                        
                    </table>
                </td> </tr> </table>
            </div>';

            $content .=
                '<div id="timbre">                                
                <span style="margin-left:37px;font-face:Arial;font-size:7pt">
                <barcode dimension="2D" type="PDF417" value="' . $timbre . '" style="width:60mm; height:30mm; font-size: 4mm"></barcode></span><br>
                Timbre Electr&oacute;nico SII<br>
                Resolución ' . $Documento->getElementsByTagName("NroResol")->item(0)->nodeValue . ' de ' . getDate(strtotime($Documento->getElementsByTagName("FchResol")->item(0)->nodeValue))["year"] . '<br>
                Verifique documento: www.sii.cl
                </div> 
            </div>            
    </body>';
        }
    }
} else {
    $content .= $rutaDte;
}
$content .= "</page>";


if (!isset($_REQUEST["debug"])) {
    $html2pdf = new Html2Pdf('P', 'A4', 'es');
    ob_end_clean();
    $html2pdf->WriteHTML($content);

    $html2pdf->Output('dtecore_file.pdf');
} else {

    echo $content;
}

function numtoletras($xcifra)
{
    $xarray = array(0 => "Cero", 1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS");

    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el l&#65533;mite a 6 d&#65533;gitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While	
        while ($xexit) {
            if ($xi == $xlimite) // si ya lleg&#65533; al l&#65533;mite máximo de enteros
            {
                break; // termina el ciclo
            }

            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres d&#65533;gitos)
            for ($xy = 1; $xy < 4; $xy++) // ciclo para revisar centenas, decenas y unidades, en ese orden
            {
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) // si el grupo de tres d&#65533;gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                        { } else {
                            $xseek = $xarray[substr($xaux, 0, 3)]; // busco si la centena es n&#65533;mero redondo (100, 200, 300, 400, etc..)
                            if ($xseek) {
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Mill&#65533;n, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            } else // entra aqu&#65533; si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                            {
                                $xseek = $xarray[substr($xaux, 0, 1) * 100]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma l&#65533;gica que las centenas)
                        if (substr($xaux, 1, 2) < 10) { } else {
                            $xseek = $xarray[substr($xaux, 1, 2)];
                            if ($xseek) {
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            } else {
                                $xseek = $xarray[substr($xaux, 1, 1) * 10];
                                if (substr($xaux, 1, 1) * 10 == 20)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) // si la unidad es cero, ya no hace nada
                        { } else {
                            $xseek = $xarray[substr($xaux, 2, 1)]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena .= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena .= " DE";

        // ----------- esta l&#65533;nea la puedes cambiar de acuerdo a tus necesidades o a tu pa&#65533;s -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena .= "UN BILLON ";
                    else
                        $xcadena .= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena .= "UN MILLON ";
                    else
                        $xcadena .= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO PESOS";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO";
                    }
                    if ($xcifra >= 2) {
                        $xcadena .= " PESOS"; // 
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para M&#65533;xico se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR	($xz)
    return trim($xcadena);
} // END FUNCTION


function subfijo($xx)
{ // esta funci&#65533;n regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //	
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}

function rut_format($rut)
{
    return number_format(substr($rut, 0, -1), 0, "", ".") . '-' . substr($rut, strlen($rut) - 1, 1);
}
