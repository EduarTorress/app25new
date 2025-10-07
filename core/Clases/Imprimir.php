<?php

namespace Core\Clases;

// use chillerlan\QRCode\QRCode as QRCodeQRCode;
use Core\Foundation\Application;
use Fpdf\Fpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use tFPDF;

class Imprimir
{
    var $rucempresa;
    var $empresa;
    var $direccionempresa;
    var $tipocomprobante;
    var $numero;
    var $serie;
    var $ndoc;
    var $tdoc;
    var $moneda;
    var $fecha;
    var $fechavto;
    var $hora;
    var $dias;
    var $ruccliente;
    var $dnicliente;
    var $guiaremision;
    var $cliente;
    var $vendedor;
    var $direccioncliente;
    var $formadepago;
    var $importeletras;
    var $valorgravado;
    var $exonerado;
    var $gratuita;
    var $dscto;
    var $igv;
    var $total;
    var $vigv;
    var $rucremitente;
    var $rucdestinatario;
    var $ptopartida;
    var $ptollegada;
    var $remitente;
    var $destinatario;
    var $fechat;
    var $placa;
    var $placa1;
    var $marca;
    var $conductor;
    var $constancia;
    var $brevete;
    var $totalpeso;
    var $tipotransporte;
    var $ructransportista;
    var $nombretransportista;
    var $referencia;
    var $detraccion;
    var $tref;
    var $fechareferencia;
    var $usuario;
    var $yape;
    var $efectivo;
    var $tarjeta;
    var $deposito;
    var $plin;
    var $credito;
    var $optigv;
    var $vuelto;
    var $sobrante;
    var $egresos;
    var $apertura;
    var $items = array();
    var $urlguiasunat = "https://e-factura.sunat.gob.pe/v1/contribuyente/gre/comprobantes/descargaqr?";
    var $qrsunat;

    public function generapdf($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);

        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;

        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 11, 10, -220);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(55);
        $pdf->cell(40, 5, (session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(150);

        $pdf->cell(50, 6, "RUC   " . session()->get("gene_nruc"), 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 6.5);

        $pdf->setx(55);
        $current_y = $pdf->GetY() + 2;
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(90, 4, trim(session()->get("gene_ptop")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->SetY($current_y - 2);
        $pdf->setx(150);
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->cell(50, 6, trim($this->tipocomprobante), 'LR', 1, 'C', 0);
        $pdf->cell(100);

        if (!empty(session()->get("gene_descrii"))) {
            $pdf->setx(55);
            $pdf->SetFont('Tahoma', '', 6.5);
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 106;
            $pdf->Multicell(100, 4, trim(session()->get("gene_descrii")), '', '', false);
            $pdf->SetXY($current_x + $cell_width, $current_y);
        }

        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(150);
        $pdf->cell(50, 6, $this->numero, 'BLR', 0, 'C', 0);
        $pdf->ln(4);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->SetFont('Tahoma', '', 6.5);
        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CORREO: ' . trim(session()->get("gene_correo")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CELULAR: ' . trim(session()->get("gene_fono")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('DejaVu', '', 8);
        if ($this->tdoc == '01') {
            $pdf->cell(100, 5, 'RUC: ' . $this->ruccliente);
            $pdf->setx(75);
        } else {
            $pdf->cell(100, 5, 'DNI: ' . $this->dnicliente);
            $pdf->setx(75);
        }
        $pdf->cell(100, 5, 'FECHA: ' . convertirformatofecha($this->fecha));
        $pdf->setx(150);
        $pdf->cell(50, 5, 'GUIA-REMISION: ' . $this->guiaremision);
        $pdf->Ln();
        $pdf->cell(100, 5, 'CLIENTE: ' . $this->cliente);
        $pdf->setx(150);
        $pdf->cell(50, 5, 'VENDEDOR: ' . $this->vendedor);
        $pdf->Ln();
        $pdf->cell(100, 5, 'DIRECCION: ' . $this->direccioncliente);

        $pdf->ln();
        if (\substr($this->formadepago, 0, 2) == 'CR') {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago . ' a ' . $this->dias . ' Dias');
        } else {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago);
        }
        $pdf->setx(150);
        $pdf->cell(80, 5, 'FECHA VTO: ' . convertirformatofecha($this->fechavto));
        $pdf->ln();
        $pdf->cell(100, 5, 'REFERENCIA: ' . $this->referencia);
        $pdf->setx(150);
        $pdf->cell(100, 5, 'MONEDA: ' . $this->moneda);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 7);

        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);

        $pdf->cell(26, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(106, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'V.U.', 1, 0, 'C', true);
        $pdf->cell(20, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Tahoma', '', 7);

        $logo = 'logos/' . trim($this->rucempresa) . '/logofondo.jpg';
        if (\file_exists($logo)) {
            $pdf->Image($logo, 32, 100, 150);
        }

        $i = 1;
        foreach ($this->items as $fila) {
            // $pdf->cell(8, 6, $i, 'L', 0, 'C', 0);
            $cant = (floatval($fila['cant']) > 0) ?  number_format($fila['cant'], 2, '.', ',') : '';
            $pdf->cell(26, 6, $cant, 'LR', 0, 'C', 0);
            $pdf->SetFont('DejaVu', '', 6);
            if ($fila['subtotal'] > 0) {
                $pdf->cell(18, 6, $fila['unid'], "LR", 0, 'C', 0);
            } else {
                $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            }
            $pdf->SetFont('DejaVu', '', 7);
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 106;
            $pdf->Multicell(106, 4,  $fila['descri'], "LR", 'L', false);
            $pdf->SetXY($current_x + $cell_width, $current_y);
            // $app = Application::getInstance();
            if ($this->optigv == 'N') {
                $precio = $fila['prec'] / $_SESSION['gene_igv'];
            } else {
                $precio = $fila['prec'];
            }
            $prec = (floatval($precio) > 0) ?  number_format($precio, 2, '.', ',') : '';
            $pdf->cell(18, 6, $prec, "LR", 0, 'C', 0);
            if ($fila['subtotal'] > 0) {
                $pdf->cell(20, 6, number_format($fila['subtotal'], 2, '.', ','), 'R', 1, 'C', 0);
            } else {
                $pdf->cell(20, 6, '', 'R', 1, 'C', 0);
            }
            $i++;
        }
        $tl = 30 - $i;

        while ($i <= $tl) :
            $pdf->cell(26, 6, '', 'LR', 0, 'C', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(106, 6, '', "LR", 0, 'L', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(20, 6, '', 'R', 1, 'C', 0);
            $i++;
        endwhile;

        $pdf->cell(188, 0, '', 'B', 1, 'C', 0);
        $pdf->cell(188, 2, '', 0, 1, 'C', 0);
        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->cell(120, 5, "SON: " . $this->importeletras);
        $pdf->setx(144);


        $ventasexon =  empty($_SESSION['config']['ventasexon']) ? 'N' : 'S';
        if ($ventasexon == 'S') {
            $this->valorgravado = 0;
            $this->vigv = 1;
            $this->igv = 0;
        }

        $pdf->cell(25, 6, 'VALOR GRAVADO', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->valorgravado, 2, '.', ','), 1, 0, 'R', 0);

        $pdf->ln();

        $dctocliente = $this->tdoc == '03' ? $this->dnicliente : $this->ruccliente;
        $tdctocliente = $this->tdoc == '03' ? '0' : '6';
        //CODIGO QR
        //RUC|TIPO DOC|SERIE|CORRELATIVO|IGV|TOTAL|FECHA EMISION|TIPO DOC CLIENTE|NUMERO DOC CLI|
        $ruta_qr = 'codigoqr' . '.png';
        $texto_qr = $this->rucempresa . '|' . $this->tdoc . '|' . $this->serie . '|' . $this->ndoc . '|' . $this->igv . '|' . $this->total . '|' . $this->fecha . '|' . $tdctocliente . '|' . $dctocliente . '|';
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);

        $y = $pdf->GetY();
        // $pdf->SetY($y + 2);
        $pdf->setx(37);

        $pdf->cell(50, 4, 'CUENTAS BANCO', 1, 0, 'C', true);
        $pdf->cell(50, 4, 'NUMERO', 1, 0, 'C', true);

        $cuentasbanco = obtenercuentasbanco();
        $pdf->SetFont('Tahoma', '', 7);
        $pdf->SetY($y + 4);
        foreach ($cuentasbanco as $bc) {
            $pdf->SetX(37);
            $pdf->cell(50, 4, $bc['mame'], 1, 0, 'C', 0);
            $pdf->cell(50, 4, $bc['number'], 1, 1, 'C', 0);
        }


        $pdf->SetY($y);

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'VALOR EXON.', 1, 0, 'R', 0);
        $ventasexon =  empty($_SESSION['config']['ventasexon']) ? '0.00' : $this->total;
        $pdf->cell(29, 6, $ventasexon, 1, 0, 'R', 0);
        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'I.G.V. ' . number_format(($this->vigv - 1) * 100, 2, '.', ',') . '%', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->igv, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();

        $pdf->setx(144);
        $pdf->cell(25, 6, 'ICBPER ', 1, 0, 'R', 0);
        $pdf->cell(29, 6, '0.00', 1, 0, 'R', 0);
        $pdf->ln();

        $pdf->setx(144);
        $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->ln();
        $mensaje =  empty($_SESSION['config']['ventasexon']) ? '' : 'Bienes Transferidos en la Amazonia Para ser consumidos en la misma';
        $pdf->cell(50, 6, $mensaje);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 6);
        $pdf->cell(50, 6, 'Representación Impresa de la Factura Electrónica');

        $pdf->SetX(80);
        $pdf->cell(80, 6, 'Este comprobante podrá ser consultado via web mediante este link https://compania-sysven.com/consulta');

        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    function generapdfpedido($rutapdf)
    {
        $pdf = new FPDF();
        $cletras = new Cletras();
        $pdf->AddPage('P', 'A4');

        $i = 1;
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 15, 10, -220);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setx(60);
        $pdf->cell(40, 5, \utf8_decode($this->empresa));
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setx(153);
        $pdf->cell(50, 6, "RUC   " . $this->rucempresa, 'LRT', 1, 'C', 0);
        $pdf->SetFont('Arial', '', 6);

        $pdf->setx(60);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(85, 3, trim($this->direccionempresa), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(153);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->cell(50, 6, $this->tipocomprobante, 'LR', 1, 'C', 0);
        $pdf->cell(100);

        $pdf->setx(60);
        $pdf->SetFont('Arial', '', 6);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, trim(session()->get("gene_descrii")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->SetFont('Arial', 'B', 10);

        $pdf->setx(153);
        $pdf->cell(50, 6, $this->numero, 'BLR', 0, 'C', 0);
        $pdf->ln(3);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->SetFont('Arial', '', 6);
        $pdf->setx(60);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CORREO: ' . trim(session()->get("gene_correo")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->setx(60);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CELULAR: ' . trim(session()->get("gene_fono")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->cell(100, 5, 'RUC: ' . $this->ruccliente);
        $pdf->setx(50);
        $pdf->cell(80, 5, 'DNI: ' . $this->dnicliente);
        $pdf->setx(100);
        $pdf->cell(100, 5, 'FECHA: ' . $this->fecha);
        $pdf->setx(155);
        $pdf->cell(100, 5, 'VENDEDOR: ' . trim($this->vendedor));
        $pdf->Ln();
        $pdf->cell(100, 5, 'CLIENTE : ' . utf8_decode($this->cliente));
        $pdf->setx(155);
        $pdf->cell(100, 5, 'MONEDA : ' . utf8_decode($this->moneda));
        //   $pdf->cell(50, 5, 'VENDEDOR: '). ' '.$this->vendedor;
        $pdf->Ln();
        $pdf->cell(100, 5, 'DIRECCION: ' . utf8_decode(trim($this->direccioncliente)));
        $pdf->setx(155);
        if (\substr($this->formadepago, 0, 2) == 'CR') {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago . ' a ' . $this->dias . ' Dias');
        } else {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago);
        }
        $pdf->ln();
        $pdf->setx(155);
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);

        $pdf->cell(8, 6, 'ITEM', 1, 0, 'C', true);
        $pdf->cell(15, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(114, 6, 'PRODUCTO', 1, 0, 'C', true);
        $pdf->cell(16, 6, 'V.U.', 1, 0, 'C', true);
        $pdf->cell(22, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 6);
        $i = 1;
        foreach ($this->items as $fila) {
            $pdf->cell(8, 6, $i, 1, 0, 'C', 0);
            $pdf->cell(15, 6, number_format($fila['cant'], 2, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(18, 6, $fila['unid'], 1, 0, 'C', 0);
            $pdf->cell(114, 6, $fila['descri'], 1, 0, 'L', 0);
            if ($this->optigv == 'N') {
                $precio = $fila['prec'] / $_SESSION['gene_igv'];
            } else {
                $precio = $fila['prec'];
            }
            $pdf->cell(16, 6, number_format($precio, 2, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(22, 6, number_format($fila['subtotal'], 2, '.', ','), 1, 1, 'R', 0);
            $i++;
        }

        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 7);
        $this->importeletras = $cletras->ValorEnLetras($this->total, $this->moneda === 'SOLES' ? 'SOLES' : 'DOLARES');
        $pdf->cell(120, 5, "SON: " . $this->importeletras);
        $pdf->setx(145);
        $pdf->ln();
        $pdf->setx(149);
        $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->Output('D', $rutapdf);
    }
    function generapdfguiatransportista($rutapdf, $estilo = '')
    {
        require('tfpdf.php');
        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        // $pdf->SetFont('DejaVu', '', 14);

        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }

        if (\file_exists($logo)) {
            $pdf->Image($logo, 10, 10, -220);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(55);
        $pdf->cell(40, 10, strtoupper($this->empresa));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(153);
        $pdf->cell(50, 6, "RUC   " . $this->rucempresa, 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 7);

        $pdf->setx(60);
        $pdf->cell(100, 6, trim($this->direccionempresa), '', '', 'C');
        $pdf->setx(153);
        $pdf->SetFont('DejaVu', '', 8);
        $pdf->cell(50, 6, "GUIA REMISIÓN", 'LR', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 7);
        $pdf->setx(50);
        $pdf->cell(100, 5, "SERVICIO DE TRANSPORTE DE CARGA A NIVEL NACIONAL", '', '', 'C');
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->SetX(153);
        $pdf->cell(50, 6, "ELECTRONICA TRANSPORTISTA", 'LR', 1, 'C', 0);

        $pdf->SetFont('DejaVu', '', 8);
        $pdf->SetX(153);
        $pdf->cell(50, 6, 'REG MTC N°' . ' ' . session()->get("gene_gene_rmtc"), 'LR', 1, 'C', 0);
        $pdf->SetFont('DejaVu', '', 8);
        $pdf->setx(55);

        // // Select a standard font (uses windows-1252)
        // $pdf->SetFont('Arial', '', 14);

        // $reportSubtitle = iconv('UTF-8', 'ASCII//TRANSLIT', $p);

        $p = "Fecha de Emisión:";
        $pdf->cell(100, 5,  $p . $this->fecha);
        $pdf->SetX(103);
        $pdf->cell(100, 5,  'Fecha Inicio Traslado:' . $this->fechat);
        $pdf->setx(153);
        $pdf->cell(50, 6, $this->numero, 'BLR', 0, 'C', 0);
        $pdf->ln(3);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->cell(100, 5, 'PUNTO PARTIDA: ' . trim($this->ptopartida));
        $pdf->Ln();
        $pdf->cell(80, 5, 'PUNTO LLEGADA: ' .  trim($this->ptollegada));
        $pdf->Ln();
        $pdf->Line(10, 55, 205, 55);
        $pdf->Ln();
        $pdf->cell(100, 5, 'REMITENTE..: ' . ($this->remitente));
        $pdf->setx(165);
        $pdf->cell(100, 5, 'RUC: ' . $this->rucremitente);
        $pdf->Ln();
        $pdf->cell(100, 5, 'DESTINATARIO: ' . trim($this->destinatario));
        $pdf->setx(165);
        $pdf->cell(100, 5, 'RUC: ' . $this->rucdestinatario);
        $pdf->ln();
        $pdf->Line(10, 70, 205, 70);

        $pdf->setx(155);
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(8, 6, 'ITEM', 1, 0, 'C', true);
        $pdf->cell(15, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(114, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(16, 6, 'PESO', 1, 0, 'C', true);
        $pdf->cell(22, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 6);
        $i = 1;
        foreach ($this->items as $fila) {
            $pdf->cell(8, 6, $i, 1, 0, 'C', 0);
            $pdf->cell(15, 6, number_format($fila['cant'], 2, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(18, 6, $fila['unid'], 1, 0, 'C', 0);
            $pdf->cell(114, 6, $fila['descri'], 1, 0, 'L', 0);
            $pdf->cell(16, 6, number_format($fila['peso'], 3, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(22, 6, number_format($fila['subtotal'], 2, '.', ','), 1, 1, 'R', 0);
            $i++;
        }

        $pdf->ln();

        // $pdf->cell(50, 6, "RUC   " . $this->rucempresa, 'LRT', 1, 'C', 0);

        // $pdf->ln();
        $pdf->SetFont('Tahomab', 'U', 8);
        $pdf->cell(125, 6, 'DATOS DEL VEHICULO', 'LRT', 1, 0, 'C');
        $pdf->SetFont('Tahoma', '', 8);
        $pdf->cell(125, 6, 'PLACA: ' . trim($this->placa) . ' ' . (empty($this->placa1) ? '' : trim($this->placa1)) . '  MARCA:' . ' ' . trim($this->marca), 'LR', '', '', '');
        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(143);
        $pdf->cell(25, 6, 'TOTAL PESO KG.', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->totalpeso, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->SetFont('Tahoma', '', 8);
        $pdf->cell(125, 6, 'CONSTANCIA DE INSCRIPCION: ' . trim("$this->constancia") . ' ' . '  CONFIGURACION VEHICULAR: T3-S3', 'LR', '', '', '');
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 8);
        $pdf->cell(125, 6, 'CONDUCTOR: ' . trim($this->conductor) . ' ' . 'BREVETE:' . trim($this->brevete), 'BLR', '', '', '');
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->cell(50, 6, 'SUB CONTRATADA: ', '', '', '');
        $pdf->SetFont('Tahoma', '', 8);
        $pdf->ln();
        $pdf->cell(50, 6, 'RAZON SOCIAL: ', '', '', '');
        $pdf->setx(150);
        $pdf->cell(50, 6, 'RUC: ', '', '', '');
        $pdf->ln();
        $pdf->cell(50, 6, 'REFERENCIA: ' . trim($this->referencia), '', '', '');
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    function generarPDFGuiaRemitente($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;
        // $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }

        if (\file_exists($logo)) {
            $pdf->Image($logo, 10, 5, -240);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(48);
        $pdf->cell(40, 5, strtoupper(session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(153);
        $pdf->cell(50, 6, "RUC   " . session()->get("gene_nruc"), 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 7);

        $pdf->setx(48);

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, trim(session()->get("gene_ptop")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(153);
        $pdf->SetFont('DejaVu', '', 8);

        $pdf->SetFont('Tahoma', '', 7);
        $pdf->setx(50);
        // $pdf->cell(100, 5, "SERVICIO DE TRANSPORTE DE CARGA A NIVEL NACIONAL", '', '', 'C');
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->SetX(153);
        $pdf->cell(50, 6, "GUIA REMITENTE ELECTRONICA", 'LR', 1, 'C', 0);

        $pdf->setx(153);
        $pdf->cell(50, 6, "Nro." . $this->numero, 'BLR', 0, 'C', 0);

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRASLADO', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->ln();
        $pdf->cell(100, 5,  "Fecha de Emisión: " . $this->fecha);
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Tipo de Transporte: " . $this->tipotransporte);
        $pdf->ln();
        $pdf->cell(100, 5,  'Fecha Inicio Traslado: ' . $this->fechat);
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Referencia: " . trim($this->referencia));
        $pdf->ln();
        $pdf->cell(100, 5,  'Motivo de Traslado: VENTA');
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Descripción del Motivo:");
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL DESTINATARIO', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'DESTINATARIO: ' . trim($this->destinatario));
        $pdf->ln();
        $pdf->cell(100, 5, 'RUC: ' . $this->rucdestinatario);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DE PUNTO DE PARTIDA Y PUNTO DE LLEGADA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        // $pdf->SetFont('Tahoma', '', 8);
        $pdf->cell(100, 5, 'PUNTO PARTIDA: ' . trim($this->ptopartida));
        $pdf->Ln();
        $pdf->cell(80, 5, 'PUNTO LLEGADA: ' .  trim($this->ptollegada));
        $pdf->Ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRANSPORTISTA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'RUC: ' . trim($this->ructransportista));
        $pdf->ln();
        $pdf->cell(100, 5, 'Nombre: ' . trim($this->nombretransportista));
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRANSPORTE', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->ln();
        $pdf->cell(100, 5, 'Placa: ' . trim($this->placa));
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Conductor: " . trim($this->conductor));
        $pdf->ln();
        $pdf->cell(100, 5, 'Marca: ' . trim($this->marca));
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Licencia N°: " . trim($this->brevete));
        $pdf->ln();
        $pdf->cell(100, 5, 'Registro MTC: ');
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(8, 6, 'ITEM', 1, 0, 'C', true);
        $pdf->cell(15, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(113, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(16, 6, 'PESO', 1, 0, 'C', true);
        // $pdf->cell(16, 6, 'SCOP', 1, 0, 'C', true);
        $pdf->cell(22, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 6);
        $i = 1;
        foreach ($this->items as $fila) {
            $pdf->cell(8, 6, $i, 1, 0, 'C', 0);
            $pdf->cell(15, 6, number_format($fila['cant'], 4, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(18, 6, $fila['unid'], 1, 0, 'C', 0);
            $pdf->cell(113, 6, $fila['descri'], 1, 0, 'L', 0);
            $pdf->cell(16, 6, number_format($fila['peso'], 3, '.', ','), 1, 0, 'R', 0);
            // $pdf->cell(16, 6, $fila['scop'], 1, 0, 'L', 0);
            $pdf->cell(22, 6, number_format($fila['subtotal'], 2, '.', ','), 1, 1, 'R', 0);
            $i++;
        }
        $pdf->ln();

        $ruta_qr = 'codigoqr' . '.png';
        $texto_qr = $this->urlguiasunat . $this->qrsunat;
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);

        $pdf->SetX(148);
        $pdf->SetFont('Tahoma', '', 7);
        $pdf->cell(25, 6, 'TOTAL PESO KG.', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->totalpeso, 3, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 6);
        $pdf->cell(100, 6, 'Representación Impresa de Guia Remitente');
        $pdf->SetX(90);
        $pdf->cell(50, 6, 'Conformidad del Cliente');
        $pdf->SetX(140);
        $pdf->cell(50, 6, 'P/' . session()->get("gene_empresa"));
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    function generarPDFGuiaRemitentecompra($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 10, 10, -220);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(48);
        $pdf->cell(40, 5, strtoupper($this->empresa));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(153);
        $pdf->cell(50, 6, "RUC   " . $this->rucempresa, 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 7);

        $pdf->setx(48);

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, $this->direccionempresa, '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(153);
        $pdf->SetFont('DejaVu', '', 8);

        $pdf->SetFont('Tahoma', '', 7);
        $pdf->setx(50);
        // $pdf->cell(100, 5, "SERVICIO DE TRANSPORTE DE CARGA A NIVEL NACIONAL", '', '', 'C');
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->SetX(153);
        $pdf->cell(50, 6, "GUIA REMITENTE ELECTRONICA", 'LR', 1, 'C', 0);

        $pdf->setx(153);
        $pdf->cell(50, 6, "Nro." . $this->numero, 'BLR', 0, 'C', 0);

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRASLADO', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->ln();
        $pdf->cell(100, 5,  "Fecha de Emisión: " . $this->fecha);
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Tipo de Transporte: " . $this->tipotransporte);
        $pdf->ln();
        $pdf->cell(100, 5,  'Fecha Inicio Traslado: ' . $this->fechat);
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Referencia: " . trim($this->referencia));
        $pdf->ln();
        $pdf->cell(100, 5,  'Motivo de Traslado: COMPRA');
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Descripción del Motivo:");
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL DESTINATARIO', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'DESTINATARIO: ' . trim($this->destinatario));
        $pdf->ln();
        $pdf->cell(100, 5, 'RUC: ' . $this->rucdestinatario);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL PROVEEDOR', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'NOMBRE: ' . trim($this->remitente));
        $pdf->ln();
        $pdf->cell(100, 5, 'RUC: ' . $this->rucremitente);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DE PUNTO DE PARTIDA Y PUNTO DE LLEGADA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        // $pdf->SetFont('Tahoma', '', 8);
        $pdf->cell(100, 5, 'PUNTO PARTIDA: ' . trim($this->ptopartida));
        $pdf->Ln();
        $pdf->cell(80, 5, 'PUNTO LLEGADA: ' .  trim($this->ptollegada));
        $pdf->Ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRANSPORTISTA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'RUC: ' . trim($this->ructransportista));
        $pdf->ln();
        $pdf->cell(100, 5, 'Nombre: ' . trim($this->nombretransportista));
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRANSPORTE', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->ln();
        $pdf->cell(100, 5, 'Placa: ' . trim($this->placa));
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Conductor: " . trim($this->conductor));
        $pdf->ln();
        $pdf->cell(100, 5, 'Marca: ' . trim($this->marca));
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Licencia N°: " . trim($this->brevete));
        $pdf->ln();
        $pdf->cell(100, 5, 'Registro MTC: ');
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(8, 6, 'ITEM', 1, 0, 'C', true);
        $pdf->cell(15, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(129, 6, 'DESCRIPCION', 1, 0, 'C', true);
        // $pdf->cell(16, 6, 'SCOP', 1, 0, 'C', true);
        $pdf->cell(22, 6, 'PESO TOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 6);
        $i = 1;
        $tpeso = 0;
        foreach ($this->items as $fila) {
            $pdf->cell(8, 6, $i, 1, 0, 'C', 0);
            $pdf->cell(15, 6, number_format($fila['cant'], 4, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(18, 6, $fila['unid'], 1, 0, 'C', 0);
            $pdf->cell(129, 6, $fila['descri'], 1, 0, 'L', 0);
            // $pdf->cell(16, 6, $fila['scop'], 1, 0, 'L', 0);
            // $pdf->cell(16, 6, number_format($fila['peso'], 2, '.', ','), 1, 0, 'R', 0);
            $pdf->cell(22, 6, number_format($fila['peso'], 3, '.', ','), 1, 1, 'R', 0);
            $i++;
            $tpeso += $fila['peso'];
        }
        $pdf->ln();

        $ruta_qr = 'codigoqr' . '.png';
        $texto_qr = $this->urlguiasunat . $this->qrsunat;
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);

        $pdf->SetFont('Tahomab', '', 8);
        $pdf->SetX(30);
        $pdf->cell(100, 8, ' Unidad de Medida del Peso Bruto: KGM');
        $pdf->SetX(148);
        $pdf->SetFont('Tahoma', '', 7);
        $pdf->cell(25, 6, 'TOTAL PESO KG.', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($tpeso, 3, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 6);
        $pdf->cell(100, 6, 'Representación Impresa de Guia Remitente');
        $pdf->SetX(90);
        $pdf->cell(50, 6, 'Conformidad del Cliente');
        $pdf->SetX(140);
        $pdf->cell(50, 6, 'P/' . session()->get("gene_empresa"));
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    public function generapdfnotacreditoydebito($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        // $pdf->SetFont('DejaVu', '', 14);
        // Load a UTF-8 string from a file and print it
        // $txt = ('Emisión');
        // $pdf->Write(8, $txt);
        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;

        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 10, 10, -220);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(55);
        $pdf->cell(40, 10, (session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(150);

        $pdf->cell(50, 6, "RUC   " . session()->get("gene_nruc"), 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 6.5);


        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $pdf->SetY($current_y + 1);
        $pdf->setx(55);
        $cell_width = 106;
        $pdf->Multicell(90, 4, trim(session()->get("gene_ptop")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(150);
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->cell(50, 6, $this->tipocomprobante, 'LR', 1, 'C', 0);
        $pdf->cell(100);

        $pdf->setx(55);
        $pdf->SetFont('Tahoma', '', 6.5);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, trim(session()->get("gene_descrii")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(150);
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->cell(50, 6, $this->numero, 'BLR', 0, 'C', 0);
        $pdf->ln(3);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->SetFont('Tahoma', '', 6.5);
        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CORREO: ' . trim(session()->get("gene_correo")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CELULAR: ' . trim(session()->get("gene_fono")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Tahoma', '', 8);
        if ($this->tref == '01') {
            $pdf->cell(100, 5, 'RUC: ' . $this->ruccliente);
            $pdf->setx(75);
        } else {
            $pdf->cell(100, 5, 'DNI: ' . $this->dnicliente);
            $pdf->setx(75);
        }
        $pdf->cell(100, 5, 'FECHA: ' . $this->fecha);
        $pdf->setx(150);
        $pdf->cell(50, 5, 'GUIA-REMISION: ' . $this->guiaremision);
        $pdf->Ln();
        $pdf->cell(100, 5, 'CLIENTE..: ' . $this->cliente);
        $pdf->setx(150);
        $pdf->cell(50, 5, 'VENDEDOR: OFICINA');
        $pdf->Ln();
        $pdf->cell(100, 5, 'DIRECCION: ' . $this->direccioncliente);

        $pdf->ln();
        if (\substr($this->formadepago, 0, 2) == 'CR') {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago . ' a ' . $this->dias . ' Dias');
        } else {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago);
        }
        $pdf->setx(150);
        $pdf->cell(80, 5, 'FECHA VTO.: ' . $this->fechavto);
        $pdf->ln();
        $pdf->cell(100, 5, 'REFERENCIA: ' . $this->referencia);
        $pdf->setx(120);
        $pdf->cell(100, 5, 'FECHA: ' . $this->fechareferencia);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 7);

        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        // $pdf->cell(8, 6, 'ITEM', 1, 0, 'C', true);
        $pdf->cell(26, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(106, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'V.U.', 1, 0, 'C', true);
        $pdf->cell(20, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Tahoma', '', 7);

        $logo = 'logos/' . trim($this->rucempresa) . '/logofondo.jpg';
        if (\file_exists($logo)) {
            $pdf->Image($logo, 32, 100, 150);
        }

        $i = 1;
        foreach ($this->items as $fila) {
            // $pdf->cell(8, 6, $i, 'L', 0, 'C', 0);
            $pdf->cell(26, 6, $fila['cant'], 'LR', 0, 'C', 0);
            // if ($fila['subtotal'] > 0) {
            $pdf->cell(18, 6, $fila['unid'], "LR", 0, 'C', 0);
            // } else {
            // $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            // }
            $pdf->cell(106, 6, ' ' . $fila['descri'], "LR", 0, 'L', 0);
            // $prec = (floatval($fila['prec']) > 0) ?  number_format($fila['prec'], 2, '.', ',') : '';
            $pdf->cell(18, 6, ($fila['prec']), "LR", 0, 'C', 0);
            // if ($fila['subtotal'] > 0) {
            $pdf->cell(20, 6, number_format($fila['subtotal'], 2, '.', ','), 'R', 1, 'C', 0);
            // } else {
            //     $pdf->cell(20, 6, '', 'R', 1, 'C', 0);
            // }
            $i++;
        }
        $tl = 30 - $i;

        while ($i <= $tl) :
            $pdf->cell(26, 6, '', 'LR', 0, 'C', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(106, 6, '', "LR", 0, 'L', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(20, 6, '', 'R', 1, 'C', 0);
            $i++;
        endwhile;

        $pdf->cell(188, 0, '', 'B', 1, 'C', 0);
        $pdf->cell(188, 2, '', 0, 1, 'C', 0);
        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->cell(120, 5, "SON: " . $this->importeletras);
        $pdf->setx(144);
        // $pdf->cell(25, 6, 'VALOR GRAVADO', 1, 0, 'R', 0);
        // $pdf->cell(29, 6, number_format($this->valorgravado, 2, '.', ','), 1, 0, 'R', 0);

        $lblsubtotal = 'VALOR GRAVADO';
        $ventasexon =  empty($_SESSION['config']['ventasexon']) ? 'N' : 'S';
        if ($ventasexon == 'S') {
            $this->valorgravado = $this->total;
            $this->vigv = 1;
            $this->igv = 0;
            $lblsubtotal = 'EXONERADO';
        }
        $pdf->cell(25, 6, $lblsubtotal, 1, 0, 'R', 0);
        $pdf->cell(29, 6, '-' . number_format($this->valorgravado, 2, '.', ','), 1, 0, 'R', 0);

        $pdf->ln();

        $dctocliente = $this->tdoc == '03' ? $this->dnicliente : $this->ruccliente;
        $tdctocliente = $this->tdoc == '03' ? '0' : '6';
        //CODIGO QR
        //RUC|TIPO DOC|SERIE|CORRELATIVO|IGV|TOTAL|FECHA EMISION|TIPO DOC CLIENTE|NUMERO DOC CLI|
        $texto_qr = $this->rucempresa . '|' . $this->tdoc . '|' . $this->serie . '|' . $this->ndoc . '|' . $this->igv . '|' . $this->total . '|' . $this->fecha . '|' . $tdctocliente . '|' . $dctocliente . '|';
        $ruta_qr = 'codigoqr' . '.png';
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);

        // $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);
        if ($this->detraccion != '0') {
            $pdf->SetFont('Tahomab', '', 6);
            $pdf->setx(35);
            $pdf->cell(105, 6, "OPERACION SUJETA AL SISTEMA DE OBLIGACIONES TRIBUTARIAS CON EL GOBIERNO CENTRAL", 1, 0, 'R', 0);
        }
        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'I.G.V. ' . number_format(($this->vigv - 1) * 100, 2, '.', ',') . '%', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->igv, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        if ($this->detraccion != '0') {
            $pdf->SetFont('DejaVu', '', 7);
            $pdf->setx(35);
            $pdf->cell(39.5, 6, "IMPORTE A DETRAER:  " . number_format($this->total, 2, '.', ',') . "   ", 1, 0, 'R', 0);
            $pdf->setx(74.5);
            $pdf->cell(32, 6, "DETRACCIÓN:  " . number_format($this->detraccion, 2, '.', ',') . "    ", 1, 0, 'R', 0);
            $pdf->setx(106.5);
            $totalPagar = floatval($this->total) - floatval($this->detraccion);
            $pdf->cell(33.5, 6, "TOTAL A PAGAR:  " . number_format($totalPagar, 2, '.', ',') . "  ", 1, 0, 'R', 0);
        }
        $pdf->setx(144);
        $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Tahoma', '', 6);
        $pdf->cell(50, 6, 'Representación Impresa de la Factura Electrónica');
        $pdf->SetX(80);
        $pdf->cell(80, 6, 'Este comprobante podrá ser consultado via web mediante este link https://compania-sysven.com/consulta');

        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    public function generarpdfticket($rutapdf, $estilo = '')
    {
        $totalitems = count($this->items);
        $ti = 240;
        for ($i = 0; $i <= $totalitems; $i++) {
            $ti += 16;
        }
        $pdf = new FPDF('P', 'mm', array(80, $ti));
        $pdf->AddPage();
        $pdf->SetMargins(-5, -10, 5);
        $pdf->SetFont('Arial', 'B', 12);

        // Agregar logo
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logoticket.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logoticket.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Cell(70, 3, $pdf->Image($logo, 9, -12, 62, 60), 0, 'C');
        }

        // Información de la tienda
        $pdf->Ln(21);
        $pdf->setx(5);
        $pdf->MultiCell(70, 5, trim($this->empresa), 0, 'C');
        $pdf->Ln(2);
        $pdf->setx(5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(70, 4, trim($this->direccionempresa), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'EMAIL: ' . trim($_SESSION['gene_correo']), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'CELULAR: ' . trim($_SESSION['gene_fono']), 0, 'C');

        // datos de la venta
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setx(5);
        $pdf->MultiCell(70, 6, trim($this->rucempresa), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 6, trim($this->tipocomprobante), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 6, trim($this->numero), 0, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('FECHA: ' . convertirformatofecha($this->fecha) . ' ' . $this->hora, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('FORMA DE PAGO: ' . $this->formadepago, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);

        if (empty($this->dnicliente) && $this->tdoc == '01') {
            $documentocliente = 'RUC: ' . $this->ruccliente;
        } else {
            $documentocliente = 'DNI: ' . $this->dnicliente;
        }

        $pdf->SetFont('Arial', '', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding($documentocliente, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);

        if (strlen($this->cliente) > 28) {
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 120;
            $pdf->setx(5);
            $pdf->Multicell(70, 5, mb_convert_encoding('CLIENTE: ' . $this->cliente, 'ISO-8859-1', 'UTF-8'), '', '', false);
            $pdf->SetXY($current_x + $cell_width, $current_y);
            $pdf->Ln(11);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->setx(5);
            $pdf->Cell(17, 5, mb_convert_encoding('CLIENTE: ' . $this->cliente, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
            $pdf->Ln(6);
        }

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->setx(5);
        $pdf->Multicell(70, 4, mb_convert_encoding('DIRECCIÓN: ' . $this->direccioncliente, 'ISO-8859-1', 'UTF-8'), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);
        if (strlen(trim($this->direccioncliente)) > 1) {
            $pdf->Ln(18);
        } else {
            $pdf->Ln(5);
        }
        // Línea divisora
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');

        $pdf->setx(5);
        // Encabezados de productos
        $pdf->Cell(10, 4, 'Cant.', 0, 0, 'L');
        $pdf->Cell(15, 4, 'U.M.', 0, 0, 'L');
        $pdf->Cell(22, 4, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(12, 4, 'Prec.', 0, 0, 'L');
        $pdf->Cell(15, 4, 'Impo.', 0, 1, 'L');

        // Línea divisora
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');

        // Detalles de cada producto
        // $totalProductos = 0;
        $pdf->SetFont('Arial', '', 8);
        $i = 0;
        $pdf->setx(5);
        foreach ($this->items as $row_detalle) {
            if ($i != 0) {
                $pdf->ln(3);
            }
            $pdf->setx(5);
            $pdf->SetFont('Arial', '', 7);
            $importe = number_format($row_detalle['cant'] * $row_detalle['prec'], 2, '.', ',');
            // $totalProductos += $row_detalle['cant'];
            $pdf->Cell(10, 4,  number_format($row_detalle['cant'], 2, '.', ','), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $yInicio = $pdf->GetY();
            $pdf->MultiCell(38, 4, trim($row_detalle['unid']), 0, 'L');
            $yFin = $pdf->GetY();
            $pdf->SetXY(30, $yInicio);

            $yInicio = $pdf->GetY();
            $pdf->SetFont('Arial', '', 8);
            $yFin = $pdf->GetY();
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetXY(45, $yInicio);
            // $app = Application::getInstance();
            if ($this->optigv == 'N') {
                $precio = $row_detalle['prec'] / $_SESSION['gene_igv'];
            } else {
                $precio = $row_detalle['prec'];
            }
            $pdf->Cell(15, 4, ' ' . ' ' . number_format($precio, 2, '.', ','), 0, 0, 'R');
            $pdf->SetXY(60, $yInicio);
            $pdf->Cell(13, 4, ' ' . ' ' . $importe, 0, 1, 'R');
            $pdf->SetY($yFin);
            $pdf->ln();
            $i = $i + 1;
            $pdf->setx(5);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->MultiCell(70, 4, trim(mb_convert_encoding($row_detalle['descri'], 'ISO-8859-1', 'UTF-8')) . '.', 0, 'L');
        }

        // Información final
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');
        $pdf->ln(3);
        $pdf->setx(5);
        $pdf->Cell(70, 4, mb_convert_encoding('ITEMS:  ' . $i, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9);

        if ($this->tdoc != '20') {
            // $pdf->setx(0);
            $totalgravado =  empty($_SESSION['config']['ventasexon']) ? number_format($this->valorgravado, 2, '.', ',') : '0.00';
            $pdf->Cell(67, 5, 'Op. Grav:', 0, 0, 'R');
            $pdf->setx(6.5);
            $pdf->Cell(67, 5, $totalgravado, 0, 1, 'R');


            // $pdf->setx(0);
            $totalexon =  empty($_SESSION['config']['ventasexon']) ? '0.00' : $this->total;
            $pdf->Cell(67, 5, 'Op. Exon:', 0, 0, 'R');
            $pdf->setx(6.5);
            $pdf->Cell(67, 5, $totalexon, 0, 1, 'R');


            // $pdf->setx(0);
            $totaligv =  empty($_SESSION['config']['ventasexon']) ? number_format($this->igv, 2, '.', ',') : '0.00';
            $pdf->Cell(67, 5, 'IGV:', 0, 0, 'R');
            $pdf->setx(6.5);
            $pdf->Cell(67, 5, $totaligv, 0, 1, 'R');
        }
        // $pdf->setx(0);
        $pdf->Cell(67, 5, 'Total:', 0, 0, 'R');
        $pdf->setx(6.5);
        $pdf->Cell(67, 5, number_format($this->total, 2, '.', ','), 0, 1, 'R');


        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Ln(6);
        $pdf->setx(5);
        $pdf->Cell(70, 5, 'SON: ' . $this->importeletras, 0, 1, 'L');
        if (!empty($this->vuelto)) {
            $pdf->Ln(1);
            $pdf->setx(5);
            $pdf->Cell(70, 5, 'PAGO: ' . number_format($this->vuelto + $this->total, 2, '.', ','), 0, 1, 'L');
            $pdf->Ln(1);
            $pdf->setx(5);
            $pdf->Cell(70, 5, 'VUELTO: ' .  number_format($this->vuelto, 2, '.', ','), 0, 1, 'L');
        }
        $pdf->Ln(7);
        $pdf->SetFont('Arial', '', 8);
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'Agradecemos su preferencia, vuelva pronto!!!', 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, mb_convert_encoding('Puede consultar este comprobante vía web:', 'ISO-8859-1', 'UTF-8'), 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'https://info.companysysven.com/consulta', 0, 'C');
        $pdf->Ln(7);
        $texto_qr = $this->rucempresa . '|' . $this->tdoc . '|' . $this->serie . '|' . $this->ndoc . '|' . $this->igv . '|' . $this->total . '|' . $this->fecha;
        $ruta_qr = 'codigoqr' . '.png';
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        // $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);
        $pdf->Cell(70, 3, $pdf->Image($ruta_qr, 32, $pdf->GetY(), 15), 0, 'C');

        // Cerrar conexiones y generar el PDF
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            #DESCARGAR PDF
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    public function generapdfcaja($rutapdf)
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);

        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;

        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 11, 3, -220);
        }

        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(55);
        $pdf->cell(40, 5, (session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(150);

        $pdf->cell(50, 6, "LIQUIDACION CAJA", 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 6.5);

        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(90, 4, trim(session()->get("gene_ptop")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(150);
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->cell(50, 6, "DOCUMENTO INTERNO", 'LR', 1, 'C', 0);
        $pdf->cell(100);

        $pdf->setx(55);
        $pdf->SetFont('Tahoma', '', 6.5);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, "", '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(150);
        $pdf->cell(50, 6, trim(session()->get("gene_nruc")), 'BLR', 0, 'C', 0);
        $pdf->ln(4);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 6.5);

        $pdf->cell(100, 5, 'USUARIO: ' . $this->cliente);
        $pdf->setx(150);
        $pdf->cell(50, 5, 'FECHA: ' . $this->fecha);
        $pdf->Ln();
        $pdf->ln(5);
        // $pdf->setx(150);
        // $pdf->cell(50, 5, 'VENDEDOR: ' . $this->vendedor);
        // $pdf->Ln();
        // $pdf->cell(100, 5, 'DIRECCION: ' . $this->direccioncliente);

        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);

        $pdf->cell(26, 6, 'Detalle', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'N. Documento', 1, 0, 'C', true);
        $pdf->cell(14, 6, 'Efectivo', 1, 0, 'C', true);
        $pdf->cell(14, 6, 'Credito', 1, 0, 'C', true);
        $pdf->cell(14, 6, 'Deposito', 1, 0, 'C', true);
        $pdf->cell(14, 6, 'Tarjeta', 1, 0, 'C', true);
        $pdf->cell(11, 6, 'PLIN', 1, 0, 'C', true);
        $pdf->cell(11, 6, 'YAPE', 1, 0, 'C', true);
        // $pdf->cell(14, 6, 'Cont. Ent.', 1, 0, 'C', true);
        $pdf->cell(14, 6, 'Egresos', 1, 0, 'C', true);
        $pdf->cell(14, 6, 'Usuario', 1, 0, 'C', true);
        $pdf->cell(10, 6, 'Mon.', 1, 0, 'C', true);
        $pdf->cell(29, 6, 'Fecha', 1, 1, 'C', true);

        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Tahoma', '', 6);

        $i = 1;
        $efectivo = 0;
        $credito = 0;
        $deposito = 0;
        $tarjeta = 0;
        $yape = 0;
        $plin = 0;
        $total = 0;
        foreach ($this->items as $fila) {
            // $current_y = $pdf->GetY();
            // $current_x = $pdf->GetX();
            // $cell_width = 26;
            // $pdf->sety($current_y + 1);
            $pdf->SetFont('DejaVu', '', 5);
            // $pdf->Multicell(26, 3,  $fila['deta'], "LR", 'L', false);
            // $pdf->Multicell(26, 4,  '', "LR", 'L', false);
            // $pdf->sety($current_y);
            // $pdf->SetFont('Tahoma', '', 6);
            // $pdf->SetXY($current_x + $cell_width, $current_y);
            // $pdf->Multicell(26, 4,  '', "LR", 'L', false);
            $pdf->cell(26, 6, substr($fila['deta'], 0, 24) . '.', 'LR', 0, 'C', 0);
            $pdf->cell(18, 6, $fila['ndoc'], "LR", 0, 'C', 0);
            $pdf->cell(14, 6, $fila['efectivo'], "LR", 0, 'C', 0);
            $efectivo += evaluarvalortdoccaja($fila['ndoc'], $fila['efectivo']);
            $pdf->cell(14, 6, $fila['credito'], "LR", 0, 'C', 0);
            $credito += evaluarvalortdoccaja($fila['ndoc'], $fila['credito']);
            $pdf->cell(14, 6, $fila['deposito'], "LR", 0, 'C', 0);
            $deposito += evaluarvalortdoccaja($fila['ndoc'], $fila['deposito']);
            $pdf->cell(14, 6, $fila['tarjeta'], "LR", 0, 'C', 0);
            $tarjeta += evaluarvalortdoccaja($fila['ndoc'], $fila['tarjeta']);
            $pdf->cell(11, 6, $fila['yape'], "LR", 0, 'C', 0);
            $yape += evaluarvalortdoccaja($fila['ndoc'], $fila['yape']);
            $pdf->cell(11, 6, $fila['plin'], "LR", 0, 'C', 0);
            $plin += evaluarvalortdoccaja($fila['ndoc'], $fila['plin']);
            // $pdf->cell(14, 6, $fila['Centrega'], "LR", 0, 'C', 0);
            $pdf->cell(14, 6, $fila['egresos'], "LR", 0, 'C', 0);
            $egresos += evaluarvalortdoccaja($fila['ndoc'], $fila['egresos']);
            $pdf->cell(14, 6, $fila['usua'], "LR", 0, 'C', 0);
            $pdf->cell(10, 6, $fila['mone'], "LR", 0, 'C', 0);
            $pdf->cell(29, 6, $fila['fechao'], 'R', 1, 'C', 0);
            $i++;
        }
        $tl = 30 - $i;

        while ($i <= $tl) :
            $pdf->cell(26, 6, '', 'LR', 0, 'C', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(11, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(11, 6, '', "LR", 0, 'C', 0);
            // $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(14, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(10, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(29, 6, '', 'R', 1, 'C', 0);
            $i++;
        endwhile;

        $pdf->cell(189, 0, '', 'B', 1, 'C', 0);
        // $pdf->cell(188, 2, '', 0, 1, 'C', 0);
        $pdf->cell(26, 6, 'TOTALES', 'LTB', 0, 'R');
        $pdf->cell(18, 6, '', 'RTB', 0, 'R');
        $pdf->cell(14, 6, number_format($efectivo, 2), 1, 0, 'R');
        $pdf->cell(14, 6, number_format($credito, 2), 1, 0, 'R');
        $pdf->cell(14, 6, number_format($deposito, 2), 1, 0, 'R');
        $pdf->cell(14, 6, number_format($tarjeta, 2), 1, 0, 'R');
        $pdf->cell(11, 6, number_format($yape, 2), 1, 0, 'R');
        $pdf->cell(11, 6, number_format($plin, 2), 1, 0, 'R');
        $pdf->cell(14, 6, number_format($egresos, 2), 1, 0, 'R');
        $pdf->cell(14, 6, '', 1, 0, 'R');
        $pdf->cell(10, 6, '', 1, 0, 'R');
        $pdf->cell(29, 6, '', 1, 0, 'R');
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 7);
        $pdf->cell(100, 5, 'ESTE DOCUMENTO FUE GENERADO POR EL SISTEMA.');
        $pdf->Output($rutapdf, 'F');
    }
    public function generarticketcaja($rutapdf, $estilo = '')
    {
        $pdf = new FPDF('P', 'mm', array(80,220));
        $pdf->AddPage();
        $pdf->SetMargins(-5, -10, 5);
        $pdf->SetFont('Arial', 'B', 12);

        // Agregar logo
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($_SESSION['gene_nruc']) . '/logoticket.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($_SESSION['gene_nruc']) . '/logoticket.jpg';
        }
        $pdf->Cell(70, 3, $pdf->Image($logo, 25, 8, 32, 30), 0, 'C');
        // $pdf->Image($logo, 18, 2, 45);

        // Información de la tienda
        $pdf->Ln(21);
        $pdf->setx(5);
        $pdf->MultiCell(70, 5, trim($_SESSION['gene_empresa']), 0, 'C');
        $pdf->Ln(2);
        $pdf->setx(5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(70, 4, trim($_SESSION['gene_ptop']), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'EMAIL: ' . trim($_SESSION['gene_correo']), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'CELULAR: ' . trim($_SESSION['gene_fono']), 0, 'C');

        // datos de la venta
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setx(5);
        $pdf->MultiCell(70, 6, trim($_SESSION['gene_nruc']), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 6, mb_convert_encoding('TICKET - LIQUIDACIÓN CAJA ', 'ISO-8859-1', 'UTF-8'), 0, 'C');
        $pdf->setx(5);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('FECHA: ' . $this->fecha, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(6);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('HORA: ' . date("H:i:s a"), 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(6);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('USUARIO: ' . $this->usuario, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(10);

        // Línea divisora
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Ln();
        $pdf->setx(5);
        $pdf->Cell(50, 4, 'EFECTIVO: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->efectivo - $this->sobrante - $this->apertura, 2)), 0, 1, 'L');
        $pdf->ln();

          $pdf->setx(5);
        $pdf->Cell(50, 4, 'APERTURA: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->apertura, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, 'SOBRANTE: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->sobrante, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, 'EGRESOS: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->egresos, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->setx(5);
        $pdf->Cell(50, 4, 'TOTAL EFECTIVO: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round(($this->efectivo) - floatval($this->egresos), 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, mb_convert_encoding('DEPÓSITO: ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->deposito, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, mb_convert_encoding('CRÉDITO: ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->credito, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, 'TARJETA: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->tarjeta, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, 'YAPE: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->yape, 2)), 0, 1, 'L');
        $pdf->ln();

        $pdf->setx(5);
        $pdf->Cell(50, 4, 'PLIN: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round($this->plin, 2)), 0, 1, 'L');
        $pdf->ln();

        // Línea divisora
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');
        $pdf->ln();
        $pdf->setx(5);
        $pdf->Cell(50, 4, 'TOTAL: ', 0, 0, 'L');
        $pdf->Cell(15, 4, 'S/ ' . (Round(floatval($this->total), 2)), 0, 1, 'L');
        $pdf->ln(2);
        $pdf->setx(5);
        $pdf->MultiCell(70, 5, trim('GLOSA: ' . (empty($this->referencia) ? '-' : $this->referencia)), 0, 'L');
        $pdf->ln();
        // Cerrar conexiones y generar el PDF
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            #DESCARGAR
            $pdf->Output($rutapdf, 'D');
        }
    }
    public function generapdfordencompra($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);

        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;

        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 11, 10, -220);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(55);
        $pdf->cell(40, 5, (session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(150);

        $pdf->cell(50, 6, "RUC   " . session()->get("gene_nruc"), 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 6.5);

        $pdf->setx(55);
        $current_y = $pdf->GetY() + 2;
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(90, 4, trim(session()->get("gene_ptop")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->SetY($current_y - 2);
        $pdf->setx(150);
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->cell(50, 6, 'ORDEN DE COMPRA', 'LR', 1, 'C', 0);
        $pdf->cell(100);

        if (!empty(session()->get("gene_descrii"))) {
            $pdf->setx(55);
            $pdf->SetFont('Tahoma', '', 6.5);
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 106;
            $pdf->Multicell(100, 4, trim(session()->get("gene_descrii")), '', '', false);
            $pdf->SetXY($current_x + $cell_width, $current_y);
        }

        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(150);
        $pdf->cell(50, 6, $this->numero, 'BLR', 0, 'C', 0);
        $pdf->ln(4);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->SetFont('Tahoma', '', 6.5);
        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CORREO: ' . trim(session()->get("gene_correo")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->setx(55);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, 'CELULAR: ' . trim(session()->get("gene_fono")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('DejaVu', '', 8);
        $pdf->cell(100, 5, 'RUC: ' . $this->ruccliente);
        $pdf->setx(150);
        $pdf->cell(100, 5, 'FECHA: ' . $this->fecha);
        $pdf->setx(150);
        $pdf->Ln();
        $pdf->cell(100, 5, 'EMPRESA: ' . $this->cliente);
        $pdf->setx(150);
        $pdf->cell(100, 5, 'DESPACHADO POR: ' . $this->vendedor);
        $pdf->Ln();
        $pdf->cell(100, 5, 'ATENDIDO: ' . $this->constancia);
        $pdf->setx(150);
        $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago);
        $pdf->setx(150);
        $pdf->ln();
        $pdf->cell(100, 5, 'REFERENCIA: ' . $this->referencia);
        $pdf->setx(150);
        $pdf->cell(100, 5, 'MONEDA: ' . $this->moneda);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 7);

        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);

        $pdf->cell(26, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(106, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'V.U.', 1, 0, 'C', true);
        $pdf->cell(20, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Tahoma', '', 7);

        $logo = 'logos/' . trim($this->rucempresa) . '/logofondo.jpg';
        if (\file_exists($logo)) {
            $pdf->Image($logo, 32, 100, 150);
        }

        $i = 1;
        foreach ($this->items as $fila) {
            // $pdf->cell(8, 6, $i, 'L', 0, 'C', 0);
            $cant = (floatval($fila['cant']) > 0) ?  number_format($fila['cant'], 2, '.', ',') : '';
            $pdf->cell(26, 6, $cant, 'LR', 0, 'C', 0);
            if ($fila['subtotal'] > 0) {
                $pdf->cell(18, 6, $fila['unid'], "LR", 0, 'C', 0);
            } else {
                $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            }
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 106;
            $pdf->Multicell(106, 4,  $fila['descri'], "LR", 'L', false);
            $pdf->SetXY($current_x + $cell_width, $current_y);
            $precio = $fila['prec'];
            $prec = (floatval($precio) > 0) ?  number_format($precio, 2, '.', ',') : '';
            $pdf->cell(18, 6, $prec, "LR", 0, 'C', 0);
            if ($fila['subtotal'] > 0) {
                $pdf->cell(20, 6, number_format($fila['subtotal'], 2, '.', ','), 'R', 1, 'C', 0);
            } else {
                $pdf->cell(20, 6, '', 'R', 1, 'C', 0);
            }
            $i++;
        }
        $tl = 30 - $i;

        while ($i <= $tl) :
            $pdf->cell(26, 6, '', 'LR', 0, 'C', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(106, 6, '', "LR", 0, 'L', 0);
            $pdf->cell(18, 6, '', "LR", 0, 'C', 0);
            $pdf->cell(20, 6, '', 'R', 1, 'C', 0);
            $i++;
        endwhile;

        $pdf->cell(188, 0, '', 'B', 1, 'C', 0);
        $pdf->cell(188, 2, '', 0, 1, 'C', 0);
        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'VALOR GRAVADO', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->valorgravado, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();

        $dctocliente = $this->tdoc == '03' ? $this->dnicliente : $this->ruccliente;
        $tdctocliente = $this->tdoc == '03' ? '0' : '6';
        //CODIGO QR
        //RUC|TIPO DOC|SERIE|CORRELATIVO|IGV|TOTAL|FECHA EMISION|TIPO DOC CLIENTE|NUMERO DOC CLI|
        $ruta_qr = 'codigoqr' . '.png';
        $texto_qr = $this->rucempresa . '|' . $this->tdoc . '|' . $this->serie . '|' . $this->ndoc . '|' . $this->igv . '|' . $this->total . '|' . $this->fecha . '|' . $tdctocliente . '|' . $dctocliente . '|';
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);

        $y = $pdf->GetY();
        $pdf->setx(37);

        $pdf->SetY($y);

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'VALOR EXON.', 1, 0, 'R', 0);
        $pdf->cell(29, 6, '0.00', 1, 0, 'R', 0);
        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'I.G.V. ' . number_format(($this->vigv - 1) * 100, 2, '.', ',') . '%', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->igv, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();

        $pdf->setx(144);
        $pdf->cell(25, 6, 'ICBPER ', 1, 0, 'R', 0);
        $pdf->cell(29, 6, '0.00', 1, 0, 'R', 0);
        $pdf->ln();

        $pdf->setx(144);
        $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->ln();
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    function generarPDFguiatraspaso($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;
        // $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
        }

        if (\file_exists($logo)) {
            $pdf->Image($logo, 10, 5, -240);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(48);
        $pdf->cell(40, 5, strtoupper(session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(153);
        $pdf->cell(50, 6, "RUC   " . session()->get("gene_nruc"), 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 7);

        $pdf->setx(48);

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->Multicell(100, 4, trim(session()->get("gene_ptop")), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->setx(153);
        $pdf->SetFont('DejaVu', '', 8);

        $pdf->SetFont('Tahoma', '', 7);
        $pdf->setx(50);
        // $pdf->cell(100, 5, "SERVICIO DE TRANSPORTE DE CARGA A NIVEL NACIONAL", '', '', 'C');
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->SetX(153);
        $pdf->cell(50, 6, "GUIA REMITENTE ELECTRONICA", 'LR', 1, 'C', 0);

        $pdf->setx(153);
        $pdf->cell(50, 6, "Nro." . $this->numero, 'BLR', 0, 'C', 0);

        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRASLADO', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->ln();
        $pdf->cell(100, 5,  "Fecha de Emisión: " . $this->fecha);
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Tipo de Transporte: " . $this->tipotransporte);
        $pdf->ln();
        $pdf->cell(100, 5,  'Fecha Inicio Traslado: ' . $this->fechat);
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Referencia: " . trim($this->referencia));
        $pdf->ln();
        $pdf->cell(100, 5,  'Motivo de Traslado: Traspaso entre Almacenes');
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Descripción del Motivo:");
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DE LA EMPRESA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'RAZÓN SOCIAL: ' . trim($_SESSION['gene_empresa']));
        $pdf->ln();
        $pdf->cell(100, 5, 'RUC: ' . trim($_SESSION['gene_nruc']));
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DE PUNTO DE PARTIDA Y PUNTO DE LLEGADA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        // $pdf->SetFont('Tahoma', '', 8);
        $pdf->cell(100, 5, 'PUNTO PARTIDA: ' . trim($this->ptopartida));
        $pdf->Ln();
        $pdf->cell(80, 5, 'PUNTO LLEGADA: ' .  trim($this->ptollegada));
        $pdf->Ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRANSPORTISTA', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->cell(100, 5, 'RUC: ' . trim($this->ructransportista));
        $pdf->ln();
        $pdf->cell(100, 5, 'Nombre: ' . trim($this->nombretransportista));
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(192, 5, 'DATOS DEL TRANSPORTE', 1, 0, 'L', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 7);
        $pdf->ln();
        $pdf->cell(100, 5, 'Placa: ' . trim($this->placa));
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Conductor: " . trim($this->conductor));
        $pdf->ln();
        $pdf->cell(100, 5, 'Marca: ' . trim($this->marca));
        $pdf->setx(120);
        $pdf->cell(100, 5,  "Licencia N°: " . trim($this->brevete));
        $pdf->ln();
        $pdf->cell(100, 5, 'Registro MTC: ' . (empty(trim($this->constancia)) ? ' TRAMITE ' : trim($this->constancia)));
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 6);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);
        $pdf->cell(8, 6, 'ITEM', 1, 0, 'C', true);
        $pdf->cell(15, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(24, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(107, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(16, 6, 'PESO', 1, 0, 'C', true);
        // $pdf->cell(16, 6, 'SCOP', 1, 0, 'C', true);
        $pdf->cell(22, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('DejaVu', '', 6);
        $i = 1;
        foreach ($this->items as $fila) {
            $pdf->cell(8, 6, $i, 1, 0, 'C', 0);
            $pdf->cell(15, 6, number_format($fila['cant'], 4, '.', ','), 1, 0, 'R', 0);
            $pdf->SetFont('DejaVu', '', 5);
            $pdf->cell(24, 6, $fila['unid'], 1, 0, 'C', 0);
            $pdf->SetFont('DejaVu', '', 6);
            $pdf->cell(107, 6, $fila['descri'], 1, 0, 'L', 0);
            $pdf->cell(16, 6, number_format($fila['peso'], 3, '.', ','), 1, 0, 'R', 0);
            // $pdf->cell(16, 6, empty($fila['scop']) ? ' ' : $fila['scop'], 1, 0, 'L', 0);
            $pdf->cell(22, 6, number_format($fila['subtotal'], 2, '.', ','), 1, 1, 'R', 0);
            $i++;
        }
        $pdf->ln();

        $ruta_qr = 'codigoqr' . '.png';
        $texto_qr = $this->urlguiasunat . $this->qrsunat;
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);

        $pdf->SetX(148);
        $pdf->SetFont('Tahoma', '', 7);
        $pdf->cell(25, 6, 'TOTAL PESO KG.', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->totalpeso, 3, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('DejaVu', '', 6);
        $pdf->cell(100, 6, 'Representación Impresa de Guia Remitente');
        $pdf->SetX(90);
        $pdf->cell(50, 6, 'Conformidad del Cliente');
        $pdf->SetX(140);
        $pdf->cell(50, 6, 'P/' . session()->get("gene_empresa"));
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
}
